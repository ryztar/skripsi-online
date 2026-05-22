@extends('layouts.dashboard')

@section('page_title', 'Dashboard Mahasiswa')

@section('main_content')
<div class="space-y-8">
    
    @php
        $notif = \App\Models\Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->latest()
            ->first();
    @endphp

    @if($notif)
        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 text-blue-800 text-xs rounded-xl flex justify-between items-center shadow-sm">
            <div>
                <strong>🔔 {{ $notif->title }}</strong>: {{ $notif->message }}
            </div>
        </div>
    @endif
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="space-y-2">
                <h4 class="text-xl font-bold text-slate-800">Selamat datang, {{ Auth::user()->name }}! 👋</h4>
                <p class="text-sm text-slate-500 leading-relaxed max-w-md">
                    Ajukan judul skripsi Anda dengan mudah dan pantau terus perkembangan status review dari dosen pembimbing secara real-time.
                </p>
                <div class="pt-2">
                    <span class="px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-semibold rounded-lg border border-blue-100">
                        Status Saat Ini: <span class="uppercase font-bold">{{ $submission->status ?? 'Belum Mengajukan' }}</span>
                    </span>
                </div>
            </div>
            <div class="hidden sm:block text-blue-600 bg-blue-50 p-4 rounded-full">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h5 class="font-bold text-sm uppercase tracking-wider text-slate-400">Pengumuman Internal</h5>
            <div class="space-y-3">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <p class="text-xs font-semibold text-slate-700">Jam Operasional Review Dosen</p>
                    <p class="text-xs text-slate-400 mt-0.5">08.00 - 16.00 WITA setiap hari kerja.</p>
                </div>
                <div class="p-3 bg-amber-50/60 rounded-xl border border-amber-100 text-amber-900">
                    <p class="text-xs font-semibold">Batas Pengajuan Judul Gelombang 1</p>
                    <p class="text-xs text-amber-700 mt-0.5">Ditutup pada akhir bulan ini.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <h4 class="font-bold text-slate-800 mb-6">Alur Pengajuan Skripsi Anda</h4>
        
        @php
            $submission = \App\Models\SkripsiSubmission::where('user_id', Auth::id())->first();
            
            // Default alur langkah awal jika belum menginput data apa-apa
            $step = 1; 
            
            if ($submission) {
                if ($submission->status == 'draft') $step = 2;
                // TUNTUTAN USER: Jika status 'submitted', ubah nama visual tahapan di dashboard menjadi tahapan "Review" (Step 3)
                elseif ($submission->status == 'submitted') $step = 3; 
                elseif ($submission->status == 'revisi') $step = 4;
                elseif ($submission->status == 'rejected') $step = 4; // Terisolasi di baris penolakan berkas
                elseif ($submission->status == 'approved') $step = 5;
            }
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 relative">
            
            <div class="text-center p-4 rounded-xl {{ $step >= 1 ? 'bg-blue-50/80 border border-blue-200' : 'opacity-50' }}">
                <div class="w-8 h-8 rounded-full {{ $step >= 1 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-600' }} flex items-center justify-center font-bold text-sm mx-auto mb-2">1</div>
                <h5 class="font-semibold text-xs text-slate-800">Draft</h5>
                <p class="text-[10px] text-slate-400 mt-1">Mahasiswa menyusun rancangan judul.</p>
            </div>

            <div class="text-center p-4 rounded-xl {{ $step >= 2 ? 'bg-blue-50/80 border border-blue-200' : 'opacity-50' }}">
                <div class="w-8 h-8 rounded-full {{ $step >= 2 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-600' }} flex items-center justify-center font-bold text-sm mx-auto mb-2">2</div>
                <h5 class="font-semibold text-xs text-slate-800">Submitted</h5>
                <p class="text-[10px] text-slate-400 mt-1">Judul berhasil dikirim ke sistem.</p>
            </div>

            <div class="text-center p-4 rounded-xl {{ $step == 3 ? 'bg-blue-50 border border-blue-200' : ($step > 3 ? 'bg-blue-50/80' : 'opacity-50') }}">
                <div class="w-8 h-8 rounded-full {{ $step == 3 ? 'bg-blue-600 text-white' : ($step > 3 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-600') }} flex items-center justify-center font-bold text-sm mx-auto mb-2">3</div>
                
                <h5 class="font-semibold text-xs text-slate-800">Review</h5>
                <p class="text-[10px] text-slate-400 mt-1">Dosen sedang memeriksa kelayakan komponen berkas Anda.</p>
            </div>

            <div class="text-center p-4 rounded-xl {{ $step == 4 ? 'bg-red-50 border border-red-200' : ($step > 4 ? 'bg-blue-50/80' : 'opacity-50') }}">
                <div class="w-8 h-8 rounded-full {{ $step == 4 ? 'bg-red-500 text-white' : ($step > 4 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-600') }} flex items-center justify-center font-bold text-sm mx-auto mb-2">4</div>
                <h5 class="font-semibold text-xs text-slate-800">Revisi</h5>
                <p class="text-[10px] text-slate-400 mt-1">Butuh perbaikan komponen data.</p>
            </div>

            <div class="text-center p-4 rounded-xl {{ $step == 5 ? 'bg-emerald-50 border border-emerald-200' : 'opacity-50' }}">
                <div class="w-8 h-8 rounded-full {{ $step == 5 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600' }} flex items-center justify-center font-bold text-sm mx-auto mb-2">5</div>
                <h5 class="font-semibold text-xs text-slate-800">Approved</h5>
                <p class="text-[10px] text-slate-400 mt-1">Judul disetujui, siap cetak SK.</p>
            </div>

        </div>
    </div>
</div>
@endsection