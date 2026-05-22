<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('skripsi_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Mahasiswa yang mengajukan
            $table->string('judul');
            $table->text('abstrak');
            $table->text('latar_belakang');
            $table->text('referensi')->nullable();
            $table->string('dokumen_pendukung')->nullable(); // Tempat menyimpan nama file PDF
            // Status: draft, submitted, review, revisi, approved (Sesuai Alur Pengajuan di UI Anda)
            $table->enum('status', ['draft', 'submitted', 'review', 'revisi', 'approved'])->default('draft');
            $table->text('komentar_dosen')->nullable(); // Catatan revisi/penolakan dari dosen
            $table->foreignId('reviewed_by')->nullable()->constrained('users'); // Dosen peninjau
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skripsi_submissions');
    }
};
