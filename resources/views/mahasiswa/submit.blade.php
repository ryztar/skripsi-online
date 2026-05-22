@extends('layouts.dashboard')

@section('page_title', 'Submit Judul')

@section('main_content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
    <div class="mb-6 border-b border-slate-100 pb-4">
        <h4 class="text-xl font-bold text-slate-800">Form Pengajuan Judul</h4>
        <p class="text-sm text-slate-500 mt-1">Lengkapi seluruh komponen data usulan skripsi Anda di bawah ini.</p>
    </div>

    @if ($errors->has('judul'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-50 rounded-xl border border-red-200" role="alert">
            {{ $errors->first('judul') }}
        </div>
    @endif

    <form id="submissionForm" action="{{ route('mahasiswa.submit.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 relative">
        @csrf

        <div id="submitLoadingOverlay" class="hidden absolute inset-0 rounded-2xl bg-slate-900/60 backdrop-blur-sm z-20 flex items-center justify-center">
            <div class="inline-flex items-center gap-3 rounded-3xl bg-white px-5 py-4 shadow-2xl">
                <div class="h-9 w-9 rounded-full border-4 border-blue-600 border-t-transparent animate-spin"></div>
                <div class="text-sm font-semibold text-slate-700">Sedang mengirim pengajuan... Mohon tunggu.</div>
            </div>
        </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul Skripsi <span class="text-red-500">*</span></label>
                <input type="text" name="judul" required value="{{ old('judul', $submission->judul ?? '') }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition"
                    placeholder="Masukkan usulan judul skripsi lengkap">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Abstrak <span class="text-red-500">*</span></label>
                <textarea name="abstrak" rows="4" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition"
                placeholder="Tulis abstrak singkat penelitian Anda...">{{ old('abstrak', $submission->abstrak ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Latar Belakang <span class="text-red-500">*</span></label>
                <textarea name="latar_belakang" rows="5" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition"
                placeholder="Jelaskan ringkasan latar belakang masalah penelitian...">{{ old('latar_belakang', $submission->latar_belakang ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Referensi (Opsional)</label>
                <textarea name="referensi" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition"
                placeholder="Contoh: (Nama Penulis, Tahun) Jurnal Utama Pendukung">{{ old('referensi', $submission->referensi ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Dokumen Pendukung</label>
                
                @if(isset($submission) && $submission->dokumen_pendukung)
                    <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between shadow-sm animate-fade-in">
                        <div class="flex items-center space-x-3 text-xs text-emerald-800 font-medium">
                            <span class="text-lg">📄</span>
                            <div>
                                <p class="font-bold">Berkas Terunggah:</p>
                                <p class="text-slate-500 text-[11px] font-mono mt-0.5">{{ $submission->dokumen_pendukung }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ asset('storage/proposals/' . $submission->dokumen_pendukung) }}" target="_blank" class="text-[11px] bg-white px-3 py-1.5 rounded-lg border border-emerald-200 text-emerald-700 font-bold hover:bg-emerald-100 transition shadow-sm">
                                Lihat File
                            </a>
                            
                            <a href="{{ route('mahasiswa.delete_file', $submission->id) }}" 
                                type="button"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus file proposal ini?')" 
                                class="text-[11px] bg-rose-50 text-rose-600 px-3 py-1.5 rounded-lg border border-rose-200 font-bold hover:bg-rose-100 transition shadow-sm">
                                Hapus File
                            </a>
                        </div>
                    </div>
                @endif

                <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:bg-slate-50 transition relative">
                    <input type="file" name="dokumen_pendukung" id="file_input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    
                    <p id="file_status_text" class="text-sm font-medium text-slate-600">Klik atau seret file ke sini untuk mengganti berkas</p>
                    <p class="text-xs text-slate-400 mt-1">PDF (Maksimal 2MB)</p>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" id="resetFormButton" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-xl transition">Reset</button>
                
                <button type="submit" name="action" value="draft" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-xl transition">Simpan Draft</button>
                
                <button type="submit" name="action" value="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition shadow-sm">Kirim Pengajuan</button>
            </div>

            <script>
                document.getElementById('resetFormButton').addEventListener('click', function() {
                    const form = document.getElementById('submissionForm');
                    form.querySelectorAll('input[type="text"], textarea').forEach(el => el.value = '');
                    form.querySelectorAll('input[type="file"]').forEach(el => el.value = '');
                    document.getElementById('file_status_text').innerText = 'Klik atau seret file ke sini untuk mengganti berkas';
                });

                document.getElementById('file_input').addEventListener('change', function(e) {
                    const fileName = e.target.files[0] ? e.target.files[0].name : "Klik atau seret file ke sini untuk mengganti berkas";
                    const statusText = document.getElementById('file_status_text');
                    
                    if(e.target.files[0]) {
                        statusText.innerHTML = `📄 <span class="text-blue-600 font-bold">${fileName}</span> siap diunggah!`;
                    } else {
                        statusText.innerText = fileName;
                    }
                });

                document.getElementById('submissionForm').addEventListener('submit', function () {
                    document.getElementById('submitLoadingOverlay').classList.remove('hidden');
                });
            </script>
    </form>
</div>
@endsection