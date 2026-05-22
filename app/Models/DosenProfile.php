<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenProfile extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk mengizinkan input data
    protected $fillable = [
        'user_id',
        'nidn',
    ];

    // Relasi kembali ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}