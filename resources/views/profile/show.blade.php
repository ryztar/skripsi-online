@extends('layouts.dashboard')

@section('page_title', 'Informasi Akun Anda')

@section('main_content')
<div class="max-w-2xl mx-auto">
    
    <div class="flex items-center space-x-4 mb-6">
        <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-sm">
            {{ substr($user->name, 0, 1) }}
        </div>
        
        <div>
            <h3 class="text-xl font-bold text-slate-800 tracking-tight">{{ $user->name }}</h3>
            <div class="inline-block mt-1 px-2.5 py-0.5 text-[10px] font-bold bg-slate-100 border border-slate-200 text-slate-500 rounded-full uppercase tracking-wider">
                Role: {{ $user->role }}
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-b-2xl border-x border-b border-slate-200 shadow-sm space-y-5">
        <div>
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Informasi Autentikasi Kredensial</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Nama Lengkap Pengguna</span>
                    <span class="text-xs font-bold text-slate-800 mt-0.5 block">{{ $user->name }}</span>
                </div>
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Alamat Email Terdaftar</span>
                    <span class="text-xs font-bold text-slate-800 mt-0.5 block font-mono">{{ $user->email }}</span>
                </div>
            </div>
        </div>

        <hr class="border-slate-100">

        <div>
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Identitas Nomor Pokok Akademik</h4>
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                @if($user->role == 'mahasiswa')
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Nomor Induk Mahasiswa (NIM)</span>
                    <span class="text-xs font-bold text-slate-800 mt-0.5 block font-mono">
                        {{ $user->mahasiswaProfile->nim ?? 'Data NIM belum dilengkapi oleh admin.' }}
                    </span>
                @elseif($user->role == 'dosen')
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Nomor Induk Dosen Nasional (NIDN)</span>
                    <span class="text-xs font-bold text-slate-800 mt-0.5 block font-mono">
                        {{ $user->dosenProfile->nidn ?? 'Data NIDN belum dilengkapi oleh admin.' }}
                    </span>
                @else
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Otoritas Akses Sistem</span>
                    <span class="text-xs font-bold text-slate-800 mt-0.5 block italic">
                        Hak akses Administrator penuh (Prodi/Jurusan)
                    </span>
                @endif
            </div>
        </div>

        <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-800 text-[11px] font-medium flex items-center space-x-2">
            <span>🔒</span>
            <p>Informasi profil bersifat terkunci dan diverifikasi oleh sistem prodi. Jika terdapat kesalahan data, hubungi Admin Jurusan.</p>
        </div>
    </div>
</div>
@endsection