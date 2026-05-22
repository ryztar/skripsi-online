<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkripsiSubmission extends Model
{
    use HasFactory;

    protected $table = 'skripsi_submissions';

    // Izinkan kolom-kolom ini untuk diisi secara massal oleh controller
    protected $fillable = [
        'user_id',
        'judul',
        'abstrak',
        'latar_belakang',
        'referensi',
        'dokumen_pendukung',
        'status',
        'komentar_dosen',
        'reviewed_by',
        'reviewed_at'
    ];

    // Relasi kembali ke User / Mahasiswa
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}