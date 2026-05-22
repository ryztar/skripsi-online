<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Skripsi Online - POLIMDO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans antialiased">
    
    <div class="min-h-screen flex flex-col md:flex-row">
        <div class="md:w-1/3 bg-slate-900 text-white flex flex-col justify-between p-8 md:p-12 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
            
            <div class="relative z-10 flex items-center space-x-3">
                <div class="bg-blue-600 p-2.5 rounded-xl shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                </div>
                <div>
                    <h1 class="font-bold text-lg leading-tight">Pengajuan Skripsi Online</h1>
                    <p class="text-xs text-slate-400">Politeknik Negeri Manado</p>
                </div>
            </div>

            <div class="relative z-10 my-auto py-12">
                <h2 class="text-3xl font-extrabold tracking-tight leading-tight mb-4">
                    Kelola Pengajuan Skripsi Anda Secara Digital & Transparan.
                </h2>
                <p class="text-sm text-slate-300 leading-relaxed">
                    Sistem informasi terintegrasi untuk mempermudah Mahasiswa, Dosen, dan Admin Program Studi dalam proses manajemen tugas akhir.
                </p>
            </div>

            <div class="relative z-10 text-xs text-slate-500">
                &copy; 2026 Jurusan Teknik Elektro POLIMDO. All rights reserved.
            </div>
        </div>

        <div class="flex-1 flex items-center justify-center p-6 sm:p-12 bg-slate-50">
            <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                @yield('content')
            </div>
        </div>
    </div>

</body>
</html>