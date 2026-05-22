<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MahasiswaProfile;
use App\Models\DosenProfile;
use App\Models\SkripsiSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function adminDashboard()
    {
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalDosen = User::where('role', 'dosen')->count();
        $pengajuanMasuk = SkripsiSubmission::where('status', 'submitted')->count();
        $pengajuanDisetujui = SkripsiSubmission::where('status', 'approved')->count();

        $pengajuanTerbaru = SkripsiSubmission::with('user.mahasiswaProfile')->latest()->paginate(5, ['*'], 'page_submissions');

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

    public function adminDosen()
    {
        $dosens = User::where('role', 'dosen')->with('dosenProfile')->latest()->paginate(5);
        return view('admin.dosen', compact('dosens'));
    }

    public function adminDosenStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nidn' => 'required|string|max:20|unique:dosen_profiles,nidn',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'dosen',
        ]);

        DosenProfile::create([
            'user_id' => $user->id,
            'nidn' => $request->nidn,
        ]);

        return redirect()->route('admin.dosen')->with('success', 'Data Dosen baru berhasil ditambahkan!');
    }

    public function adminMahasiswa()
    {
        $mahasiswas = User::where('role', 'mahasiswa')->with('mahasiswaProfile')->latest()->paginate(5);
        return view('admin.mahasiswa', compact('mahasiswas'));
    }

    public function adminMahasiswaStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nim' => 'required|string|max:8|unique:mahasiswa_profiles,nim',
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

    public function adminReceived()
    {
        $search = request()->query('search');

        $submissions = SkripsiSubmission::with('user.mahasiswaProfile')
            ->latest()
            ->when($search, function ($query, $search) {
                $query->where('judul', 'ilike', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
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
            'nidn' => 'required|string|max:20|unique:dosen_profiles,nidn,' . $id . ',user_id',
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

    public function adminDosenDelete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Data Dosen berhasil dihapus!');
    }

    public function adminMahasiswaUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'nim' => 'required|string|max:8|unique:mahasiswa_profiles,nim,' . $id . ',user_id',
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

    public function adminMahasiswaDelete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Data Mahasiswa berhasil dihapus!');
    }
}
