@extends('layouts.dashboard')

@section('page_title', 'Dashboard Admin')

@section('main_content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Mahasiswa</p>
            <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $totalMahasiswa }}</h3>
        </div>
        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Dosen</p>
            <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $totalDosen }}</h3>
        </div>
        <div class="p-3 bg-teal-50 text-teal-600 rounded-xl">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pengajuan Masuk</p>
            <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $pengajuanMasuk }}</h3>
        </div>
        <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pengajuan Disetujui</p>
            <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $pengajuanDisetujui }}</h3>
        </div>
        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between min-h-[500px]">
        <div>
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Pengajuan Terbaru</h4>
                <a href="#" class="text-xs font-semibold text-blue-600 hover:underline">Lihat semua pengajuan</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider">
                            <th class="py-3 px-6">Mahasiswa</th>
                            <th class="py-3 px-6">Usulan Judul</th>
                            <th class="py-3 px-6 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs text-slate-600 divide-y divide-slate-100">
                        @forelse($pengajuanTerbaru as $pt)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 px-6">
                                    <p class="font-semibold text-slate-800">{{ $pt->user->name }}</p>
                                    <p class="text-slate-400 mt-0.5">{{ $pt->user->mahasiswaProfile->nim ?? '-' }}</p>
                                </td>
                                <td class="py-4 px-6 font-medium text-slate-700 max-w-xs truncate">{{ $pt->judul }}</td>
                                <td class="py-4 px-6 text-center">
                                    <span class="px-2 py-0.5 font-bold rounded text-[10px] uppercase
                                        {{ $pt->status == 'submitted' || $pt->status == 'SUBMITTED' ? 'bg-amber-100 text-amber-700' : '' }}
                                        {{ $pt->status == 'approved' || $pt->status == 'APPROVED' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                        {{ $pt->status == 'revisi' || $pt->status == 'REVISI' ? 'bg-slate-100 text-slate-600' : '' }}
                                    ">
                                        {{ $pt->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-8 text-slate-400">Belum ada pengajuan judul yang masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            @if($pengajuanTerbaru instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                {{ $pengajuanTerbaru->links() }}
            @else
                <div class="flex items-center justify-between text-xs text-slate-500">
                    <span>Menampilkan 1-5 dari 5 data</span>
                    <div class="inline-flex space-x-1">
                        <button class="px-2.5 py-1 rounded border border-slate-200 bg-white text-slate-400 cursor-not-allowed" disabled>Sebelumnya</button>
                        <button class="px-2.5 py-1 rounded border border-blue-600 bg-blue-600 text-white font-medium">1</button>
                        <button class="px-2.5 py-1 rounded border border-slate-200 bg-white hover:bg-slate-50 transition">Selanjutnya</button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="space-y-6">
        
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Statistik Pengajuan</h4>
                <span class="text-[10px] bg-blue-50 text-blue-600 font-bold px-2 py-0.5 rounded">Bulan Ini</span>
            </div>
            <div class="h-56 relative">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between min-h-[190px]">
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Pengumuman</h4>
                    <a href="#" class="text-xs font-semibold text-blue-600 hover:underline">Lihat semua</a>
                </div>
                <div class="space-y-3">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs font-bold text-slate-700">Jam Operasional Review Dosen</p>
                        <p class="text-[11px] text-slate-400 mt-1">08.00 - 16.00 WITA setiap hari kerja.</p>
                    </div>
                </div>
            </div>
            <div class="text-[10px] text-slate-400 mt-4 text-center border-t border-slate-100 pt-3">
                &copy; 2026 Pengajuan Skripsi Online.
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('trendChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels'] ?? ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4']) !!},
            datasets: [{
                label: 'Jumlah Dokumen Masuk',
                data: {!! json_encode($chartData['data'] ?? [0, 0, 0, 0]) !!},
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.05)',
                tension: 0.4,
                fill: true,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endsection