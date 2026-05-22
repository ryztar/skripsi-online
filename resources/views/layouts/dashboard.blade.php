<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pengajuan Skripsi Online</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 font-sans antialiased text-slate-800">

    @php
        $unreadNotifications = \App\Models\Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();
        $unreadCount = $unreadNotifications->count();
    @endphp

    <div class="min-h-screen relative overflow-hidden">
        <div id="sidebarBackdrop" class="fixed inset-0 z-20 hidden bg-slate-900/50 md:hidden" onclick="closeSidebar()"></div>

        <div id="sidebar" class="fixed inset-y-0 left-0 z-30 w-72 max-w-[18rem] bg-slate-900 text-white flex flex-col justify-between h-full border-r border-slate-900 shadow-xl transform -translate-x-full md:translate-x-0 transition-all duration-300 ease-out">
            
            <div class="p-5 flex-1 overflow-y-auto space-y-7 scrollbar-thin scrollbar-thumb-slate-800">
                
                <div class="flex items-center justify-between pb-5 border-b border-slate-800/80 transition-all duration-200 brand-panel">
                    <div class="inline-flex items-center gap-3 brand-panel-inner">
                        <div class="brand-icon bg-blue-600 p-2.5 rounded-3xl shadow-md shadow-blue-900/30 ring-4 ring-blue-600/10 transition">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            </svg>
                        </div>
                        <div class="brand-text leading-tight">
                            <h2 class="font-bold text-sm tracking-wide text-slate-100">Skripsi Online</h2>
                            <p class="text-[10px] text-blue-300 uppercase tracking-widest mt-0.5">POLIMDO</p>
                        </div>
                    </div>
                    <button id="sidebarToggle" aria-label="Toggle sidebar" class="sidebar-toggle-btn inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-800 text-slate-200 hover:bg-slate-700 transition">
                        <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                </div>

                <nav class="space-y-1">
                    @if(Auth::user()->role == 'mahasiswa')
                        <a href="{{ route('mahasiswa.dashboard') }}" title="Dashboard" class="sidebar-menu-item flex items-center space-x-3 px-4 py-3 rounded-2xl {{ Request::routeIs('mahasiswa.dashboard') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }} text-sm transition-all duration-200">
                            <span class="text-base">🏠</span>
                            <span class="sidebar-label">Dashboard</span>
                        </a>
                        <a href="{{ route('mahasiswa.submit') }}" title="Submit Judul" class="sidebar-menu-item flex items-center space-x-3 px-4 py-3 rounded-2xl {{ Request::routeIs('mahasiswa.submit') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }} text-sm transition-all duration-200">
                            <span class="text-base">✍️</span>
                            <span class="sidebar-label">Submit Judul</span>
                        </a>
                        <a href="{{ route('mahasiswa.status') }}" title="Status Pengajuan" class="sidebar-menu-item flex items-center space-x-3 px-4 py-3 rounded-2xl {{ Request::routeIs('mahasiswa.status') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }} text-sm transition-all duration-200">
                            <span class="text-base">📊</span>
                            <span class="sidebar-label">Status Pengajuan</span>
                        </a>
                        <a href="{{ route('mahasiswa.riwayat') }}" title="Riwayat Pengajuan" class="sidebar-menu-item flex items-center space-x-3 px-4 py-3 rounded-2xl {{ Request::routeIs('mahasiswa.riwayat') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }} text-sm transition-all duration-200">
                            <span class="text-base">🕘</span>
                            <span class="sidebar-label">Riwayat Pengajuan</span>
                        </a>
                    @elseif(Auth::user()->role == 'dosen')
                        <a href="{{ route('dosen.dashboard') }}" title="Dashboard" class="sidebar-menu-item flex items-center space-x-3 px-4 py-3 rounded-2xl {{ Request::routeIs('dosen.dashboard') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }} text-sm transition-all duration-200">
                            <span class="text-base">🏠</span>
                            <span class="sidebar-label">Dashboard</span>
                        </a>
                        <a href="{{ route('dosen.received') }}" title="Pengajuan Masuk" class="sidebar-menu-item flex items-center space-x-3 px-4 py-3 rounded-2xl {{ Request::routeIs('dosen.received') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }} text-sm transition-all duration-200">
                            <span class="text-base">📥</span>
                            <span class="sidebar-label">Pengajuan Masuk</span>
                        </a>
                        <a href="{{ route('dosen.riwayat') }}" title="Riwayat Review" class="sidebar-menu-item flex items-center space-x-3 px-4 py-3 rounded-2xl {{ Request::routeIs('dosen.riwayat') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }} text-sm transition-all duration-200">
                            <span class="text-base">📚</span>
                            <span class="sidebar-label">Riwayat Review</span>
                        </a>
                    @elseif(Auth::user()->role == 'admin')
                        <a href="{{ route('admin.dashboard') }}" title="Dashboard" class="sidebar-menu-item flex items-center space-x-3 px-4 py-3 rounded-2xl {{ Request::routeIs('admin.dashboard') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }} text-sm transition-all duration-200">
                            <span class="text-base">🏠</span>
                            <span class="sidebar-label">Dashboard</span>
                        </a>
                        <a href="{{ route('admin.mahasiswa') }}" title="Data Mahasiswa" class="sidebar-menu-item flex items-center space-x-3 px-4 py-3 rounded-2xl {{ Request::routeIs('admin.mahasiswa') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }} text-sm transition-all duration-200">
                            <span class="text-base">🎓</span>
                            <span class="sidebar-label">Data Mahasiswa</span>
                        </a>
                        <a href="{{ route('admin.dosen') }}" title="Data Dosen" class="sidebar-menu-item flex items-center space-x-3 px-4 py-3 rounded-2xl {{ Request::routeIs('admin.dosen') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }} text-sm transition-all duration-200">
                            <span class="text-base">👨‍🏫</span>
                            <span class="sidebar-label">Data Dosen</span>
                        </a>
                        <a href="{{ route('admin.received') }}" title="Pengajuan Masuk" class="sidebar-menu-item flex items-center space-x-3 px-4 py-3 rounded-2xl {{ Request::routeIs('admin.received') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100' }} text-sm transition-all duration-200">
                            <span class="text-base">📥</span>
                            <span class="sidebar-label">Pengajuan Masuk</span>
                        </a>
                    @endif
                </nav>
            </div>

            <div class="p-5 border-t border-slate-800/80 space-y-1 shrink-0 bg-slate-900 z-10 shadow-[0_-8px_24px_rgba(15,23,42,0.3)] sidebar-footer">
                <a href="{{ route('profile.show') }}" class="group flex items-center space-x-3 px-4 py-3 rounded-2xl text-slate-400 hover:bg-slate-800/60 hover:text-slate-100 text-sm font-medium transition-all duration-200">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-100 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="sidebar-label">Profile</span>
                </a>

                <button id="logoutTrigger" type="button" class="group w-full flex items-center space-x-3 px-4 py-3 rounded-2xl text-slate-400 hover:bg-red-950/30 hover:text-red-400 text-sm font-medium transition-all duration-200">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-red-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="sidebar-label">Leave</span>
                </button>
            </div>
        </div>

        <div class="content-wrapper md:pl-72 min-h-screen">
            <div class="flex flex-col min-h-screen">
                <header class="bg-white border-b border-slate-200/80 h-16 flex items-center justify-between px-4 sm:px-8 shrink-0 shadow-sm shadow-slate-100/40">
                            <div class="flex items-center gap-3">
                            <button id="sidebarToggleTop" class="md:hidden flex items-center justify-center h-11 w-11 rounded-2xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            </button>
                            <div class="flex items-center gap-3">
                                <div class="w-1.5 h-5 bg-blue-600 rounded-full"></div>
                                <div>
                                    <h3 class="font-bold text-base text-slate-800 tracking-tight">@yield('page_title', 'Dashboard')</h3>
                                    <p class="text-[11px] text-slate-500 mt-1 hidden md:block">@yield('page_subtitle', 'Pantau perkembangan pengajuan skripsi secara real-time.')</p>
                                </div>
                            </div>
                        </div>

                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <button id="notificationsToggle" class="relative inline-flex items-center justify-center h-11 w-11 rounded-2xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0h6z"/></svg>
                                @if($unreadCount > 0)
                                    <span class="absolute -top-1 -right-1 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-rose-500 px-1.5 text-[10px] font-bold text-white">{{ $unreadCount }}</span>
                                @endif
                            </button>
                            <div id="notificationsPanel" class="hidden absolute right-0 top-full z-30 mt-3 w-80 max-w-[22rem] rounded-3xl border border-slate-200 bg-white shadow-2xl ring-1 ring-slate-900/5 overflow-hidden">
                                <div class="px-4 py-4 border-b border-slate-100 bg-slate-50">
                                    <h4 class="text-sm font-bold text-slate-700">Notifikasi Terbaru</h4>
                                    <p class="text-xs text-slate-400 mt-1">Hanya notifikasi yang belum dibaca ditampilkan di sini.</p>
                                </div>
                                <div class="max-h-72 overflow-y-auto">
                                    @forelse($unreadNotifications as $notification)
                                        <div class="px-4 py-3 border-b border-slate-100 hover:bg-slate-50 transition">
                                            <p class="text-[10px] uppercase tracking-[0.2em] font-semibold text-slate-400">{{ $notification->title }}</p>
                                            <p class="text-sm text-slate-700 mt-1">{{ $notification->message }}</p>
                                            <p class="text-[11px] text-slate-400 mt-2">{{ $notification->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                    @empty
                                        <div class="px-4 py-4 text-sm text-slate-500">Tidak ada notifikasi baru.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-slate-700 tracking-wide">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-widest mt-0.5">{{ Auth::user()->role }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-50 to-slate-100 text-blue-600 flex items-center justify-center font-black text-xs border border-blue-100 shadow-inner uppercase tracking-wider">
                            @php
                                $cleanName = str_replace(',', ' ', Auth::user()->name);
                                $titles = ['s.kom', 'm.cs', 'm.kom', 'm.t', 's.t', 'dr.', 'prof.', 'ph.d', 's.pd', 'm.pd'];
                                $words = explode(' ', $cleanName);
                                $filteredWords = array_values(array_filter($words, function($word) use ($titles) {
                                    return !in_array(strtolower(trim($word)), $titles) && trim($word) !== '';
                                }));

                                if (count($filteredWords) > 1) {
                                    $initials = substr($filteredWords[0], 0, 1) . substr(end($filteredWords), 0, 1);
                                } elseif (count($filteredWords) === 1) {
                                    $initials = substr($filteredWords[0], 0, 2);
                                } else {
                                    $initials = substr(Auth::user()->name, 0, 2);
                                }
                            @endphp
                            {{ strtoupper($initials) }}
                        </div>
                    </div>
                </header>

                <div class="flex-1 overflow-y-auto bg-slate-50/90">
                    <main class="p-4 sm:p-8 max-w-[1600px] mx-auto w-full">
                        @yield('main_content')
                    </main>
                </div>
            </div>
        </div>
    </div>

    <div id="logoutModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/50 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-[2rem] bg-white shadow-2xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-rose-100 text-rose-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Yakin ingin logout?</h3>
                <p class="text-sm text-slate-500 mt-2">Semua progress akan tetap tersimpan di akun Anda.</p>
            </div>
            <div class="flex items-center justify-between gap-3 border-t border-slate-200 p-4">
                <button type="button" onclick="closeLogoutModal()" class="flex-1 rounded-2xl border border-slate-200 bg-white py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">Batal</button>
                <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full rounded-2xl bg-rose-600 py-3 text-sm font-semibold text-white hover:bg-rose-700 transition">Keluar Sekarang</button>
                </form>
            </div>
        </div>
    </div>

    <style>
        #sidebar {
            background: radial-gradient(circle at top, rgba(59,130,246,.18), transparent 40%), linear-gradient(180deg, #020617 0%, #111827 100%);
            border-right-color: rgba(148,163,184,.12);
        }
        #sidebar .brand-icon {
            min-width: 2.75rem;
            min-height: 2.75rem;
        }
        #sidebar .sidebar-menu-item {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding-left: 1.1rem;
            padding-right: 1.1rem;
            border-radius: 1.25rem;
            transition: background-color .25s ease, transform .25s ease, box-shadow .25s ease;
        }
        #sidebar .sidebar-menu-item:hover {
            background-color: rgba(148,163,184,.08);
            transform: translateX(2px);
        }
        #sidebar .sidebar-menu-item span.text-base {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 1rem;
            background: rgba(255,255,255,.08);
            color: #e2e8f0;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,.06);
        }
        #sidebar.sidebar-collapsed {
            width: 5.5rem !important;
        }
        #sidebar.sidebar-collapsed .sidebar-label,
        #sidebar.sidebar-collapsed .brand-text {
            display: none !important;
        }
        #sidebar.sidebar-collapsed .sidebar-footer {
            opacity: 0;
            visibility: hidden;
            transform: translateY(8px);
            pointer-events: none;
        }
        #sidebar.sidebar-collapsed .brand-panel,
        #sidebar.sidebar-collapsed .brand-panel-inner {
            justify-content: center !important;
        }
        #sidebar.sidebar-collapsed .brand-icon {
            margin-right: 0 !important;
        }
        #sidebar.sidebar-collapsed nav a {
            justify-content: center !important;
            padding-left: 0.95rem !important;
            padding-right: 0.95rem !important;
        }
        #sidebar.sidebar-collapsed nav a span.sidebar-label {
            display: none !important;
        }
        #sidebar.sidebar-collapsed nav a span.text-base {
            margin: 0 !important;
        }
        #sidebar.sidebar-collapsed .sidebar-toggle-btn {
            margin-left: auto !important;
        }
        #sidebar.sidebar-open {
            transform: translateX(0) !important;
        }
        #sidebar.sidebar-collapsed .sidebar-toggle-btn svg {
            transform: rotate(180deg);
        }
        .content-wrapper {
            transition: padding .25s ease;
        }
        #sidebar.sidebar-collapsed ~ .content-wrapper {
            padding-left: 5.5rem !important;
        }
        #sidebar.sidebar-open ~ .content-wrapper {
            padding-left: 0 !important;
        }
    </style>

    <script>
        function toggleSidebar(force) {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const isDesktop = window.innerWidth >= 768;

            if (typeof force === 'boolean') {
                if (force) {
                    sidebar.classList.add(isDesktop ? 'sidebar-collapsed' : 'sidebar-open');
                } else {
                    sidebar.classList.remove(isDesktop ? 'sidebar-collapsed' : 'sidebar-open');
                }
            } else if (isDesktop) {
                sidebar.classList.toggle('sidebar-collapsed');
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.classList.toggle('sidebar-open');
            }

            backdrop.classList.toggle('hidden', !sidebar.classList.contains('sidebar-open'));
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            sidebar.classList.remove('sidebar-open');
            backdrop.classList.add('hidden');
        }

        document.getElementById('sidebarToggleTop').addEventListener('click', function () {
            toggleSidebar();
        });

        document.getElementById('sidebarToggle').addEventListener('click', function () {
            toggleSidebar();
        });

        document.getElementById('notificationsToggle').addEventListener('click', function (event) {
            const panel = document.getElementById('notificationsPanel');
            panel.classList.toggle('hidden');
            event.stopPropagation();
        });

        document.addEventListener('click', function (event) {
            const panel = document.getElementById('notificationsPanel');
            const button = document.getElementById('notificationsToggle');
            if (!panel.contains(event.target) && !button.contains(event.target)) {
                panel.classList.add('hidden');
            }
        });

        document.getElementById('logoutTrigger').addEventListener('click', function () {
            document.getElementById('logoutModal').classList.remove('hidden');
        });

        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.add('hidden');
        }

        window.addEventListener('resize', function () {
            if (window.innerWidth >= 768) {
                document.getElementById('sidebarBackdrop').classList.add('hidden');
            }
        });
    </script>
</body>
</html>