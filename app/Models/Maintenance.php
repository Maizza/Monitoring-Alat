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
        'content',            // WAJIB ADA
        'photo_maintenance',
        'voice_note'          // WAJIB ADA
    ];
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}