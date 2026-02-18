<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Comment;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi input agar deskripsi & voice bisa masuk
        $request->validate([
            'comment_id'        => 'required|exists:comments,id',
            'status_kerja'      => 'required',
            'content'           => 'nullable|string', // Deskripsi Mekanik
            'photo_maintenance' => 'nullable|image|max:5120',
            'voice_note'        => 'nullable|file|max:15000', // Support format m4a/mp3
        ]);

        $maintenance = new Maintenance();
        $maintenance->comment_id   = $request->comment_id;
        $maintenance->status_kerja = $request->status_kerja;
        
        // --- FIX VS CODE MERAH: Gunakan input() agar IDE tidak protes ---
        $maintenance->content      = $request->input('content'); 

        // 2. Proses Simpan Foto Perbaikan
        if ($request->hasFile('photo_maintenance')) {
            $path = $request->file('photo_maintenance')->store('uploads/maintenance', 'public');
            $maintenance->photo_maintenance = $path;
        }

        // --- PROSES SIMPAN VOICE NOTE ---
        if ($request->hasFile('voice_note')) {
            // Simpan ke folder khusus voice notes di storage/app/public/uploads/voice_notes
            $voicePath = $request->file('voice_note')->store('uploads/voice_notes', 'public');
            $maintenance->voice_note = $voicePath;
        }

        $maintenance->save(); // Simpan ke Database

        // 3. Update Status Laporan Utama (Comment)
        $comment = Comment::find($request->comment_id);
        if ($comment) {
            // Jika mekanik pilih DONE, laporan jadi DONE. Jika PROSES, status jadi Preventive.
            $newStatus = ($request->status_kerja == 'DONE') ? 'DONE' : 'Preventive';
            $comment->update(['status' => $newStatus]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data perbaikan berhasil disimpan!',
            'data'    => $maintenance
        ], 201);
    }
}