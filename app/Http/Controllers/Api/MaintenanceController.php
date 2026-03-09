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
    /**
     * Menyimpan data perbaikan dari Mekanik
     * Dan memindahkan laporan dari tab Repair ke Preventive
     */
    public function store(Request $request): JsonResponse
    {
        // 1. VALIDASI KETAT TAPI FLEKSIBEL
        // Mimes audio ditambahin m4a & mp4 karena Flutter 'record' pakainya itu
        $request->validate([
            'comment_id'        => 'required|exists:comments,id',
            'status_kerja'      => 'required|in:PROSES,DONE', // Hanya terima 2 status ini
            'content'           => 'required|string', 
            'photo_maintenance' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'voice_note'        => 'nullable|file|mimes:mp3,wav,m4a,mp4,aac|max:15000', 
        ]);

        // 2. PROTEKSI DOUBLE TAP (ANTAL-ANTIL)
        // Mencegah data masuk 2x kalau mekanik pencet tombol simpan kecepatan
        $existing = Maintenance::where('comment_id', $request->comment_id)
                                ->where('status_kerja', $request->status_kerja)
                                ->where('created_at', '>=', now()->subSeconds(10)) 
                                ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan serupa baru saja masuk, tunggu sebentar.'
            ], 422);
        }

        // 3. TRANSACTION: SIMPAN DATA & UPDATE STATUS INDUK
        return DB::transaction(function () use ($request) {
            $maintenance = new Maintenance();
            $maintenance->comment_id   = $request->comment_id;
            $maintenance->status_kerja = $request->status_kerja;
            $maintenance->content      = $request->input('content'); 
            
            // Catat ID Mekanik yang sedang login
            $maintenance->user_id      = auth()->id(); 

            // Handle Upload Foto Bukti
            if ($request->hasFile('photo_maintenance')) {
                $path = $request->file('photo_maintenance')->store('uploads/maintenance', 'public');
                $maintenance->photo_maintenance = $path;
            }

            // Handle Upload Voice Note
            if ($request->hasFile('voice_note')) {
                $voicePath = $request->file('voice_note')->store('uploads/voice_notes', 'public');
                $maintenance->voice_note = $voicePath;
            }

            $maintenance->save(); 

            // 4. LOGIKA ESTAFET TAB: Update Status di tabel Comments
            // Mencari laporan utama (induk) untuk diubah statusnya
            $comment = Comment::with('user', 'alat')->find($request->comment_id);
            
            if ($comment) {
                // Update status induk jadi PROSES atau DONE
                // Ini yang bikin laporan hilang di tab Repair dan muncul di Preventive
                $comment->update(['status' => $request->status_kerja]);

                // 5. NOTIFIKASI EMAIL KE OPERATOR (Optional)
                try {
                    $emailUser = $comment->user->email;
                    if ($emailUser) {
                        Mail::to($emailUser)->send(new RepairSelesaiMail($maintenance, $comment));
                    }
                } catch (\Exception $e) {
                    \Log::error("Gagal kirim email perbaikan: " . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data perbaikan berhasil disimpan, status alat diupdate!',
                'data'    => $maintenance->load('user')
            ], 201);
        });
    }
}