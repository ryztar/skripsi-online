<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Cek apakah role pengguna saat ini terdaftar di role yang diizinkan mengakses rute ini
        $user = Auth::user();
        if (in_array($user->role, $roles)) {
            return $next($request); // Izinkan masuk
        }

        // 3. Jika tidak punya hak akses, lempar ke dashboard masing-masing
        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'dosen' => redirect()->route('dosen.dashboard'),
            default => redirect()->route('mahasiswa.dashboard'),
        };
    }
}