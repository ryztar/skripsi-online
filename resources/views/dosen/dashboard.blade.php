@extends('layouts.dashboard')

@section('page_title', 'Dashboard Dosen')

@section('main_content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Perlu Review</p>
            <h3 class="text-2xl font-black text-amber-600 mt-1">{{ $totalSubmitted }}</h3>
        </div>
        <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">📄</div>
    </div>
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Disetujui</p>
            <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ $totalApproved }}</h3>
        </div>
        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">✓</div>
    </div>
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Butuh Revisi</p>
            <h3 class="text-2xl font-black text-blue-600 mt-1">{{ $totalRevisi }}</h3>
        </div>
        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">⟳</div>
    </div>
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Ditolak</p>
            <h3 class="text-2xl font-black text-rose-600 mt-1">{{ $totalRejected }}</h3>
        </div>
        <div class="p-3 bg-rose-50 text-rose-600 rounded-xl">✕</div>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100">
        <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Antrean Pengajuan Judul</h4>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider">
                    <th class="py-3 px-6">Mahasiswa</th>
                    <th class="py-3 px-6">Judul Skripsi</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-xs text-slate-600 divide-y divide-slate-100">
                @forelse($antreanMasuk as $am)
                    <tr>
                        <td class="py-4 px-6">
                            <span class="font-bold text-slate-800 block">{{ $am->user->name }}</span>
                            <span class="text-slate-400 font-mono">{{ $am->user->mahasiswaProfile->nim ?? '-' }}</span>
                        </td>
                        <td class="py-4 px-6 font-medium text-slate-700">{{ $am->judul }}</td>
                        <td class="py-4 px-6 text-center">
                            <a href="{{ route('dosen.received') }}" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition">Review</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-6 text-slate-400">Tidak ada antrean dokumen masuk saat ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection