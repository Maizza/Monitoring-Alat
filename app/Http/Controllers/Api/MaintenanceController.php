<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Comment;
use App\Models\Alat;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'comment_id' => 'required|exists:comments,id',
            'status_kerja' => 'required',
            'photo_maintenance' => 'nullable|image|max:5120',
        ]);

        // Kita bikin object baru
        $maintenance = new \App\Models\Maintenance();
        $maintenance->comment_id = $request->comment_id;
        $maintenance->status_kerja = $request->status_kerja;

        // Bagian paling krusial: Proses simpan foto
        if ($request->hasFile('photo_maintenance')) {
            // Simpan file ke folder uploads/maintenance di disk public
            $path = $request->file('photo_maintenance')->store('uploads/maintenance', 'public');

            // Kita paksa isi field-nya di sini
            $maintenance->photo_maintenance = $path;
        }

        $maintenance->save(); // Simpan ke database

        // Update status laporan utama (Comment)
        $comment = \App\Models\Comment::find($request->comment_id);
        if ($comment) {
            $newStatus = ($request->status_kerja == 'DONE') ? 'DONE' : 'Preventive';
            $comment->update(['status' => $newStatus]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan!',
            'debug_data' => $maintenance // Buat cek di Postman/Insomnia
        ], 201);
    }
}