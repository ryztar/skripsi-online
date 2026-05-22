@extends('layouts.dashboard')

@section('page_title', 'Riwayat Review Dosen')

@section('main_content')
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100">
        <h4 class="text-lg font-bold text-slate-800">Daftar Judul yang Sudah Anda Evaluasi</h4>
    </div>
    <div class="overflow-x-auto">
        <form action="" method="GET" class="mt-4 mb-4 ml-6 flex space-x-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama pengusul atau kata kunci judul..." class="w-full max-w-sm px-4 py-2 text-xs border rounded-xl focus:outline-none focus:border-blue-600 transition">
            <button type="submit" class="px-4 py-2 bg-slate-800 text-white font-bold text-xs rounded-xl">Cari</button>
        </form>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider">
                    <th class="py-4 px-6">Mahasiswa</th>
                    <th class="py-4 px-6">Judul Skripsi</th>
                    <th class="py-4 px-6">Tanggal Keputusan</th>
                    <th class="py-4 px-6 text-center">Status Akhir</th>
                </tr>
            </thead>
            <tbody class="text-sm text-slate-600 divide-y divide-slate-100">
                @forelse($riwayatGlobal as $riwayat)
                    <tr>
                        <td class="py-4 px-6">
                            <p class="font-semibold text-slate-800">{{ $riwayat->user->name }}</p>
                            <p class="text-xs text-slate-400">{{ $riwayat->user->mahasiswaProfile->nim ?? '-' }}</p>
                        </td>
                        <td class="py-4 px-6 font-medium text-slate-800 max-w-xs truncate">{{ $riwayat->judul }}</td>
                        <td class="py-4 px-6">{{ \Carbon\Carbon::parse($riwayat->reviewed_at)->format('d M Y') }}</td>
                        <td class="py-4 px-6 text-center">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg uppercase 
                                {{ $riwayat->status == 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : '' }}
                                {{ $riwayat->status == 'revisi' ? 'bg-amber-50 text-amber-700 border border-amber-100' : '' }}
                                {{ $riwayat->status == 'draft' ? 'bg-red-50 text-red-700 border border-red-100' : '' }}
                            ">
                                {{ $riwayat->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-slate-400">Anda belum pernah melakukan review dokumen.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection