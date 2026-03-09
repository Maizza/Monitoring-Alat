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
        // 1. Validasi Input
        $request->validate([
            'comment_id'        => 'required|exists:comments,id',
            'status_kerja'      => 'required|in:PROSES,DONE', 
            'content'           => 'required|string', 
            'photo_maintenance' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'voice_note'        => 'nullable|file|mimes:mp3,wav,m4a,mp4,aac|max:15000', 
        ]);

        // 2. Cek Duplikat (10 detik terakhir)
        $existing = Maintenance::where('comment_id', $request->comment_id)
                                ->where('status_kerja', $request->status_kerja)
                                ->where('created_at', '>=', now()->subSeconds(10)) 
                                ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan serupa baru saja masuk.'
            ], 422);
        }

        return DB::transaction(function () use ($request) {
            // 3. Simpan Data Perbaikan
            $maintenance = new Maintenance();
            $maintenance->comment_id   = $request->comment_id;
            $maintenance->status_kerja = $request->status_kerja;
            $maintenance->content      = $request->input('content'); 
            $maintenance->user_id      = auth()->id(); 

            if ($request->hasFile('photo_maintenance')) {
                $maintenance->photo_maintenance = $request->file('photo_maintenance')->store('uploads/maintenance', 'public');
            }

            if ($request->hasFile('voice_note')) {
                $maintenance->voice_note = $request->file('voice_note')->store('uploads/voice_notes', 'public');
            }

            $maintenance->save(); 

            // 4. FIX: LOGIKA UPDATE STATUS INDUK (CARA MANUAL BIAR PASTI MASUK)
            $comment = Comment::find($request->comment_id);
            
            if ($comment) {
                // Jangan pake update(['status' => ...]) kalau belum setting fillable di Model
                // Pakai cara manual ini biar PASTI kesimpan ke database
                $comment->status = $request->status_kerja; 
                $comment->save(); // Simpan perubahan status induk

                // 5. Notifikasi Email
                try {
                    $comment->load('user', 'alat');
                    if ($comment->user && $comment->user->email) {
                        Mail::to($comment->user->email)->send(new RepairSelesaiMail($maintenance, $comment));
                    }
                } catch (\Exception $e) {
                    \Log::error("Email Error: " . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Berhasil! Laporan pindah ke tab Preventive.',
                'data'    => $maintenance->load('user')
            ], 201);
        });
    }
}