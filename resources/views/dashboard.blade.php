@extends('layouts.app')

@section('title', 'Dashboard - Monitoring Alat')

@section('content')
<div class="space-y-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Dashboard Overview</h1>
        <p class="text-slate-500 italic text-sm">Selamat datang kembali, <span class="font-bold text-blue-600">{{ auth()->user()->name }}</span>!</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 relative overflow-hidden group hover:scale-[1.02] transition-all">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full group-hover:bg-blue-100 transition-colors"></div>
            <div class="relative">
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white mb-4 shadow-lg shadow-blue-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Alat</p>
                <h3 class="text-4xl font-black text-slate-900 mt-2">{{ $totalAlat }}</h3>
                <p class="text-[10px] text-slate-400 mt-2 italic">* Terdata di inventaris</p>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 relative overflow-hidden group hover:scale-[1.02] transition-all">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-50 rounded-full group-hover:bg-orange-100 transition-colors"></div>
            <div class="relative">
                <div class="w-12 h-12 bg-orange-500 rounded-2xl flex items-center justify-center text-white mb-4 shadow-lg shadow-orange-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Alat Repair</p>
                <h3 class="text-4xl font-black text-slate-900 mt-2">{{ $totalRepair }}</h3>
                <p class="text-[10px] text-orange-500 mt-2 italic font-bold uppercase tracking-tighter animate-pulse">Butuh Tindakan</p>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 relative overflow-hidden group hover:scale-[1.02] transition-all">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full group-hover:bg-emerald-100 transition-colors"></div>
            <div class="relative">
                <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white mb-4 shadow-lg shadow-emerald-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Maintenance</p>
                <h3 class="text-4xl font-black text-slate-900 mt-2">{{ $totalMaintenance }}</h3>
                <p class="text-[10px] text-emerald-500 mt-2 italic font-bold tracking-tighter uppercase text-xs">Laporan Selesai</p>
            </div>
        </div>

    </div>
</div>
@endsection