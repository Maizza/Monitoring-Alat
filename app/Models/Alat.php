<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     * Secara default Laravel akan menganggap tabelnya bernama 'alats'.
     */
    protected $table = 'alats';

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignable).
     * Kita hapus 'kategori' sesuai permintaan lu tadi.
     */
    protected $fillable = [
        'nama_alat',
        'serial_number',
        'status',
    ];

    /**
     * Default nilai untuk atribut tertentu.
     */
    protected $attributes = [
        'status' => 'Tersedia',
    ];

    /**
     * Casting tipe data agar lebih konsisten saat dipanggil.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}