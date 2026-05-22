<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use Illuminate\Support\Facades\Route;

// Rute Tamu (GUEST) - Hanya bisa diakses jika belum login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rute Keluar (LOGOUT)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==================== RUTE TERPROTEKSI BERDASARKAN ROLE ====================

Route::middleware(['auth'])->group(function () {
    // Jalur rute menuju halaman profil universal
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
});

// 1. Rute Khusus Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'adminDashboard'])->name('dashboard');
    Route::get('/dosen', [AdminController::class, 'adminDosen'])->name('dosen');
    Route::post('/dosen/store', [AdminController::class, 'adminDosenStore'])->name('dosen.store');
    
    // Hidupkan Rute Data Mahasiswa & Pengajuan Masuk Admin
    Route::get('/mahasiswa', [AdminController::class, 'adminMahasiswa'])->name('mahasiswa');
    Route::post('/mahasiswa/store', [AdminController::class, 'adminMahasiswaStore'])->name('mahasiswa.store');
    Route::get('/received', [AdminController::class, 'adminReceived'])->name('received');
    // Rute Aksi untuk Dosen
    Route::post('/dosen/update/{id}', [AdminController::class, 'adminDosenUpdate'])->name('dosen.update');
    Route::post('/dosen/delete/{id}', [AdminController::class, 'adminDosenDelete'])->name('dosen.delete');

    // Rute Aksi untuk Mahasiswa
    Route::post('/mahasiswa/update/{id}', [AdminController::class, 'adminMahasiswaUpdate'])->name('mahasiswa.update');
    Route::post('/mahasiswa/delete/{id}', [AdminController::class, 'adminMahasiswaDelete'])->name('mahasiswa.delete');
});

// 2. Rute Khusus Dosen
Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    // Arahkan ke halaman dashboard statis baru
    Route::get('/dashboard', [DosenController::class, 'dosenDashboard'])->name('dashboard');
    
    Route::get('/received', [DosenController::class, 'dosenReceived'])->name('received');
    Route::post('/review/{id}/store', [DosenController::class, 'dosenReviewStore'])->name('review.store');

    Route::get('/riwayat', [DosenController::class, 'dosenRiwayat'])->name('riwayat'); 
});

// 3. Rute Khusus Mahasiswa
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    // 1. Dashboard
    Route::get('/dashboard', [MahasiswaController::class, 'mahasiswaDashboard'])->name('dashboard');
    
    // 2. Submit Judul
    Route::get('/submit', [MahasiswaController::class, 'showSubmitForm'])->name('submit');
    Route::post('/submit', [MahasiswaController::class, 'storeSubmit'])->name('submit.store');
    
    // 3. Status Pengajuan
    Route::get('/status', [MahasiswaController::class, 'mahasiswaStatus'])->name('status');
    
    // 4. Riwayat Pengajuan
    Route::get('/riwayat', [MahasiswaController::class, 'mahasiswaRiwayat'])->name('riwayat');
    Route::get('/delete-file/{id}', [MahasiswaController::class, 'mahasiswaDeleteFile'])->name('delete_file');
});

use Illuminate\Support\Facades\Artisan;

Route::get('/init-database', function () {
    try {
        Artisan::call('migrate:fresh --seed');
        return "Database Supabase berhasil dimigrasi dan di-seed!";
    } catch (\Exception $e) {
        return "Gagal migrasi: " . $e->getMessage();
    }
});
