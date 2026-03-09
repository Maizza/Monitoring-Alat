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
        // 1. Validasi yang lebih 'longgar' biar gak gampang ditolak server
        $request->validate([
            'comment_id'        => 'required|exists:comments,id',
            'status_kerja'      => 'required', 
            'content'           => 'required|string', 
            'photo_maintenance' => 'nullable|image|max:10240', // Naikin ke 10MB buat jaga-jaga
            'voice_note'        => 'nullable|file|max:20480', // Naikin ke 20MB
        ]);

        // 2. Cek Duplikat (Proteksi Double Click)
        $existing = Maintenance::where('comment_id', $request->comment_id)
                                ->where('status_kerja', $request->status_kerja)
                                ->where('created_at', '>=', now()->subSeconds(5)) 
                                ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan sudah masuk sebelumnya.'
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request) {
                // 3. Simpan Data Perbaikan
                $maintenance = new Maintenance();
                $maintenance->comment_id   = $request->comment_id;
                $maintenance->status_kerja = $request->status_kerja;
                $maintenance->content      = $request->input('content'); 
                
                // Pastikan user_id terisi (Mekanik yang login)
                $maintenance->user_id      = auth()->id() ?? 1; // Fallback ke ID 1 jika auth bermasalah

                if ($request->hasFile('photo_maintenance')) {
                    $maintenance->photo_maintenance = $request->file('photo_maintenance')->store('uploads/maintenance', 'public');
                }

                if ($request->hasFile('voice_note')) {
                    $maintenance->voice_note = $request->file('voice_note')->store('uploads/voice_notes', 'public');
                }

                $maintenance->save(); 

                // 4. KUNCI PERPINDAHAN TAB: Paksa Update Status Induk
                $comment = Comment::find($request->comment_id);
                if ($comment) {
                    // Kita samakan statusnya dengan apa yang dikirim mekanik (PROSES/DONE)
                    $comment->status = $request->status_kerja; 
                    $comment->save(); 
                }

                // 5. Kirim Email (Jangan biarkan email bikin error simpan)
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