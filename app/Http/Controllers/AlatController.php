<?php

namespace App\Http\Controllers;
use App\Models\Alat;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    // Menghapus data alat
    public function destroy(Alat $alat)
    {
        $alat->delete();
        return back()->with('success', 'Alat berhasil dihapus!');
    }

    // Opsional: Untuk pencarian di index
    public function index(Request $request)
    {
        $query = Alat::latest();

        if ($request->has('search')) {
            $query->where('nama_alat', 'like', '%' . $request->search . '%')
                ->orWhere('serial_number', 'like', '%' . $request->search . '%');
        }

        $alats = $query->get();
        return view('alat.index', compact('alats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_alat' => 'required',
            'serial_number' => 'required|unique:alats',
        ]);

        Alat::create($data);
        return redirect()->route('alat.index')->with('success', 'Alat berhasil ditambahkan!');
    }

    public function edit(Alat $alat)
    {
        return view('alat.edit', compact('alat'));
    }

    public function update(Request $request, $id)
    {
        // 1. Validasi: Hapus 'status' dari validasi karena sudah tidak diinput manual
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:alats,serial_number,' . $id,
        ]);

        $alat = \App\Models\Alat::findOrFail($id);

        // 2. Update field yang ada saja
        $alat->update([
            'nama_alat' => $request->nama_alat,
            'serial_number' => $request->serial_number,
            // Status JANGAN dimasukkan di sini biar gak keganti jadi kosong
        ]);

        return redirect()->route('alat.index')->with('success', 'Data perangkat berhasil diperbarui!');
    }
}