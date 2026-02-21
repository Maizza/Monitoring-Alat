<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    public function index(Request $request)
    {
        // Pake query builder biar lebih clean
        $query = Alat::latest();

        if ($request->filled('search')) { // filled() lebih aman drpada has() buat input kosong
            $query->where(function($q) use ($request) {
                $q->where('nama_alat', 'like', '%' . $request->search . '%')
                  ->orWhere('serial_number', 'like', '%' . $request->search . '%');
            });
        }

        $alats = $query->get();
        return view('alat.index', compact('alats'));
    }

    public function store(Request $request)
    {
        // Tambahin validasi tipe data biar makin aman
        $data = $request->validate([
            'nama_alat' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:alats,serial_number',
        ]);

        Alat::create($data);
        return redirect()->route('alat.index')->with('success', 'Alat berhasil ditambahkan!');
    }

    public function edit(Alat $alat)
    {
        return view('alat.edit', compact('alat'));
    }

    public function update(Request $request, Alat $alat) // Pake Type Hinting biar gak perlu findOrFail lagi
    {
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:alats,serial_number,' . $alat->id,
        ]);

        $alat->update($request->only(['nama_alat', 'serial_number']));

        return redirect()->route('alat.index')->with('success', 'Data perangkat berhasil diperbarui!');
    }

    public function destroy(Alat $alat)
    {
        $alat->delete();
        return back()->with('success', 'Alat berhasil dihapus!');
    }
}