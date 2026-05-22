@extends('layouts.dashboard')

@section('page_title', 'Kelola Data Dosen')

@section('main_content')
@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-xl flex items-center space-x-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-xl font-bold text-slate-800">Data Dosen</h3>
        <p class="text-xs text-slate-400 mt-1">Kelola data dosen pembimbing tugas akhir program studi.</p>
    </div>
    <button onclick="toggleModal(true)" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm flex items-center space-x-2 transition">
        <span>+ Tambah Dosen</span>
    </button>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider">
                    <th class="py-4 px-6 text-center w-16">No</th>
                    <th class="py-4 px-6">Nama Dosen</th>
                    <th class="py-4 px-6">NIDN</th>
                    <th class="py-4 px-6">Email</th>
                    <th class="py-4 px-6 text-center w-16">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-slate-600 divide-y divide-slate-100">
                @forelse($dosens as $index => $dosen)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-4 px-6 text-center text-slate-400 font-medium">{{ $index + 1 }}</td>
                        <td class="py-4 px-6 font-bold text-slate-800">{{ $dosen->name }}</td>
                        <td class="py-4 px-6 text-slate-500 font-mono text-xs">{{ $dosen->dosenProfile->nidn ?? '-' }}</td>
                        <td class="py-4 px-6 text-slate-500">{{ $dosen->email }}</td>
                        <td class="py-4 px-6 text-center flex justify-center space-x-2">
                            <button onclick="openEditDosenModal('{{ $dosen->id }}', '{{ addslashes($dosen->name) }}', '{{ $dosen->email }}', '{{ $dosen->dosenProfile->nidn ?? '' }}')" class="p-1.5 bg-amber-50 border border-amber-200 text-amber-600 rounded-lg hover:bg-amber-100 transition" title="Ubah Data">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            
                            <form action="{{ route('admin.dosen.delete', $dosen->id) }}" method="POST" onsubmit="return confirm('Hapus data dosen ini permanen?')">
                                @csrf
                                <button type="submit" class="p-1.5 bg-rose-50 border border-rose-200 text-rose-600 rounded-lg hover:bg-rose-100 transition" title="Hapus Permanen">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-slate-400">Belum ada data dosen terdaftar di sistem.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 bg-slate-50 border-t border-slate-100">
        {{ $dosens->links() }}
    </div>
</div>

<div id="dosenModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 w-full max-w-md shadow-xl overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="modalContainer">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Tambah Dosen Baru</h4>
            <button onclick="toggleModal(false)" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18"/></svg>
            </button>
        </div>
        <form action="{{ route('admin.dosen.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap & Gelar</label>
                <input type="text" name="name" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">NIDN</label>
                <input type="text" name="nidn" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-mono focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat Email Resmi</label>
                <input type="email" name="email" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Password Akun</label>
                <input type="password" name="password" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
            </div>
            <div class="flex justify-end space-x-3 pt-2">
                <button type="button" onclick="toggleModal(false)" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl transition shadow-sm">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(show) {
        const modal = document.getElementById('dosenModal');
        const container = document.getElementById('modalContainer');
        if (show) {
            modal.classList.remove('hidden');
            setTimeout(() => {
                container.classList.remove('scale-95', 'opacity-0');
                container.classList.add('scale-100', 'opacity-100');
            }, 50);
        } else {
            container.classList.remove('scale-100', 'opacity-100');
            container.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    }
</script>

<div id="editDosenModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 w-full max-w-md shadow-xl overflow-hidden p-6">
        <div class="flex justify-between items-center mb-4 pb-2 border-b">
            <h4 class="text-sm font-bold text-slate-800 uppercase">Ubah Data Dosen</h4>
            <button onclick="toggleEditDosenModal(false)" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>
        <form id="editDosenForm" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">NAMA LENGKAP & GELAR</label>
                <input type="text" id="edit_dosen_name" name="name" required class="w-full px-4 py-2 rounded-xl border text-sm focus:outline-none focus:border-blue-600">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">NIDN</label>
                <input type="text" id="edit_dosen_nidn" name="nidn" required class="w-full px-4 py-2 rounded-xl border text-sm focus:outline-none focus:border-blue-600">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">EMAIL RESMI</label>
                <input type="email" id="edit_dosen_email" name="email" required class="w-full px-4 py-2 rounded-xl border text-sm focus:outline-none focus:border-blue-600">
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="toggleEditDosenModal(false)" class="px-4 py-2 bg-slate-100 text-slate-600 text-xs rounded-xl">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded-xl shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditDosenModal(id, name, email, nidn) {
        document.getElementById('editDosenForm').action = '/admin/dosen/update/' + id;
        document.getElementById('edit_dosen_name').value = name;
        document.getElementById('edit_dosen_email').value = email;
        document.getElementById('edit_dosen_nidn').value = nidn;
        toggleEditDosenModal(true);
    }

    function toggleEditDosenModal(show) {
        const modal = document.getElementById('editDosenModal');
        if(show) modal.classList.remove('hidden');
        else modal.classList.add('hidden');
    }
</script>
@endsection