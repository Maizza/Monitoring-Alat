<?php

namespace App\Exports;

use App\Models\Maintenance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MaintenanceExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * Ambil data dari database beserta relasinya
    */
    public function collection()
    {
        // Pake Eager Loading ('comment.alat') biar export-nya kenceng
        return Maintenance::with('comment.alat')->latest()->get();
    }

    /**
    * Atur Judul Kolom di Excel
    */
    public function headings(): array
    {
        return [
            'ID Perbaikan',
            'Nama Alat',
            'Status Kerja',
            'Deskripsi Perbaikan', // Kolom content yang baru
            'File Voice Note',      // Kolom voice_note yang baru
            'Tanggal Perbaikan',
        ];
    }

    /**
    * Map data ke kolom yang sesuai
    */
    public function map($maintenance): array
    {
        return [
            $maintenance->id,
            $maintenance->comment->alat->nama_alat ?? 'Alat Tidak Ditemukan',
            $maintenance->status_kerja,
            $maintenance->content ?? 'Tidak ada deskripsi', // Deskripsi Mekanik
            $maintenance->voice_note ?? 'Tidak ada rekaman', // Nama file voice
            $maintenance->created_at->format('d-m-Y H:i'),
        ];
    }
}