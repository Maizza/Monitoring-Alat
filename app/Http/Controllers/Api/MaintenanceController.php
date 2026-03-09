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
use Illuminate\Support\Facades\Log;

class MaintenanceController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        // 1. Validasi
        $request->validate([
            'comment_id'        => 'required|exists:comments,id',
            'status_kerja'      => 'required', 
            'content'           => 'required|string', 
            'photo_maintenance' => 'nullable|image|max:10240', 
            'voice_note'        => 'nullable|file|max:20480', 
        ]);

        // 2. PROTEKSI ANTI DUPLIKAT (SATPAM SERVER)
        // Gue naikin jedanya ke 10 detik biar lebih aman dari spam sinkronisasi Flutter
        $existing = Maintenance::where('comment_id', $request->comment_id)
                                ->where('status_kerja', $request->status_kerja)
                                ->where('created_at', '>=', now()->subSeconds(10)) 
                                ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan perbaikan sedang diproses server, sabar King!'
            ], 422); // Status 422 agar Flutter tahu ini duplikat
        }

        try {
            return DB::transaction(function () use ($request) {
                // 3. Simpan Data Perbaikan
                $maintenance = new Maintenance();
                $maintenance->comment_id   = $request->comment_id;
                $maintenance->status_kerja = $request->status_kerja;
                $maintenance->content      = $request->input('content'); 
                
                // Pastikan user_id terisi (Mekanik yang login)
                $maintenance->user_id      = auth()->id() ?? 1;

                if ($request->hasFile('photo_maintenance')) {
                    // Simpan di folder uploads/maintenance
                    $maintenance->photo_maintenance = $request->file('photo_maintenance')->store('uploads/maintenance', 'public');
                }

                if ($request->hasFile('voice_note')) {
                    $maintenance->voice_note = $request->file('voice_note')->store('uploads/voice_notes', 'public');
                }

                $maintenance->save(); 

                // 4. UPDATE STATUS INDUK (Penting untuk perpindahan Tab di Flutter)
                $comment = Comment::find($request->comment_id);
                if ($comment) {
                    $comment->status = $request->status_kerja; 
                    $comment->save(); 
                }

                // 5. Kirim Email (Try-catch agar tidak menggagalkan transaksi utama)
                try {
                    $comment->load('user', 'alat');
                    if ($comment->user && $comment->user->email) {
                        Mail::to($comment->user->email)->send(new RepairSelesaiMail($maintenance, $comment));
                    }
                } catch (\Exception $e) {
                    Log::error("Gagal kirim email: " . $e->getMessage());
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Laporan Berhasil Update!',
                    'data'    => $maintenance->load('user')
                ], 201);
            });
        } catch (\Exception $e) {
            Log::error("Error Simpan Maintenance: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }
}