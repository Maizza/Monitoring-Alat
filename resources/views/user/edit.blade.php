@extends('layouts.app')

@section('title', 'Edit User - Monitoring Alat')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('user.index') }}" class="p-3 bg-white border border-slate-200 rounded-2xl text-slate-400 hover:text-blue-600 transition-all shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Edit Akun</h1>
            <p class="text-slate-500 italic text-sm">Update informasi atau reset password untuk {{ $user->name }}</p>
        </div>
    </div>

    <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50">
        <form action="{{ route('user.update', $user->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all font-semibold text-slate-700" required>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Alamat Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all font-semibold text-slate-700" required>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Akses Role</label>
                    <select name="role" class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all font-bold text-slate-700 uppercase">
                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>USER (Operator)</option>
                        <option value="mekanik" {{ $user->role == 'mekanik' ? 'selected' : '' }}>MEKANIK</option>
                        <option value="supervisor" {{ $user->role == 'supervisor' ? 'selected' : '' }}>SUPERVISOR</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-blue-600 uppercase tracking-widest ml-1">Password Baru (Opsional)</label>
                    <input type="password" name="password" placeholder="Isi jika ingin ganti password..." class="w-full px-6 py-4 rounded-2xl bg-blue-50/30 border border-blue-100 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all font-semibold">
                    <p class="text-[9px] text-slate-400 italic mt-1 ml-1">*Kosongkan jika tidak ingin mengubah password.</p>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-50 flex justify-end gap-4">
                <a href="{{ route('user.index') }}" class="px-8 py-4 text-slate-400 font-bold text-sm hover:text-slate-600 transition-all">Batal</a>
                <button type="submit" class="px-10 py-4 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                    Update Data User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection