<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Comment;
use App\Mail\RepairSelesaiMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'comment_id'        => 'required|exists:comments,id',
            'status_kerja'      => 'required',
            'content'           => 'nullable|string', 
            'photo_maintenance' => 'nullable|image|max:5120',
            'voice_note'        => 'nullable|file|max:15000', 
        ]);

        $existing = Maintenance::where('comment_id', $request->comment_id)
                                ->where('status_kerja', $request->status_kerja)
                                ->where('created_at', '>=', now()->subSeconds(10)) 
                                ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan duplikat terdeteksi.'
            ], 422);
        }

        return DB::transaction(function () use ($request) {
            $maintenance = new Maintenance();
            $maintenance->comment_id   = $request->comment_id;
            $maintenance->status_kerja = $request->status_kerja;
            $maintenance->content      = $request->input('content'); 
            
            // FIX 1: SIMPAN USER ID (MEKANIK)
            // Tanpa ini, relasi user di Flutter bakal null dan nampilin "Mekanik Terhapus"
            $maintenance->user_id      = auth()->id(); 

            if ($request->hasFile('photo_maintenance')) {
                $path = $request->file('photo_maintenance')->store('uploads/maintenance', 'public');
                $maintenance->photo_maintenance = $path;
            }

            if ($request->hasFile('voice_note')) {
                $voicePath = $request->file('voice_note')->store('uploads/voice_notes', 'public');
                $maintenance->voice_note = $voicePath;
            }

            $maintenance->save(); 

            $comment = Comment::with('user', 'alat')->find($request->comment_id);
            
            if ($comment) {
                // FIX 2: LOGIKA UPDATE STATUS INDUK
                // Lu pakai status_kerja yang dikirim dari Flutter langsung biar fleksibel
                $comment->update(['status' => $request->status_kerja]);

                try {
                    $emailUser = $comment->user->email;
                    if ($emailUser) {
                        Mail::to($emailUser)->send(new RepairSelesaiMail($maintenance, $comment));
                    }
                } catch (\Exception $e) {
                    \Log::error("Gagal kirim email: " . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data perbaikan berhasil disimpan!',
                // Load user biar Flutter dapet nama mekanik secara real-time
                'data'    => $maintenance->load('user')
            ], 201);
        });
    }
}