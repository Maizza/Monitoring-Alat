@extends('layouts.app')

@section('title', 'Manajemen Alat - Monitoring Alat')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Manajemen Alat</h1>
            <p class="text-slate-500 italic text-sm">Kelola inventaris perangkat Monitoring Alat</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-2xl font-bold text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div id="form-create" class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50">
        <h2 class="text-lg font-bold mb-6 text-slate-800 flex items-center">
            <span class="w-1.5 h-6 bg-blue-600 rounded-full mr-3"></span> Registrasi Alat Baru
        </h2>
        <form action="{{ route('alat.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Perangkat</label>
                <input type="text" name="nama_alat" placeholder="Nama alat..." class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all" required>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Serial Number (SN)</label>
                <input type="text" name="serial_number" placeholder="Nomor seri..." class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all" required>
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="px-10 py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-blue-600 transition-all shadow-lg shadow-slate-200">
                    Simpan Perangkat
                </button>
            </div>
        </form>
    </div>

    <div class="flex justify-start">
        <form action="{{ route('alat.index') }}" method="GET" class="relative w-full max-md:max-w-md">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari alat atau SN..." 
                class="w-full pl-12 pr-4 py-3.5 rounded-2xl bg-white border border-slate-200 outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm shadow-sm">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50/50 border-b border-slate-100">
                <tr>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Alat</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Serial Number</th>
                    {{-- Kolom Status telah dihapus --}}
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($alats as $alat)
                <tr class="hover:bg-slate-50/30 transition-colors">
                    <td class="px-8 py-5 font-bold text-slate-700">{{ $alat->nama_alat }}</td>
                    <td class="px-8 py-5 font-mono text-xs text-blue-500 font-bold uppercase">{{ $alat->serial_number }}</td>
                    {{-- Data Status telah dihapus agar tabel lebih bersih --}}
                    <td class="px-8 py-5 text-right">
                        <div class="flex justify-end items-center gap-2">
                            <a href="{{ route('alat.edit', $alat->id) }}" class="p-2 text-slate-400 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                            
                            <form action="{{ route('alat.destroy', $alat->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    {{-- Colspan disesuaikan menjadi 3 karena jumlah kolom berkurang --}}
                    <td colspan="3" class="px-8 py-12 text-center text-slate-400 text-sm italic">
                        Data tidak ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection