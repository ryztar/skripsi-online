@extends('layouts.dashboard')

@section('page_title', 'Status Pengajuan Judul')
@section('page_subtitle', 'Lihat riwayat, detail, dan respons dosen dengan tampilan yang jelas.')

@section('main_content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    
    <div class="lg:col-span-2 space-y-6">
        
        @if(!$submission)
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm p-8 text-center">
                <span class="text-4xl">📋</span>
                <p class="text-sm text-slate-500 font-medium mt-3">Anda belum mengirimkan pengajuan judul skripsi dalam sistem.</p>
            </div>
        @else
            @php
                $statusBadge = 'bg-slate-100 text-slate-700 border-slate-200';
                $progressValue = 30;
                if ($submission->status == 'submitted') {
                    $statusBadge = 'bg-amber-100 text-amber-700 border-amber-200';
                    $progressValue = 45;
                } elseif ($submission->status == 'revisi') {
                    $statusBadge = 'bg-sky-100 text-sky-700 border-sky-200';
                    $progressValue = 70;
                } elseif ($submission->status == 'approved') {
                    $statusBadge = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                    $progressValue = 100;
                } elseif ($submission->status == 'rejected') {
                    $statusBadge = 'bg-rose-100 text-rose-700 border-rose-200';
                    $progressValue = 100;
                }
            @endphp

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm p-6 space-y-6">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Saat Ini</span>
                        <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900">
                            @if($submission->status == 'submitted') Review
                            @elseif($submission->status == 'revisi') Butuh Revisi
                            @elseif($submission->status == 'rejected') Ditolak
                            @elseif($submission->status == 'approved') Disetujui
                            @endif
                        </h2>
                        <p class="text-sm text-slate-500 mt-3 max-w-2xl">
                            @if($submission->status == 'submitted') Dosen sedang melakukan review terhadap pengajuan Anda.
                            @elseif($submission->status == 'revisi') Pengajuan memerlukan perbaikan berdasarkan catatan dosen.
                            @elseif($submission->status == 'rejected') Pengajuan ditolak, silakan ajukan judul alternatif baru.
                            @elseif($submission->status == 'approved') Pengajuan judul resmi disetujui.
                            @endif
                        </p>
                    </div>
                    <div class="inline-flex items-center gap-3 rounded-3xl border px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] {{ $statusBadge }} shadow-sm">
                        <span class="text-base">•</span>
                        <span>
                            @if($submission->status == 'submitted') Menunggu Review
                            @elseif($submission->status == 'revisi') Perlu Revisi
                            @elseif($submission->status == 'rejected') Ditolak
                            @elseif($submission->status == 'approved') Disetujui
                            @endif
                        </span>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between text-[11px] uppercase tracking-[0.3em] text-slate-400 font-semibold">
                        <p>Progres Status</p>
                        <p>{{ $progressValue }}%</p>
                    </div>
                    <div class="h-3 overflow-hidden rounded-full bg-slate-100 border border-slate-200">
                        <div class="h-full rounded-full bg-gradient-to-r from-blue-500 to-sky-500 transition-all duration-500" style="width: {{ $progressValue }}%"></div>
                    </div>
                </div>

                <div class="pt-4 text-[11px] text-slate-500 border-t border-slate-100">
                    Terakhir diperbarui: <span class="font-medium text-slate-700">{{ $submission->updated_at->format('d M Y, H:i') }} WITA</span>
                </div>
            </div>

            @if(in_array($submission->status, ['revisi', 'rejected']) && $submission->komentar_dosen)
                <div class="p-5 bg-amber-50 border border-amber-200 rounded-2xl shadow-sm animate-pulse-once">
                    <div class="flex items-start space-x-3">
                        <span class="text-lg mt-0.5">⚠️</span>
                        <div class="w-full">
                            <h4 class="text-xs font-bold text-amber-800 uppercase tracking-wider">Catatan Perbaikan Pembimbing:</h4>
                            <p class="text-sm text-amber-900 mt-2 font-medium bg-white/90 p-4 rounded-xl border border-amber-100 italic shadow-sm leading-relaxed">
                                "{{ $submission->komentar_dosen }}"
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-6">Timeline Status</h4>
                
                <div class="relative pl-6 border-l-2 border-slate-100 space-y-8 ml-3">
                    
                    <div class="relative">
                        <span class="absolute -left-[31px] top-0.5 w-3.5 h-3.5 rounded-full border-[3px] border-white bg-emerald-500 shadow-sm ring-2 ring-emerald-500/10"></span>
                        <div class="flex justify-between items-start">
                            <div>
                                <h5 class="text-xs font-bold text-slate-800 uppercase">Draft</h5>
                                <p class="text-xs text-slate-400 mt-0.5 font-medium">Pengajuan dibuat dan disimpan sebagai draft.</p>
                            </div>
                            <span class="text-[10px] font-medium text-slate-400 font-mono">{{ $submission->created_at->format('d M Y, H:i') }} WITA</span>
                        </div>
                    </div>

                    <div class="relative">
                        <span class="absolute -left-[31px] top-0.5 w-3.5 h-3.5 rounded-full border-[3px] border-white bg-emerald-500 shadow-sm ring-2 ring-emerald-500/10"></span>
                        <div class="flex justify-between items-start">
                            <div>
                                <h5 class="text-xs font-bold text-slate-800 uppercase">Submitted</h5>
                                <p class="text-xs text-slate-400 mt-0.5 font-medium">Pengajuan berhasil dikirimkan ke sistem prodi.</p>
                            </div>
                            <span class="text-[10px] font-medium text-slate-400 font-mono">{{ $submission->created_at->format('d M Y, H:i') }} WITA</span>
                        </div>
                    </div>

                    <div class="relative">
                        @if($submission->status == 'submitted')
                            <span class="absolute -left-[31px] top-0.5 w-3.5 h-3.5 rounded-full border-[3px] border-white bg-amber-500 ring-4 ring-amber-500/30 shadow-sm animate-pulse"></span>
                        @elseif(in_array($submission->status, ['revisi', 'rejected', 'approved']))
                            <span class="absolute -left-[31px] top-0.5 w-3.5 h-3.5 rounded-full border-[3px] border-white bg-emerald-500 shadow-sm"></span>
                        @else
                            <span class="absolute -left-[31px] top-0.5 w-3.5 h-3.5 rounded-full border-[3px] border-white bg-slate-200 shadow-sm"></span>
                        @endif
                        
                        <div class="flex justify-between items-start">
                            <div>
                                <h5 class="text-xs font-bold uppercase {{ $submission->status == 'submitted' ? 'text-amber-600' : 'text-slate-700' }}">Review</h5>
                                <p class="text-xs text-slate-400 mt-0.5 font-medium">Dosen sedang melakukan review terhadap pengajuan Anda.</p>
                            </div>
                            @if(in_array($submission->status, ['submitted', 'revisi', 'rejected', 'approved']))
                                <span class="text-[10px] font-medium text-slate-400 font-mono">{{ $submission->updated_at->format('d M Y, H:i') }} WITA</span>
                            @else
                                <span class="text-[10px] font-bold text-slate-300 font-mono">-</span>
                            @endif
                        </div>
                    </div>

                    <div class="relative">
                        @if($submission->status == 'revisi')
                            <span class="absolute -left-[31px] top-0.5 w-3.5 h-3.5 rounded-full border-[3px] border-white bg-blue-500 ring-4 ring-blue-500/30 shadow-sm"></span>
                        @elseif(in_array($submission->status, ['approved']))
                            <span class="absolute -left-[31px] top-0.5 w-3.5 h-3.5 rounded-full border-[3px] border-white bg-slate-200 shadow-sm"></span>
                        @else
                            <span class="absolute -left-[31px] top-0.5 w-3.5 h-3.5 rounded-full border-[3px] border-white bg-slate-200 shadow-sm"></span>
                        @endif
                        
                        <div class="flex justify-between items-start">
                            <div>
                                <h5 class="text-xs font-bold uppercase {{ $submission->status == 'revisi' ? 'text-blue-600' : 'text-slate-400' }}">Revisi</h5>
                                <p class="text-xs text-slate-400 mt-0.5 font-medium">Menunggu jika ada perbaikan data atau berkas proposal.</p>
                            </div>
                            @if($submission->status == 'revisi')
                                <span class="text-[10px] font-medium text-slate-400 font-mono">{{ $submission->updated_at->format('d M Y, H:i') }} WITA</span>
                            @else
                                <span class="text-[10px] font-bold text-slate-300 font-mono">-</span>
                            @endif
                        </div>
                    </div>

                    <div class="relative">
                        @if($submission->status == 'approved')
                            <span class="absolute -left-[31px] top-0.5 w-3.5 h-3.5 rounded-full border-[3px] border-white bg-emerald-600 ring-4 ring-emerald-600/20 shadow-sm"></span>
                        @elseif($submission->status == 'rejected')
                            <span class="absolute -left-[31px] top-0.5 w-3.5 h-3.5 rounded-full border-[3px] border-white bg-rose-500 ring-4 ring-rose-500/30 shadow-sm"></span>
                        @else
                            <span class="absolute -left-[31px] top-0.5 w-3.5 h-3.5 rounded-full border-[3px] border-white bg-slate-200 shadow-sm"></span>
                        @endif
                        
                        <div class="flex justify-between items-start">
                            <div>
                                <h5 class="text-xs font-bold uppercase 
                                    {{ $submission->status == 'approved' ? 'text-emerald-600' : '' }}
                                    {{ $submission->status == 'rejected' ? 'text-rose-600' : '' }}
                                    {{ !in_array($submission->status, ['approved', 'rejected']) ? 'text-slate-400' : '' }}
                                ">
                                    @if($submission->status == 'rejected') Rejected @else Approved @endif
                                </h5>
                                <p class="text-xs text-slate-400 mt-0.5 font-medium">Menunggu persetujuan akhir atau keputusan kelayakan judul.</p>
                            </div>
                            @if(in_array($submission->status, ['approved', 'rejected']))
                                <span class="text-[10px] font-medium text-slate-400 font-mono">{{ $submission->updated_at->format('d M Y, H:i') }} WITA</span>
                            @else
                                <span class="text-[10px] font-bold text-slate-300 font-mono">-</span>
                            @endif
                        </div>
                    </div>

                </div> </div>
        @endif
    </div>

    <div class="space-y-6">
        @if($submission)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-3">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Informasi Judul Skripsi</span>
                <div class="pt-1">
                    <p class="text-xs text-slate-700 font-bold font-sans leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100 shadow-inner">
                        {{ $submission->judul }}
                    </p>
                </div>
            </div>

            @if($submission->status == 'approved')
                <div class="p-5 bg-emerald-50 text-emerald-800 rounded-2xl border border-emerald-100 text-xs space-y-3 shadow-sm">
                    <p class="font-bold text-emerald-900 flex items-center text-xs uppercase tracking-wide">
                        <span class="mr-2 text-base">📋</span> Langkah Lanjutan:
                    </p>
                    <div class="space-y-2 text-emerald-700 font-medium pl-1 leading-relaxed">
                        <p>1. Silakan cetak lembar persetujuan judul melalui admin prodi.</p>
                        <p>2. Hubungi dosen pembimbing yang tertera untuk memulai bimbingan Bab 1.</p>
                    </div>
                </div>
            @endif
        @endif

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-3">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Jam Operasional</span>
            <div class="flex items-center space-x-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                <span class="text-xl">📢</span>
                <div>
                    <h5 class="text-xs font-bold text-slate-700">Jam Operasional Review Dosen</h5>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">08.00 - 16.00 WITA setiap hari kerja.</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection