@extends('layouts.app')

@section('title', 'Manajemen User - PAO System')

@section('content')
    <div class="space-y-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Manajemen User</h1>
                <p class="text-slate-500 italic text-sm">Kelola akun operasional untuk Supervisor, Mekanik, dan User </p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-2xl font-bold text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-red-50 border border-red-200 text-red-600 rounded-2xl font-bold text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50">
            <h2 class="text-lg font-bold mb-6 text-slate-800 flex items-center">
                <span class="w-1.5 h-6 bg-blue-600 rounded-full mr-3"></span> Registrasi Akun Baru
            </h2>
            <form action="{{ route('user.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama
                        Lengkap</label>
                    <input type="text" name="name" placeholder="Nama user..."
                        class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-200 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all"
                        required>
                </div>
                <div>
                    <label
                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Email</label>
                    <input type="email" name="email" placeholder="user@example.com"
                        class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-200 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all"
                        required>
                </div>
                <div>
                    <label
                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Password</label>
                    <input type="password" name="password" placeholder="Min 6 karakter..."
                        class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-200 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all"
                        required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Akses
                        Role</label>
                    <select name="role"
                        class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-200 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all font-bold text-slate-700 uppercase">
                        <option value="user">USER (Operator)</option>
                        <option value="mekanik">MEKANIK</option>
                        <option value="supervisor">SUPERVISOR</option>
                    </select>
                </div>
                <div class="md:col-span-2 text-right">
                    <button type="submit"
                        class="px-10 py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-blue-600 transition-all shadow-lg shadow-slate-200">
                        Simpan & Daftarkan
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead
                    class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-5">Nama</th>
                        <th class="px-8 py-5">Email</th>
                        <th class="px-8 py-5 text-center">Role</th>
                        <th class="px-8 py-5 text-center">Password</th> {{-- Kolom Baru --}}
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50/30 transition-colors text-sm">
                            <td class="px-8 py-5 font-bold text-slate-700">{{ $user->name }}</td>
                            <td class="px-8 py-5 text-slate-500 italic">{{ $user->email }}</td>
                            <td class="px-8 py-5 text-center">
                                <span
                                    class="px-4 py-1.5 {{ $user->role == 'supervisor' ? 'bg-purple-100 text-purple-600' : ($user->role == 'mekanik' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600') }} rounded-full text-[9px] font-black uppercase tracking-tighter">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-center font-mono text-xs text-slate-400">
                                ******** {{-- Karena di-hash, kita tampilin sensor aja --}}
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end gap-2">
                                    {{-- Tombol Reset/Edit Password --}}
                                    <a href="{{ route('user.edit', $user->id) }}"
                                        class="text-slate-400 hover:text-blue-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                            </path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus user ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection