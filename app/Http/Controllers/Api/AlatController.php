<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    /**
     * Tampilan Index untuk Web (Laravel Blade)
     * Solusi Error "Undefined variable $alats"
     */
    public function indexWeb()
    {
        // Ambil semua data agar variabel $alats tidak kosong saat dipanggil di Blade
        $alats = Alat::all(); 
        return view('alat.index', compact('alats'));
    }

    /**
     * Simpan data Alat baru (Create di halaman Index)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_alat' => 'required|string|max:255',
        ]);

        Alat::create($request->all());

        // Setelah simpan, balik ke index agar data terbaru langsung muncul di tabel
        return redirect()->back()->with('success', 'Alat berhasil ditambahkan!');
    }

    /**
     * Endpoint API untuk Flutter (JSON)
     */
    public function index()
    {
        // Mengambil semua data alat untuk pilihan dropdown/list di Flutter
        $alats = Alat::all();
        return response()->json([
            'status' => 'success',
            'data' => $alats
        ]);
    }
}