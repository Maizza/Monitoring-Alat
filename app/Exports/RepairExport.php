<?php

namespace App\Exports;

use App\Models\Comment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RepairExport implements FromCollection, WithMapping, WithHeadings
{
    public function collection()
    {
        // Ambil data beserta relasinya agar tidak lambat (Eager Loading)
        return Comment::with(['alat', 'user'])->latest()->get();
    }

    // Mengatur judul kolom di Excel
    public function headings(): array
    {
        return [
            'ID Laporan',
            'Nama Alat',
            'Nama Operator',
            'Deskripsi Keluhan',
            'Status',
            'Tanggal Lapor',
        ];
    }

    // Memetakan data mana saja yang mau ditampilin
    public function map($comment): array
    {
        return [
            $comment->id,
            $comment->alat->nama_alat ?? 'Alat Dihapus', // Ambil nama alat
            $comment->user->name ?? 'User Dihapus',     // Ambil nama operator
            $comment->content,
            $comment->status,
            $comment->created_at->format('d-m-Y H:i'),
        ];
    }
}