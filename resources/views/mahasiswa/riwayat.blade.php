@extends('layouts.dashboard')

@section('page_title', 'Riwayat Pengajuan')

@section('main_content')
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100">
        <h4 class="text-lg font-bold text-slate-800">Daftar Semua Riwayat Pengajuan</h4>
    </div>
    <div class="overflow-x-auto">
        <form action="" method="GET" class="mt-4 mb-4 ml-6 flex space-x-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama pengusul atau kata kunci judul..." class="w-full max-w-sm px-4 py-2 text-xs border rounded-xl focus:outline-none focus:border-blue-600 transition">
            <button type="submit" class="px-4 py-2 bg-slate-800 text-white font-bold text-xs rounded-xl">Cari</button>
        </form>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider">
                    <th class="py-3 px-6">Pengusul</th>
                    <th class="py-3 px-6">Judul Skripsi</th>
                    <th class="py-3 px-6">Tanggal Pengajuan</th>
                    <th class="py-3 px-6 text-center">Detail</th>
                    <th class="py-3 px-6 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="text-xs text-slate-600 divide-y divide-slate-100">
                @forelse($riwayat as $r)
                    <tr>
                        <td class="py-4 px-6">
                            <p class="font-bold text-slate-800">{{ $r->user->name }}</p>
                            <p class="text-slate-400 font-mono">{{ $r->user->mahasiswaProfile->nim ?? '-' }}</p>
                        </td>
                        <td class="py-4 px-6 max-w-xs truncate font-semibold text-slate-700">
                            {{ $r->judul }}
                        </td>
                        <td class="py-4 px-6 text-slate-500">{{ $r->created_at->format('d M Y') }}</td>
                        <td class="py-4 px-6 text-center">
                            <button onclick="openReadModal({{ json_encode($r->judul) }}, {{ json_encode($r->abstrak) }}, {{ json_encode($r->latar_belakang) }})" 
                                    class="px-2.5 py-1 text-xs font-semibold bg-slate-100 text-slate-700 rounded-lg border border-slate-200 hover:bg-slate-200 transition">
                                Lihat
                            </button>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="px-2 py-0.5 font-bold rounded uppercase text-[10px]
                                {{ $r->status == 'submitted' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $r->status == 'approved' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $r->status == 'revisi' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $r->status == 'rejected' ? 'bg-rose-100 text-rose-700' : '' }}
                            ">
                                {{ $r->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-slate-400">Belum ada riwayat pengajuan dalam sistem.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div> </div> <div id="readModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg p-6 space-y-4 shadow-xl">
        <h4 id="popJudul" class="text-sm font-bold text-slate-800"></h4>
        <div class="max-h-60 overflow-y-auto space-y-2 text-xs text-slate-600">
            <p><strong>Abstrak:</strong></p>
            <p id="popAbstrak" class="bg-slate-50 p-3 rounded-lg border"></p>
            <p><strong>Latar Belakang:</strong></p>
            <p id="popLatar" class="bg-slate-50 p-3 rounded-lg border"></p>
        </div>
        <button onclick="document.getElementById('readModal').classList.add('hidden')" class="w-full py-2 bg-slate-800 text-white font-bold text-xs rounded-xl">Tutup</button>
    </div>
</div>

<script>
    function openReadModal(judul, abstrak, latar) {
        document.getElementById('popJudul').innerText = judul;
        document.getElementById('popAbstrak').innerText = abstrak;
        document.getElementById('popLatar').innerText = latar;
        document.getElementById('readModal').classList.remove('hidden');
    }
</script>
@endsection