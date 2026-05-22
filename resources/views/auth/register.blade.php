@extends('auth.layouts')

@section('content')
    <div class="mb-6">
        <h3 class="text-2xl font-bold text-slate-800">Registrasi Akun</h3>
        <p class="text-sm text-slate-500 mt-1">Khusus Mahasiswa aktif Teknik Informatika.</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl space-y-1">
            @foreach ($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Nomor Induk Mahasiswa (NIM)</label>
            <input type="text" name="nim" value="{{ old('nim') }}" required
                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-600 transition" 
                placeholder="Contoh: 23024001">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-600 transition" 
                placeholder="Masukkan Nama Lengkap">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Email Aktif</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-600 transition" 
                placeholder="nama@polimdo.ac.id">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Password Baru</label>
            <input type="password" name="password" required
                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-600 transition" 
                placeholder="Minimal 8 karakter">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required
                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-600 transition" 
                placeholder="Ulangi password baru">
        </div>

        <button type="submit" 
            class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-xl shadow-sm transition mt-2">
            Register
        </button>
    </form>

    <div class="mt-6 pt-4 border-t border-slate-100 text-center">
        <p class="text-sm text-slate-500">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Login di sini</a>
        </p>
    </div>
@endsection