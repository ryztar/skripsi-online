@extends('layouts.dashboard')

@section('page_title', 'Review Pengajuan Masuk')

@section('main_content')
<div class="space-y-8">
    
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h4 class="text-lg font-bold text-slate-800">Daftar Pengajuan Judul yang Perlu Direview</h4>
        </div>
        <div class="overflow-x-auto">
            <form action="" method="GET" class="mt-4 mb-4 ml-6 flex space-x-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama pengusul atau kata kunci judul..." class="w-full max-w-sm px-4 py-2 text-xs border rounded-xl focus:outline-none focus:border-blue-600 transition">
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white font-bold text-xs rounded-xl">Cari</button>
            </form>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider">
                        <th class="py-4 px-6">Mahasiswa</th>
                        <th class="py-4 px-6">Usulan Judul</th>
                        <th class="py-4 px-6">Tanggal Masuk</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-600 divide-y divide-slate-100">
                    @forelse($submissions as $sub)
                        <tr>
                            <td class="py-4 px-6">
                                <p class="font-semibold text-slate-800">{{ $sub->user->name }}</p>
                                <p class="text-xs text-slate-400">{{ $sub->user->mahasiswaProfile->nim ?? '-' }}</p>
                            </td>
                            <td class="py-4 px-6 font-medium text-slate-800 max-w-xs truncate">{{ $sub->judul }}</td>
                            <td class="py-4 px-6">{{ $sub->updated_at->format('d M Y') }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-lg uppercase
                                    {{ $sub->status == 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : '' }}
                                    {{ $sub->status == 'submitted' ? 'bg-blue-50 text-blue-700 border border-blue-100' : '' }}
                                    {{ $sub->status == 'review' ? 'bg-amber-50 text-amber-700 border border-amber-100' : '' }}
                                    {{ $sub->status == 'revisi' ? 'bg-red-50 text-red-700 border border-red-100' : '' }}
                                    {{ $sub->status == 'rejected' ? 'bg-rose-50 text-rose-700 border border-rose-100' : '' }}
                                ">
                                    {{ $sub->status }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <button type="button"
                                        onclick="openReviewModal(this)"
                                        data-id="{{ $sub->id }}"
                                        data-nama="{{ $sub->user->name }}"
                                        data-nim="{{ $sub->user->mahasiswaProfile->nim ?? '-' }}"
                                        data-judul="{{ $sub->judul }}"
                                        data-abstrak="{{ $sub->abstrak }}"
                                        data-latar="{{ $sub->latar_belakang }}"
                                        data-referensi="{{ $sub->referensi ?? '' }}"
                                        data-file="{{ $sub->dokumen_pendukung ? asset('storage/proposals/' . $sub->dokumen_pendukung) : '' }}"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl transition shadow-sm">
                                    Review
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-slate-400">Belum ada dokumen mahasiswa yang dikirimkan ke sistem.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="adminReviewModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl border border-slate-100 w-full max-w-3xl shadow-xl overflow-hidden my-8">
            
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <div>
                    <h4 class="text-sm font-black text-slate-700 uppercase tracking-wider">Form Evaluasi Judul Skripsi</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Pengaju: <span id="modalReviewPengaju" class="font-bold text-slate-700"></span></p>
                </div>
                <button type="button" onclick="closeReviewModal()" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
            </div>
            
            <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                
                <div class="space-y-4 border-b border-slate-100 pb-6">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 tracking-wider">Judul yang Diajukan</label>
                        <p id="modalReviewJudul" class="text-xs font-bold text-slate-800 bg-slate-50 p-3 rounded-xl border border-slate-200 whitespace-pre-line"></p>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 tracking-wider">Abstrak</label>
                        <p id="modalReviewAbstrak" class="text-xs text-slate-600 bg-slate-50 p-4 rounded-xl border border-slate-200 whitespace-pre-line leading-relaxed"></p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 tracking-wider">Latar Belakang</label>
                        <p id="modalReviewLatar" class="text-xs text-slate-600 bg-slate-50 p-4 rounded-xl border border-slate-200 whitespace-pre-line leading-relaxed"></p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 tracking-wider">Referensi</label>
                        <p id="modalReviewReferensi" class="text-xs text-slate-600 bg-slate-50 p-4 rounded-xl border border-slate-200 whitespace-pre-line leading-relaxed"></p>
                    </div>

                    <div id="modalContainerFile" class="hidden">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 tracking-wider">Dokumen Proposal</label>
                        <a id="modalReviewFile" href="#" target="_blank" rel="noopener noreferrer" class="inline-flex items-center space-x-2 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 rounded-xl text-xs font-semibold transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Lihat Berkas PDF Proposal Mahasiswa</span>
                        </a>
                    </div>
                </div>

                <form id="reviewFormAction" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1 tracking-wider">Pilih Status Keputusan <span class="text-red-500">*</span></label>
                        <select id="modalSelectKeputusan" name="status" onchange="toggleKomentarRequired(this)" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-blue-600">
                            <option value="approved">APPROVE</option>
                            <option value="revisi">REVISI</option>
                            <option value="rejected">REJECT</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1 tracking-wider">
                            Catatan / Komentar Perbaikan <span id="bintangWajib" class="text-red-500 hidden">*</span>
                        </label>
                        <textarea id="modalKomentar" name="komentar" rows="3" placeholder="Tuliskan catatan apresiasi atau pesan untuk mahasiswa (Opsional)..." class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-blue-600"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2 pt-4 border-t">
                        <button type="button" onclick="closeReviewModal()" class="px-4 py-2 bg-slate-100 text-slate-600 text-xs font-bold rounded-xl">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-xl shadow-sm">Simpan Keputusan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
function openReviewModal(button) {
    // Ambil data atribut dari tombol
    const id = button.getAttribute('data-id');
    const nama = button.getAttribute('data-nama');
    const nim = button.getAttribute('data-nim');
    const judul = button.getAttribute('data-judul');
    const abstrak = button.getAttribute('data-abstrak');
    const latar = button.getAttribute('data-latar');
    const referensi = button.getAttribute('data-referensi');
    const fileUrl = button.getAttribute('data-file');

    // Set action URL form POST
    document.getElementById('reviewFormAction').action = `/dosen/review/${id}/store`;

    // Isi konten text ke dalam modal
    document.getElementById('modalReviewPengaju').innerText = nama + " (" + nim + ")";
    document.getElementById('modalReviewJudul').innerText = judul;
    document.getElementById('modalReviewAbstrak').innerText = abstrak || 'Tidak ada abstrak.';
    document.getElementById('modalReviewLatar').innerText = latar || 'Tidak ada latar belakang.';
    document.getElementById('modalReviewReferensi').innerText = referensi || 'Tidak ada referensi.';

    // Logika pengkondisian Link PDF berkas
    const containerFile = document.getElementById('modalContainerFile');
    const linkFile = document.getElementById('modalReviewFile');
    if (fileUrl && fileUrl.trim() !== "") {
        linkFile.href = fileUrl;
        containerFile.classList.remove('hidden');
    } else {
        containerFile.classList.add('hidden');
    }

    // RESET DEFAULT: Saat modal pertama buka, set ke 'approved' (Komentar TIDAK WAJIB)
    const selectKeputusan = document.getElementById('modalSelectKeputusan');
    selectKeputusan.value = 'approved';
    
    const komentarTextarea = document.getElementById('modalKomentar');
    komentarTextarea.required = false;
    komentarTextarea.value = ''; // Kosongkan ketikan sebelumnya jika ada
    komentarTextarea.placeholder = "Tuliskan catatan apresiasi atau pesan untuk mahasiswa (Opsional)...";
    
    document.getElementById('bintangWajib').classList.add('hidden');

    // Munculkan modal ke layar
    document.getElementById('adminReviewModal').classList.remove('hidden');
}

function closeReviewModal() {
    document.getElementById('adminReviewModal').classList.add('hidden');
}

function toggleKomentarRequired(selectElement) {
    const komentarTextarea = document.getElementById('modalKomentar');
    const bintangWajib = document.getElementById('bintangWajib');

    if (selectElement.value === 'approved') {
        komentarTextarea.required = false;
        bintangWajib.classList.add('hidden');
        komentarTextarea.placeholder = "Tuliskan catatan apresiasi atau pesan untuk mahasiswa (Opsional)...";
    } else {
        komentarTextarea.required = true;
        bintangWajib.classList.remove('hidden');
        komentarTextarea.placeholder = "Tulis alasan dikembalikan untuk revisi atau poin penolakan judul... (Wajib Diisi)";
    }
}
</script>
@endsection