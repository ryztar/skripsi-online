@extends('layouts.dashboard')
@section('page_title', 'Daftar Pengajuan Masuk')
@section('main_content')
<div class="mb-6">
    <h3 class="text-xl font-bold text-slate-800">Semua Pengajuan Judul</h3>
    <p class="text-xs text-slate-400 mt-1">Rekapitulasi berkas usulan skripsi mahasiswa yang beredar di sistem.</p>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <form action="" method="GET" class="mt-4 mb-4 ml-6 flex space-x-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama pengusul atau kata kunci judul..." class="w-full max-w-sm px-4 py-2 text-xs border rounded-xl focus:outline-none focus:border-blue-600 transition">
        <button type="submit" class="px-4 py-2 bg-slate-800 text-white font-bold text-xs rounded-xl">Cari</button>
    </form>
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider">
                <th class="py-4 px-6">Mahasiswa</th>
                <th class="py-4 px-6">Judul Usulan</th>
                <th class="py-4 px-6">Tanggal Masuk</th>
                <th class="py-4 px-6 text-center">Status</th>
            </tr>
        </thead>
        <tbody class="text-sm text-slate-600 divide-y divide-slate-100">
            @forelse($submissions as $sub)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="py-4 px-6">
                        <span class="font-bold text-slate-800 block">{{ $sub->user->name }}</span>
                        <span class="text-xs text-slate-400 font-mono">{{ $sub->user->mahasiswaProfile->nim ?? '-' }}</span>
                    </td>
                    <td class="py-4 px-6 font-medium text-slate-700">{{ $sub->judul }}</td>
                    <td class="py-4 px-6 text-xs text-slate-500">{{ $sub->created_at->format('d M Y H:i') }} WITA</td>
                    <td class="py-4 px-6 text-center">
                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg uppercase
                            {{ $sub->status == 'submitted' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                            {{ $sub->status == 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                            {{ $sub->status == 'revisi' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                            {{ $sub->status == 'rejected' ? 'bg-rose-50 text-rose-700 border border-rose-200' : '' }}
                        ">
                            {{ $sub->status }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-8 text-slate-400">Belum ada dokumen pengajuan masuk.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection