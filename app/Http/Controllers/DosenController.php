<?php

namespace App\Http\Controllers;

use App\Models\SkripsiSubmission;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenController extends Controller
{
    public function dosenDashboard()
    {
        $totalSubmitted = SkripsiSubmission::where('status', 'submitted')->count();
        $totalApproved = SkripsiSubmission::where('status', 'approved')->count();
        $totalRevisi = SkripsiSubmission::where('status', 'revisi')->count();
        $totalRejected = SkripsiSubmission::where('status', 'rejected')->count();

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

        $submissions = SkripsiSubmission::where('status', '!=', 'draft')
            ->with('user.mahasiswaProfile')
            ->when($search, function ($query, $search) {
                $query->where('judul', 'ilike', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'ilike', "%{$search}%");
                    });
            })
            ->latest()
            ->get();

        return view('dosen.received', compact('submissions'));
    }

    public function dosenReviewStore(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,revisi,rejected',
            'komentar' => 'nullable|string',
        ]);

        $submission = SkripsiSubmission::findOrFail($id);
        $submission->status = $request->status;
        $submission->komentar_dosen = $request->komentar;
        $submission->reviewed_by = auth()->id();
        $submission->reviewed_at = now();
        $submission->save();

        Notification::where('user_id', $submission->user_id)
            ->where('is_read', false)
            ->delete();

        Notification::create([
            'user_id' => $submission->user_id,
            'title' => 'Update Pengajuan Skripsi',
            'message' => 'Judul skripsi Anda mendapat status: ' . strtoupper($request->status) . '. Catatan: ' . ($request->komentar ?? '-'),
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Berhasil memperbarui status pengajuan menjadi ' . ($request->status));
    }

    public function dosenRiwayat()
    {
        $search = request()->query('search');

        $riwayatGlobal = SkripsiSubmission::with('user.mahasiswaProfile')
            ->when($search, function ($query, $search) {
                $query->where('judul', 'ilike', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'ilike', "%{$search}%");
                    });
            })
            ->whereIn('status', ['approved', 'revisi', 'rejected'])
            ->latest()
            ->get();

        return view('dosen.riwayat', compact('riwayatGlobal'));
    }
}
