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
        // PERBAIKAN VALIDASI: Tambahkan mimes biar server gak nolak file dari HP
        $request->validate([
            'comment_id'        => 'required|exists:comments,id',
            'status_kerja'      => 'required',
            'content'           => 'nullable|string', 
            'photo_maintenance' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            // Tambahin mimes mp4 karena .m4a sering kedeteksi sebagai mp4 audio
            'voice_note'        => 'nullable|file|mimes:mp3,wav,m4a,mp4,aac|max:15000', 
        ]);

        // Proteksi Duplikat (Udah bener)
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
            
            // Fix: Catat siapa yang login (Mekanik)
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
                // Update status induk (pending -> PROSES/DONE)
                $comment->update(['status' => $request->status_kerja]);

                try {
                    $emailUser = $comment->user->email;
                    if ($emailUser) {
                        // Pastikan Mail diproses secara Queue jika memungkinkan agar tidak lambat
                        Mail::to($emailUser)->send(new RepairSelesaiMail($maintenance, $comment));
                    }
                } catch (\Exception $e) {
                    \Log::error("Gagal kirim email: " . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data perbaikan berhasil disimpan!',
                'data'    => $maintenance->load('user')
            ], 201);
        });
    }
}