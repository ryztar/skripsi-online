@extends('auth.layouts')

@section('content')
    <div class="mb-8">
        <h3 class="text-2xl font-bold text-slate-800">Login Sistem</h3>
        <p class="text-sm text-slate-500 mt-1">Masuk dengan akun Jurusan Anda.</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="space-y-5">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Alamat Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition" 
                placeholder="nama@kampus.ac.id">
        </div>

        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider">Password</label>
                <a href="#" class="text-xs text-blue-600 hover:underline font-medium">Lupa password?</a>
            </div>
            <input type="password" name="password" required
                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition" 
                placeholder="••••••••">
        </div>

        <button type="submit" 
            class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-xl shadow-sm hover:shadow transition-all duration-150 block text-center">
            Login
        </button>
    </form>

    <div class="mt-8 pt-6 border-t border-slate-100 text-center">
        <p class="text-sm text-slate-500">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">Daftar Mahasiswa</a>
        </p>
    </div>
@endsection