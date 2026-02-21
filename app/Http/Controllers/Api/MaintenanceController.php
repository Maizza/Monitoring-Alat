<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Comment;
use App\Mail\RepairSelesaiMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB; // Tambahkan ini

class MaintenanceController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        // 1. Validasi input
        $request->validate([
            'comment_id'        => 'required|exists:comments,id',
            'status_kerja'      => 'required',
            'content'           => 'nullable|string', 
            'photo_maintenance' => 'nullable|image|max:5120',
            'voice_note'        => 'nullable|file|max:15000', 
        ]);

        // --- PROTEKSI ANTI-DUPLIKAT (START) ---
        // Cek apakah laporan ini sudah pernah di-submit sebelumnya
        $existing = Maintenance::where('comment_id', $request->comment_id)
                                ->where('status_kerja', $request->status_kerja)
                                ->where('created_at', '>=', now()->subSeconds(30)) // Cek dalam 30 detik terakhir
                                ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan ini sudah terkirim, mohon tunggu sebentar.'
            ], 422);
        }
        // --- PROTEKSI ANTI-DUPLIKAT (END) ---

        // Pake DB Transaction biar kalau email gagal, data database tetap konsisten
        return DB::transaction(function () use ($request) {
            $maintenance = new Maintenance();
            $maintenance->comment_id   = $request->comment_id;
            $maintenance->status_kerja = $request->status_kerja;
            $maintenance->content      = $request->input('content'); 

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
                $newStatus = ($request->status_kerja == 'DONE') ? 'DONE' : 'Preventive';
                $comment->update(['status' => $newStatus]);

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
                'data'    => $maintenance
            ], 201);
        });
    }
}