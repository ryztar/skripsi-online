<?php

namespace App\Http\Controllers;

use App\Models\SkripsiSubmission;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    public function mahasiswaDashboard()
    {
        $submission = SkripsiSubmission::where('user_id', Auth::id())->first();
        return view('mahasiswa.dashboard', compact('submission'));
    }

    public function showSubmitForm()
    {
        $submission = SkripsiSubmission::where('user_id', Auth::id())->first();
        return view('mahasiswa.submit', compact('submission'));
    }

    public function storeSubmit(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'abstrak' => 'required|string',
            'latar_belakang' => 'required|string',
            'referensi' => 'nullable|string',
            'dokumen_pendukung' => 'nullable|mimes:pdf|max:2048',
        ]);

        $judulBaru = strtolower(trim($request->judul));

        $approvedExist = SkripsiSubmission::where('user_id', Auth::id())->where('status', 'approved')->first();
        if ($approvedExist) {
            return redirect()->back()->withInput()->withErrors(['judul' => 'Anda tidak bisa mengajukan judul baru karena pengajuan sebelumnya sudah DISETUJUI.']);
        }

        $existingSubmissions = SkripsiSubmission::where('user_id', '!=', Auth::id())
            ->where('status', '!=', 'draft')
            ->get();

        foreach ($existingSubmissions as $exist) {
            similar_text($judulBaru, strtolower(trim($exist->judul)), $percent);
            if ($percent > 80) {
                return redirect()->back()->withInput()->withErrors([
                    'judul' => '⚠️ Pengajuan ditolak! Judul ini memiliki kemiripan ' . round($percent, 1) . '% dengan judul yang sudah ada di sistem: "' . $exist->judul . '"'
                ]);
            }
        }

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

    public function mahasiswaStatus()
    {
        $submission = SkripsiSubmission::where('user_id', Auth::id())->latest()->first();
        return view('mahasiswa.status', compact('submission'));
    }

    public function mahasiswaRiwayat()
    {
        $search = request()->query('search');

        $riwayat = SkripsiSubmission::with('user.mahasiswaProfile')
            ->where('status', '!=', 'draft')
            ->when($search, function ($query, $search) {
                $query->where('judul', 'ilike', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
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
            Storage::disk('public')->delete('proposals/' . $submission->dokumen_pendukung);
            $submission->dokumen_pendukung = null;
            $submission->save();
        }

        return redirect()->back()->with('success', 'File pendukung berhasil dihapus!');
    }
}
