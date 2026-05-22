@extends('layouts.dashboard')

@section('page_title', 'Kelola Data Mahasiswa')

@section('main_content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-xl font-bold text-slate-800">Data Mahasiswa</h3>
        <p class="text-xs text-slate-400 mt-1">Daftar mahasiswa terdaftar di dalam sistem pengajuan skripsi digital.</p>
    </div>
    <button onclick="toggleMhsModal(true)" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
        <span>+ Tambah Mahasiswa</span>
    </button>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider">
                    <th class="py-4 px-6 text-center w-16">No</th>
                    <th class="py-4 px-6">Nama Mahasiswa</th>
                    <th class="py-4 px-6">NIM</th>
                    <th class="py-4 px-6">Email Akun</th>
                    <th class="py-4 px-6 text-center w-16">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-slate-600 divide-y divide-slate-100">
                @forelse($mahasiswas as $index => $mhs)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-4 px-6 text-center text-slate-400 font-medium">{{ $index + 1 }}</td>
                        <td class="py-4 px-6 font-bold text-slate-800">{{ $mhs->name }}</td>
                        <td class="py-4 px-6 text-slate-500 font-mono text-xs">{{ $mhs->mahasiswaProfile->nim ?? '-' }}</td>
                        <td class="py-4 px-6 text-slate-500">{{ $mhs->email }}</td>
                        <td class="py-4 px-6 text-center flex justify-center space-x-2">
                            <button onclick="openEditMhsModal('{{ $mhs->id }}', '{{ addslashes($mhs->name) }}', '{{ $mhs->email }}', '{{ $mhs->mahasiswaProfile->nim ?? '' }}')" class="p-1.5 bg-amber-50 border border-amber-200 text-amber-600 rounded-lg hover:bg-amber-100 transition" title="Ubah Data">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            
                            <form action="{{ route('admin.mahasiswa.delete', $mhs->id) }}" method="POST" onsubmit="return confirm('Hapus data mahasiswa ini? Seluruh berkas pengajuannya akan ikut terhapus.')">
                                @csrf
                                <button type="submit" class="p-1.5 bg-rose-50 border border-rose-200 text-rose-600 rounded-lg hover:bg-rose-100 transition" title="Hapus Permanen">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-slate-400">Belum ada mahasiswa terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 bg-slate-50 border-t border-slate-100">
        {{ $mahasiswas->links() }}
    </div>
</div>

<div id="mhsModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 w-full max-w-md shadow-xl overflow-hidden p-6">
        <div class="flex justify-between items-center mb-4 pb-2 border-b">
            <h4 class="text-sm font-bold text-slate-800 uppercase">Tambah Mahasiswa Baru</h4>
            <button onclick="toggleMhsModal(false)" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>
        <form action="{{ route('admin.mahasiswa.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">NAMA LENGKAP</label>
                <input type="text" name="name" required class="w-full px-4 py-2 rounded-xl border text-sm focus:outline-none focus:border-blue-600">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">NIM</label>
                <input type="number" name="nim" required class="w-full px-4 py-2 rounded-xl border text-sm focus:outline-none focus:border-blue-600">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">EMAIL MAHASISWA</label>
                <input type="email" name="email" required class="w-full px-4 py-2 rounded-xl border text-sm focus:outline-none focus:border-blue-600">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">PASSWORD DEFAULT</label>
                <input type="password" name="password" required class="w-full px-4 py-2 rounded-xl border text-sm focus:outline-none focus:border-blue-600">
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="toggleMhsModal(false)" class="px-4 py-2 bg-slate-100 text-slate-600 text-xs rounded-xl">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded-xl shadow-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleMhsModal(show) {
        const modal = document.getElementById('mhsModal');
        if(show) modal.classList.remove('hidden');
        else modal.classList.add('hidden');
    }
</script>

<div id="editMhsModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-100 w-full max-w-md shadow-xl overflow-hidden p-6">
        <div class="flex justify-between items-center mb-4 pb-2 border-b">
            <h4 class="text-sm font-bold text-slate-800 uppercase">Ubah Data Mahasiswa</h4>
            <button onclick="toggleEditMhsModal(false)" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>
        <form id="editMhsForm" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">NAMA LENGKAP</label>
                <input type="text" id="edit_mhs_name" name="name" required class="w-full px-4 py-2 rounded-xl border text-sm focus:outline-none focus:border-blue-600">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">NIM (BILANGAN BULAT)</label>
                <input type="number" id="edit_mhs_nim" name="nim" required class="w-full px-4 py-2 rounded-xl border text-sm focus:outline-none focus:border-blue-600">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">EMAIL MAHASISWA</label>
                <input type="email" id="edit_mhs_email" name="email" required class="w-full px-4 py-2 rounded-xl border text-sm focus:outline-none focus:border-blue-600">
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="toggleEditMhsModal(false)" class="px-4 py-2 bg-slate-100 text-slate-600 text-xs rounded-xl">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded-xl shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditMhsModal(id, name, email, nim) {
        document.getElementById('editMhsForm').action = '/admin/mahasiswa/update/' + id;
        document.getElementById('edit_mhs_name').value = name;
        document.getElementById('edit_mhs_email').value = email;
        document.getElementById('edit_mhs_nim').value = nim;
        toggleEditMhsModal(true);
    }

    function toggleEditMhsModal(show) {
        const modal = document.getElementById('editMhsModal');
        if(show) modal.classList.remove('hidden');
        else modal.classList.add('hidden');
    }
</script>
@endsection