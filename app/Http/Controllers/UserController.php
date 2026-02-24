<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\AkunBaruMail; // Panggil Mailable yang baru kita buat
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail; // Pastikan ini ada untuk kirim email

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('id', '!=', auth()->id())->latest();

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->get();

        return view('user.index', compact('users'));
    }

    /**
     * Daftarkan User Baru & Kirim Email Notifikasi
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:supervisor,user,mekanik', 
        ]);

        $data['password'] = Hash::make($request->password);
        
        // Simpan ke database
        $user = User::create($data);

        // --- TRIGGER EMAIL NOTIFIKASI (START) ---
        try {
            // Kirim notifikasi tanpa password untuk keamanan
            Mail::to($user->email)->send(new AkunBaruMail($user));
        } catch (\Exception $e) {
            // Log jika email gagal agar proses pendaftaran tetap lanjut
            \Log::error("Gagal kirim email akun baru: " . $e->getMessage());
        }
        // --- TRIGGER EMAIL NOTIFIKASI (END) ---

        return back()->with('success', 'User ' . $request->name . ' berhasil didaftarkan dan notifikasi email terkirim!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }

    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:supervisor,mekanik,user',
            'password' => 'nullable|min:6'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'Akun ' . $user->name . ' berhasil diupdate!');
    }
}