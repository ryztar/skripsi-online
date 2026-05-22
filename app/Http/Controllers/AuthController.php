<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MahasiswaProfile;
use App\Models\DosenProfile;
use App\Models\SkripsiSubmission;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
   //                                   ==================== FORM LOGIN AREA ====================
    // Menampilkan Form Login
    public function showLogin() {
        return view('auth.login');
    }

    // Proses Login Terpusat
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // REDIRECT OTOMATIS BERDASARKAN ROLE (Sesuai Permintaan)
            return match(Auth::user()->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'dosen' => redirect()->route('dosen.dashboard'),
                'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
            };
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

   //                                   ==================== FORM REGISTRASI AREA ====================
    // Menampilkan Form Registrasi (Khusus Mahasiswa)
    public function showRegister() {
        return view('auth.register');
    }

    // Proses Registrasi Mahasiswa
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:mahasiswa_profiles,nim',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 1. Simpan ke tabel Users sebagai mahasiswa
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',
        ]);

        // 2. Simpan NIM ke tabel profil mahasiswa
        MahasiswaProfile::create([
            'user_id' => $user->id,
            'nim' => $request->nim,
        ]);

        Auth::login($user);

        return redirect()->route('login');
    }

    // Proses Logout -- LOGOUT --
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function storeSubmit(Request $request)
    {
        // Validasi input form
        $request->validate([
            'judul' => 'required|string|max:255',
            'abstrak' => 'required|string',
            'latar_belakang' => 'required|string',
            'referensi' => 'nullable|string',
            'dokumen_pendukung' => 'nullable|mimes:pdf|max:2048', 
        ]);

        $judulBaru = strtolower(trim($request->judul));
    
        // Cek apakah mahasiswa ini sudah punya judul yang di-approve sebelumnya
        $approvedExist = SkripsiSubmission::where('user_id', Auth::id())->where('status', 'approved')->first();
        if ($approvedExist) {
            return redirect()->back()->withInput()->withErrors(['judul' => 'Anda tidak bisa mengajukan judul baru karena pengajuan sebelumnya sudah DISETUJUI.']);
        }

        // Pengecekan Duplikasi dengan judul lain di database (hanya pengajuan yang sudah dikirim atau sedang direview)
        $existingSubmissions = SkripsiSubmission::where('user_id', '!=', Auth::id())
            ->where('status', '!=', 'draft')
            ->get();

        foreach ($existingSubmissions as $exist) {
            similar_text($judulBaru, strtolower(trim($exist->judul)), $percent);
            
            if ($percent > 80) { // Jika tingkat kemiripan di atas 80%
                return redirect()->back()->withInput()->withErrors([
                    'judul' => '⚠️ Pengajuan ditolak! Judul ini memiliki kemiripan ' . round($percent, 1) . '% dengan judul yang sudah ada di sistem: "' . $exist->judul . '"'
                ]);
            }
        }

        // Proses upload file berkas
        $fileName = null;
        $existingSubmission = SkripsiSubmission::where('user_id', Auth::id())->first();
        if ($existingSubmission) {
            $fileName = $existingSubmission->dokumen_pendukung;
        }

        if ($request->hasFile('dokumen_pendukung')) {
            if ($fileName) {
                Storage::disk('public')->delete('proposals/' . $fileName);
            }
            $file = $request->file('dokumen_pendukung');
            $identifier = Auth::user()->mahasiswaProfile->nim ?? 'ID_' . Auth::id();
            $fileName = $identifier . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('proposals', $fileName, 'public');
        }

        $statusAction = $request->input('action') === 'draft' ? 'draft' : 'submitted';

        SkripsiSubmission::updateOrCreate(
            ['user_id' => Auth::id()], 
            [
                'judul' => $request->judul,
                'abstrak' => $request->abstrak,
                'latar_belakang' => $request->latar_belakang,
                'referensi' => $request->referensi,
                'dokumen_pendukung' => $fileName,
                'status' => $statusAction,
            ]
        );

        return redirect()->route('mahasiswa.status')->with('success', 'Data pengajuan berhasil diperbarui!');
    }

    // 1. Fungsi menampilkan form submit judul skripsi
    public function showSubmitForm()
    {
        $submission = \App\Models\SkripsiSubmission::where('user_id', Auth::id())->first();
        return view('mahasiswa.submit', compact('submission'));
    }

    // 2. Fungsi menampilkan halaman status pengajuan terperinci
    public function mahasiswaStatus()
    {
        // Cari data pengajuan aktif milik mahasiswa yang sedang login
        $submission = SkripsiSubmission::where('user_id', Auth::id())->latest()->first();

        return view('mahasiswa.status', compact('submission'));
    }

    // 3. Fungsi menampilkan riwayat pengajuan skripsi
    public function mahasiswaRiwayat()
    {
        $search = request()->query('search');

        $riwayat = SkripsiSubmission::with('user.mahasiswaProfile')
                    ->where('status', '!=', 'draft')
                    ->when($search, function($query, $search) {
                        $query->where('judul', 'ilike', "%{$search}%")
                            ->orWhereHas('user', function($q) use ($search) {
                                $q->where('name', 'ilike', "%{$search}%");
                            });
                    })
                    ->latest()
                    ->paginate(5);

        return view('mahasiswa.riwayat', compact('riwayat'));
    }

    public function mahasiswaDeleteFile($id)
    {
        $submission = SkripsiSubmission::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        
        if ($submission->dokumen_pendukung) {
            // Hapus file dari penyimpanan lokal/public storage
            Storage::disk('public')->delete('proposals/' . $submission->dokumen_pendukung);
            
            // Kosongkan nama kolom berkas di database
            $submission->dokumen_pendukung = null;
            $submission->save();
        }

        return redirect()->back()->with('success', 'File pendukung berhasil dihapus!');
    }

    //                                   ==================== DOSEN AREA ====================
    // 1. Menampilkan Halaman Daftar Pengajuan Masuk untuk Dosen
    public function dosenDashboard()
    {
        // Menghitung statistik global untuk dashboard dosen
        $totalSubmitted = SkripsiSubmission::where('status', 'submitted')->count();
        $totalApproved  = SkripsiSubmission::where('status', 'approved')->count();
        $totalRevisi    = SkripsiSubmission::where('status', 'revisi')->count();
        $totalRejected  = SkripsiSubmission::where('status', 'rejected')->count();

        // Mengambil 5 antrean pengajuan terbaru yang butuh direview
        $antreanMasuk = SkripsiSubmission::with('user.mahasiswaProfile')
                        ->where('status', 'submitted')
                        ->latest()
                        ->take(5)
                        ->get();

        return view('dosen.dashboard', compact('totalSubmitted', 'totalApproved', 'totalRevisi', 'totalRejected', 'antreanMasuk'));
    }

    public function dosenReceived()
    {
        $search = request()->query('search');

        // Mengambil semua pengajuan yang statusnya BUKAN draft (artinya sudah dikirim oleh mahasiswa)
        $submissions = SkripsiSubmission::where('status', '!=', 'draft')
                        ->with('user.mahasiswaProfile') // Meload data nama dan NIM mahasiswa
                        ->when($search, function ($query, $search) {
                            $query->where('judul', 'ilike', "%{$search}%")
                                ->orWhereHas('user', function($q) use ($search) {
                                    $q->where('name', 'ilike', "%{$search}%");
                                });
                        })
                        ->latest()
                        ->get();

        return view('dosen.received', compact('submissions'));
    }

    // 2. Memproses Keputusan Review dari Dosen (Approve / Revisi / Tolak)
    public function dosenReviewStore(Request $request, $id)
    {
        // 1. Validasi input dari modal
        $request->validate([
            'status' => 'required|in:approved,revisi,rejected',
            'komentar' => 'nullable|string',
        ]);

        // 2. Cari data pengajuan berdasarkan ID
        $submission = SkripsiSubmission::findOrFail($id);
        
        // 3. Paksa simpan data secara langsung (Aman dari kendala $fillable)
        $submission->status = $request->status;
        $submission->komentar_dosen = $request->komentar;
        $submission->reviewed_by = auth()->id();
        $submission->reviewed_at = now();
        $submission->save();

        // 4. Proses pembuatan notifikasi otomatis untuk mahasiswa
        Notification::where('user_id', $submission->user_id)
            ->where('is_read', false)
            ->delete();

        Notification::create([
            'user_id' => $submission->user_id,
            'title' => 'Update Pengajuan Skripsi',
            'message' => 'Judul skripsi Anda mendapat status: ' . strtoupper($request->status) . '. Catatan: ' . ($request->komentar ?? '-'),
            'is_read' => false,
        ]);

        // 5. Kembalikan ke halaman received (sesuai alur kerja dashboard dosen Anda)
        return redirect()->back()->with('success', 'Berhasil memperbarui status pengajuan menjadi ' . ($request->status));
    }

    public function dosenRiwayat()
    {
        $search = request()->query('search');

        // Mengambil semua judul yang sudah berstatus disetujui, revisi, atau ditolak
        $riwayatGlobal = SkripsiSubmission::with('user.mahasiswaProfile')
                            ->when($search, function ($query, $search) {
                                $query->where('judul', 'ilike', "%{$search}%")
                                    ->orWhereHas('user', function($q) use ($search) {
                                        $q->where('name', 'ilike', "%{$search}%");
                                    });
                            })
                            ->whereIn('status', ['approved', 'revisi', 'rejected'])
                            ->latest()
                            ->get();

        return view('dosen.riwayat', compact('riwayatGlobal'));
    }

    //                                   ==================== ADMIN AREA ====================
    public function adminDashboard()
    {
        $totalMahasiswa = \App\Models\User::where('role', 'mahasiswa')->count();
        $totalDosen = \App\Models\User::where('role', 'dosen')->count();
        $pengajuanMasuk = SkripsiSubmission::where('status', 'submitted')->count();
        $pengajuanDisetujui = SkripsiSubmission::where('status', 'approved')->count();

        // Gunakan Paginate(5) untuk tabel ringkasan bawah
        $pengajuanTerbaru = SkripsiSubmission::with('user.mahasiswaProfile')->latest()->paginate(5, ['*'], 'page_submissions');

        // Data Line Chart Bulan Ini (Statistik Pengajuan Sesuai Gambar 3)
        $chartData = [
            'labels' => ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
            'data' => [
                SkripsiSubmission::whereBetween('created_at', [now()->startOfMonth(), now()->startOfMonth()->addDays(7)])->count(),
                SkripsiSubmission::whereBetween('created_at', [now()->startOfMonth()->addDays(8), now()->startOfMonth()->addDays(14)])->count(),
                SkripsiSubmission::whereBetween('created_at', [now()->startOfMonth()->addDays(15), now()->startOfMonth()->addDays(21)])->count(),
                SkripsiSubmission::whereBetween('created_at', [now()->startOfMonth()->addDays(22), now()->endOfMonth()])->count(),
            ]
        ];

        return view('admin.dashboard', compact('totalMahasiswa', 'totalDosen', 'pengajuanMasuk', 'pengajuanDisetujui', 'pengajuanTerbaru', 'chartData'));
    }

    public function adminDosen() {
        $dosens = \App\Models\User::where('role', 'dosen')->with('dosenProfile')->latest()->paginate(5);
        return view('admin.dosen', compact('dosens'));
    }

    // 2. Menyimpan Data Dosen Baru ke Database
    public function adminDosenStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nidn' => 'required|string|max:20|unique:dosen_profiles,nidn', // Ditangani sebagai string karena bisa mengandung karakter khusus
            'password' => 'required|string|min:8',
        ]);

        // Pembuatan Akun User Utama (untuk Login Auth)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'dosen',
        ]);

        // Pembuatan Profil Dosen Relasional (untuk NIDN)
        DosenProfile::create([
            'user_id' => $user->id,
            'nidn' => $request->nidn,
        ]);

        return redirect()->route('admin.dosen')->with('success', 'Data Dosen baru berhasil ditambahkan!');
    }

    public function adminMahasiswaStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nim' => 'required|string|max:8|unique:mahasiswa_profiles,nim', // Ditangani sebagai bilangan bulat
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',
        ]);

        MahasiswaProfile::create([
            'user_id' => $user->id,
            'nim' => $request->nim,
        ]);

        return redirect()->route('admin.mahasiswa')->with('success', 'Data Mahasiswa baru berhasil ditambahkan!');
    }

    public function adminMahasiswa() {
        $mahasiswas = \App\Models\User::where('role', 'mahasiswa')->with('mahasiswaProfile')->latest()->paginate(5);
        return view('admin.mahasiswa', compact('mahasiswas'));
    }

    public function adminReceived()
    {
        $search = request()->query('search');

        // Mengambil semua pengajuan yang sudah masuk ke sistem prodi
        $submissions = SkripsiSubmission::with('user.mahasiswaProfile')
                            ->latest()
                            ->when($search, function ($query, $search) {
                                $query->where('judul', 'ilike', "%{$search}%")
                                    ->orWhereHas('user', function($q) use ($search) {
                                        $q->where('name', 'ilike', "%{$search}%");
                                    });
                            })
                            ->get();
        return view('admin.received', compact('submissions'));
    }

    public function adminDosenUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'nidn' => 'required|string|max:20|unique:dosen_profiles,nidn,' . $id, 'user_id',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        DosenProfile::updateOrCreate(
            ['user_id' => $user->id],
            ['nidn' => $request->nidn]
        );

        return redirect()->back()->with('success', 'Data Dosen berhasil diperbarui!');
    }

    // 4. BARU: Fungsi Hapus Dosen (Admin)
    public function adminDosenDelete($id)
    {
        $user = User::findOrFail($id);
        $user->delete(); // Otomatis menghapus profile jika cascade, atau hapus manual jika tidak

        return redirect()->back()->with('success', 'Data Dosen berhasil dihapus!');
    }

    // 5. BARU: Fungsi Update Data Mahasiswa (Admin)
    public function adminMahasiswaUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'nim' => 'required|string|max:8|unique:mahasiswa_profiles,nim,' . $id, 'user_id',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        MahasiswaProfile::updateOrCreate(
            ['user_id' => $user->id],
            ['nim' => $request->nim]
        );

        return redirect()->back()->with('success', 'Data Mahasiswa berhasil diperbarui!');
    }

    // 6. BARU: Fungsi Hapus Mahasiswa (Admin)
    public function adminMahasiswaDelete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Data Mahasiswa berhasil dihapus!');
    }

    public function showProfile()
    {
        // Mengambil data user yang sedang login beserta relasi profilnya
        $user = \App\Models\User::with(['mahasiswaProfile', 'dosenProfile'])->findOrFail(auth()->id());
        
        return view('profile.show', compact('user'));
    }

}