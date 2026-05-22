<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaProfile extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk mengizinkan input data mahasiswa
    protected $fillable = [
        'user_id',
        'nim',
    ];

    // Relasi kembali ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}