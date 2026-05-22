<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Tambahkan 'role' ke dalam fillable agar bisa diisi saat registrasi/pembuatan akun
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke Profil Mahasiswa
    public function mahasiswaProfile()
    {
        return $this->hasOne(MahasiswaProfile::class);
    }

    // Relasi ke Profil Dosen
    public function dosenProfile()
    {
        return $this->hasOne(DosenProfile::class);
    }
}