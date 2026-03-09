<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id',
        'user_id',       // <--- INI WAJIB ADA BIAR GAK GAGAL ONLINE!
        'status_kerja',
        'content',
        'photo_maintenance',
        'voice_note'
    ];

    // Relasi ke User (Mekanik) biar namanya bisa kebaca di Flutter
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}