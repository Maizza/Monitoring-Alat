@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white/60 backdrop-blur-md p-8 rounded-[2rem] border border-white shadow-sm">
        <div>
            <h3 class="text-2xl font-bold text-slate-800 tracking-tight">Data Maintenance Mekanik</h3>
            <p class="text-sm text-slate-400 mt-1">Rekapitulasi histori perbaikan yang telah diselesaikan (Status: DONE)</p>
        </div>
        <a href="{{ route('report.export') }}" class="group flex items-center gap-2 px-6 py-3 bg-slate-900 hover:bg-blue-600 text-white text-xs font-bold rounded-2xl transition-all duration-300 shadow-xl shadow-slate-200 hover:shadow-blue-100">
            <svg class="w-4 h-4 group-hover:bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            EXPORT DATA EXCEL
        </a>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.2em] font-bold text-slate-400">Info Alat</th>
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.2em] font-bold text-slate-400">Tindakan Perbaikan</th>
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.2em] font-bold text-slate-400 text-center">Status</th>
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.2em] font-bold text-slate-400">Media Bukti</th>
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.2em] font-bold text-slate-400 text-center">Aksi</th>
                        <th class="px-8 py-5 text-[10px] uppercase tracking-[0.2em] font-bold text-slate-400 text-right">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($maintenances as $m)
                    <tr class="group hover:bg-slate-50/80 transition-all duration-200">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-xs shadow-sm">
                                    {{ strtoupper(substr($m->comment->alat->nama_alat ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-700 text-sm tracking-tight">{{ $m->comment->alat->nama_alat ?? 'Alat Dihapus' }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium uppercase tracking-widest">SN: {{ $m->comment->alat->serial_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-8 py-6">
                            <div class="max-w-[200px]">
                                <p class="text-xs text-slate-600 italic leading-relaxed line-clamp-2">"{{ $m->content ?? 'Tidak ada deskripsi' }}"</p>
                            </div>
                        </td>

                        <td class="px-8 py-6">
                            <div class="flex justify-center">
                                <span class="px-4 py-1.5 border bg-emerald-50 text-emerald-600 border-emerald-100 rounded-xl text-[10px] font-black tracking-widest uppercase">
                                    COMPLETED
                                </span>
                            </div>
                        </td>

                        <td class="px-8 py-6">
                            <div class="flex gap-2">
                                @if($m->photo_maintenance)
                                    <a href="{{ asset('storage/' . $m->photo_maintenance) }}" target="_blank" class="p-2 bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-600 rounded-lg transition-colors group/link">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </a>
                                @endif
                                @if($m->voice_note)
                                    <a href="{{ asset('storage/' . $m->voice_note) }}" target="_blank" class="p-2 bg-slate-100 hover:bg-orange-100 text-slate-600 hover:text-orange-600 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                                    </a>
                                @endif
                                @if(!$m->photo_maintenance && !$m->voice_note)
                                    <span class="text-[10px] text-slate-300 font-medium italic">No Media</span>
                                @endif
                            </div>
                        </td>

                        <td class="px-8 py-6">
                            <div class="flex justify-center">
                                <form action="{{ route('maintenance.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus histori perbaikan ini?')">
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
                            <p class="text-xs font-bold text-slate-700">{{ $m->created_at->format('d M Y') }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $m->created_at->format('H:i') }} WIB</p>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($maintenances->isEmpty())
        <div class="py-20 text-center bg-white">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-300 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <p class="text-slate-400 text-sm font-medium">Belum ada data perbaikan yang diselesaikan.</p>
        </div>
        @endif
    </div>
</div>
@endsection