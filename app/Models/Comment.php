<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'alat_id',
        'content',
        'voice_note',
        'photo',
        'video',
        'unique_code',
        'status', // WAJIB TAMBAHIN INI
    ];

    // Relasi ke User (Pelapor)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }

    /**
     * WAJIB ADA: Relasi ke tabel perbaikan
     */
    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }
}