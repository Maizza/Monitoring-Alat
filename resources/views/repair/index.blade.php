@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white/60 backdrop-blur-md p-8 rounded-[2rem] border border-white shadow-sm">
        <div>
            <h3 class="text-2xl font-bold text-slate-800 tracking-tight">History Laporan & Repair</h3>
            <p class="text-sm text-slate-400 mt-1">Pantau seluruh aktivitas Operator dan Mekanik</p>
        </div>
        <a href="{{ route('repair.export') }}" class="group flex items-center gap-2 px-6 py-3 bg-slate-900 hover:bg-emerald-600 text-white text-xs font-bold rounded-2xl transition-all duration-300 shadow-xl shadow-slate-200 hover:shadow-emerald-100">
            <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            EXPORT DATA EXCEL
        </a>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.2em] font-bold text-slate-400">Detail Alat & Ops</th>
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.2em] font-bold text-slate-400">Keluhan</th>
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.2em] font-bold text-slate-400">Progres Mekanik</th>
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.2em] font-bold text-slate-400 text-center">Status Akhir</th>
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.2em] font-bold text-slate-400 text-center">Aksi</th>
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.2em] font-bold text-slate-400 text-right">Waktu Lapor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($repairs as $item)
                    <tr class="group hover:bg-slate-50/80 transition-all duration-200">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-xs shadow-sm">
                                    {{ strtoupper(substr($item->alat->nama_alat ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-700 text-sm tracking-tight">{{ $item->alat->nama_alat ?? 'Alat Dihapus' }}</p>
                                    <p class="text-[10px] text-blue-500 font-semibold italic uppercase tracking-tighter">By: {{ $item->user->name ?? 'User' }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-8 py-6">
                            <div class="max-w-[180px]">
                                <p class="text-xs text-slate-600 italic leading-relaxed line-clamp-2 mb-2">"{{ $item->content }}"</p>
                                <div class="flex gap-2">
                                    @if($item->photo)
                                        <a href="{{ asset('storage/' . $item->photo) }}" target="_blank" class="p-1.5 bg-slate-100 hover:bg-blue-100 text-slate-500 hover:text-blue-600 rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </a>
                                    @endif
                                    @if($item->voice_note)
                                        <a href="{{ asset('storage/' . $item->voice_note) }}" target="_blank" class="p-1.5 bg-slate-100 hover:bg-orange-100 text-slate-500 hover:text-orange-600 rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="px-8 py-6">
                            @if($item->maintenances->isNotEmpty())
                                @php $latest = $item->maintenances->last(); @endphp
                                <div class="flex flex-col gap-1">
                                    <span class="text-[10px] font-black {{ strtoupper($latest->status_kerja) == 'DONE' ? 'text-emerald-600' : 'text-blue-600' }} uppercase tracking-widest">{{ $latest->status_kerja }}</span>
                                    <p class="text-[11px] text-slate-500 line-clamp-1 italic">"{{ $latest->content ?? 'No desc' }}"</p>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                    <span class="text-[10px] text-slate-400 font-medium italic uppercase">Waiting...</span>
                                </div>
                            @endif
                        </td>

                        <td class="px-8 py-6 text-center">
                            @php
                                $currentStatus = $item->maintenances->isNotEmpty() 
                                    ? $item->maintenances->last()->status_kerja 
                                    : ($item->status ?? 'pending');

                                $color = strtoupper($currentStatus) == 'DONE' 
                                    ? 'bg-emerald-50 text-emerald-600 border-emerald-100' 
                                    : ($currentStatus == 'pending' ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-blue-50 text-blue-600 border-blue-100');
                            @endphp
                            <span class="px-4 py-1.5 border {{ $color }} rounded-xl text-[10px] font-black uppercase tracking-widest">
                                {{ $currentStatus }}
                            </span>
                        </td>

                        <td class="px-8 py-6">
                            <div class="flex justify-center">
                                <form action="{{ route('repair.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus laporan ini beserta semua histori perbaikannya?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 bg-slate-50 hover:bg-red-50 text-slate-400 hover:text-red-500 rounded-xl border border-transparent hover:border-red-100 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>

                        <td class="px-8 py-6 text-right whitespace-nowrap">
                            <p class="text-xs font-bold text-slate-700">{{ $item->created_at->translatedFormat('d M Y') }}</p>
                            <p class="text-[10px] text-slate-400 font-bold tracking-widest mt-0.5">{{ $item->created_at->format('H:i') }} WIB</p>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection