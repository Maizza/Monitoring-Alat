@extends('layouts.app')

@section('title', 'Edit Alat - Monitoring Alat')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('alat.index') }}" class="p-3 bg-white border border-slate-200 rounded-2xl hover:bg-slate-50 transition-all">
            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Edit Perangkat</h1>
            <p class="text-slate-500 italic text-sm">Ubah informasi alat: {{ $alat->nama_alat }}</p>
        </div>
    </div>

    <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50">
        <form action="{{ route('alat.update', $alat->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Nama Perangkat</label>
                    <input type="text" name="nama_alat" value="{{ $alat->nama_alat }}" class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">Serial Number (SN)</label>
                    <input type="text" name="serial_number" value="{{ $alat->serial_number }}" class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all" required>
                </div>
                {{-- Dropdown Status Perangkat dihapus agar sinkron dengan index --}}
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full md:w-auto px-12 py-4 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                    Update Perangkat
                </button>
            </div>
        </form>
    </div>
</div>
@endsection