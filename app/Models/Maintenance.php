<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id',
        'status_kerja',
        'photo_maintenance', // WAJIB TAMBAHIN INI
    ];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}