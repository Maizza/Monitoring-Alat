<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Comment;
use App\Mail\RepairSelesaiMail; // Import Class Mail lu
use Illuminate\Support\Facades\Mail; // Import Facade Mail
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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

        $maintenance = new Maintenance();
        $maintenance->comment_id   = $request->comment_id;
        $maintenance->status_kerja = $request->status_kerja;
        $maintenance->content      = $request->input('content'); 

        // 2. Proses Simpan Foto Perbaikan
        if ($request->hasFile('photo_maintenance')) {
            $path = $request->file('photo_maintenance')->store('uploads/maintenance', 'public');
            $maintenance->photo_maintenance = $path;
        }

        // --- PROSES SIMPAN VOICE NOTE ---
        if ($request->hasFile('voice_note')) {
            $voicePath = $request->file('voice_note')->store('uploads/voice_notes', 'public');
            $maintenance->voice_note = $voicePath;
        }

        $maintenance->save(); 

        // 3. Update Status Laporan Utama & Kirim Email ke User
        $comment = Comment::with('user', 'alat')->find($request->comment_id);
        
        if ($comment) {
            $newStatus = ($request->status_kerja == 'DONE') ? 'DONE' : 'Preventive';
            $comment->update(['status' => $newStatus]);

            // --- FITUR NOTIFIKASI BALIK KE USER ---
            try {
                $emailUser = $comment->user->email;
                
                if ($emailUser) {
                    // Pastikan lu udah buat: php artisan make:mail RepairSelesaiMail
                    Mail::to($emailUser)->send(new RepairSelesaiMail($maintenance, $comment));
                }
            } catch (\Exception $e) {
                // Catat error di log biar gak ngerusak respon API
                \Log::error("Gagal kirim email ke user: " . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Data perbaikan berhasil disimpan & email terkirim!',
            'data'    => $maintenance
        ], 201);
    }
}