@extends('layouts.dashboard-instansi')

@section('title', 'Perbaiki Pengajuan Kausa - ' . $kausa->judul)
@section('topbar-title', 'Perbaikan Pengajuan Kausa')
@section('topbar-subtitle', 'Kabupaten Tulungagung • Sistem Monitoring Akuntabilitas Bantuan Kemasyarakatan')

@section('content')
<div class="bg-[#F6F8F7] py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Breadcrumb & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-[#D9E2DE]">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#73817C] mb-2" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard.instansi') }}" class="hover:text-[#087F5B] transition-colors">Dashboard Instansi</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                    <a href="{{ route('dashboard.instansi.detail', $kausa->id) }}" class="hover:text-[#087F5B] transition-colors">Detail Kausa</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span class="text-[#17211E] font-medium">Perbaikan</span>
                </nav>
                <div class="flex items-center gap-3">
                    <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-[#17211E] tracking-tight">
                        Perbaiki Pengajuan Kausa
                    </h1>
                    @if($kausa->status === 'perlu_diperbaiki')
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800 border border-amber-300">
                            Perlu Perbaikan
                        </span>
                    @else
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700 border border-slate-300">
                            Draf
                        </span>
                    @endif
                </div>
                <p class="text-xs sm:text-sm text-[#52615C] mt-1 max-w-3xl">
                    Perbarui rincian kausa sosial Anda sesuai catatan verifikator Admin Pemkab Tulungagung agar dapat segera ditinjau kembali dan dipublikasikan.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard.instansi.detail', $kausa->id) }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition-all shadow-xs">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span>Kembali ke Detail</span>
                </a>
            </div>
        </div>

        <!-- Alert Catatan Perbaikan Resmi dari Admin Pemkab -->
        @if($catatanRevisi || $kausa->status === 'perlu_diperbaiki')
            <div class="rounded-2xl bg-amber-50/90 border-2 border-amber-300 p-5 shadow-xs">
                <div class="flex items-start gap-3">
                    <div class="p-2 rounded-xl bg-amber-100 text-amber-800 shrink-0 mt-0.5">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <h3 class="font-heading font-bold text-sm text-amber-900">
                                Catatan Perbaikan dari Tim Verifikator Pemkab Tulungagung
                            </h3>
                            <span class="text-[11px] font-medium text-amber-700">
                                Status: Perlu Direvisi Sebelum Disetujui
                            </span>
                        </div>
                        <div class="mt-2 p-3 bg-white/80 rounded-xl border border-amber-200 text-xs text-amber-950 leading-relaxed font-mono">
                            {{ $catatanRevisi ?? $kausa->catatan_admin ?? 'Silakan lengkapi dokumen pendukung dan sesuaikan estimasi target dana sesuai ketentuan Pemkab.' }}
                        </div>
                        <p class="text-[11px] text-amber-800 mt-2">
                            <strong>Petunjuk:</strong> Lakukan perbaikan pada form di bawah ini, jelaskan langkah perbaikan pada kolom catatan, lalu klik tombol <em>"Ajukan Kembali ke Admin Pemkab"</em>.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('status'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs flex items-center gap-2 font-medium">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-xs space-y-1">
                <div class="font-bold flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i>
                    <span>Terdapat kesalahan pengisian formulir:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 pl-2 text-rose-800">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Edit & Resubmit Kausa -->
        <form action="{{ route('dashboard.instansi.update', $kausa->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- SECTION 1: Informasi Pokok Kausa -->
            <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 sm:p-7 shadow-xs space-y-5">
                <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                    <span class="p-2 rounded-xl bg-emerald-50 text-[#087F5B]">
                        <i data-lucide="edit-3" class="w-5 h-5"></i>
                    </span>
                    <div>
                        <h2 class="font-heading font-bold text-base text-[#123B32]">Informasi Pokok Kausa</h2>
                        <p class="text-xs text-[#52615C]">Identitas kegiatan sosial dan target penerima manfaat di Kabupaten Tulungagung.</p>
                    </div>
                </div>

                <!-- Judul -->
                <div>
                    <label for="judul" class="block text-xs font-bold text-slate-800 mb-1.5">
                        Judul Kausa Sosial <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="judul" name="judul" required value="{{ old('judul', $kausa->judul) }}"
                           placeholder="Contoh: Bantuan Rehabilitasi Rumah Korban Longsor Sendang"
                           class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#087F5B] focus:border-[#087F5B]">
                    @error('judul') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Kategori -->
                    <div>
                        <label for="kategori_kausa_id" class="block text-xs font-bold text-slate-800 mb-1.5">
                            Kategori Kausa <span class="text-rose-500">*</span>
                        </label>
                        <select id="kategori_kausa_id" name="kategori_kausa_id" required
                                class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#087F5B]">
                            <option value="">Pilih Kategori Kausa...</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}" {{ (string) old('kategori_kausa_id', $kausa->kategori_kausa_id) === (string) $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_kausa_id') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label for="lokasi" class="block text-xs font-bold text-slate-800 mb-1.5">
                            Lokasi Kegiatan / Wilayah di Tulungagung <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="lokasi" name="lokasi" required value="{{ old('lokasi', $kausa->lokasi) }}"
                               placeholder="Contoh: Desa Nglurup, Kec. Sendang, Kab. Tulungagung"
                               class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#087F5B]">
                        @error('lokasi') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Target Dana -->
                    <div>
                        <label for="target_dana" class="block text-xs font-bold text-slate-800 mb-1.5">
                            Target Dana (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-bold text-slate-500">Rp</span>
                            <input type="text" id="target_dana" name="target_dana" required
                                   value="{{ old('target_dana', number_format((float) $kausa->target_dana, 0, ',', '.')) }}"
                                   placeholder="50.000.000"
                                   class="w-full pl-10 pr-3.5 py-2.5 text-sm bg-white border border-slate-300 rounded-xl text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-[#087F5B]">
                        </div>
                        @error('target_dana') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tanggal Mulai -->
                    <div>
                        <label for="tanggal_mulai" class="block text-xs font-bold text-slate-800 mb-1.5">
                            Tanggal Mulai
                        </label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                               value="{{ old('tanggal_mulai', $kausa->tanggal_mulai?->format('Y-m-d')) }}"
                               class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#087F5B]">
                        @error('tanggal_mulai') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tanggal Berakhir -->
                    <div>
                        <label for="tanggal_berakhir" class="block text-xs font-bold text-slate-800 mb-1.5">
                            Tanggal Berakhir (Batas Penggalangan)
                        </label>
                        <input type="date" id="tanggal_berakhir" name="tanggal_berakhir"
                               value="{{ old('tanggal_berakhir', $kausa->tanggal_berakhir?->format('Y-m-d')) }}"
                               class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#087F5B]">
                        @error('tanggal_berakhir') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Ringkasan -->
                <div>
                    <label for="ringkasan" class="block text-xs font-bold text-slate-800 mb-1.5">
                        Ringkasan Singkat (Muncul di Katalog)
                    </label>
                    <textarea id="ringkasan" name="ringkasan" rows="2" maxlength="1000"
                              placeholder="Deskripsi singkat maksimal 2-3 kalimat mengenai urgensi bantuan..."
                              class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#087F5B]">{{ old('ringkasan', $kausa->ringkasan) }}</textarea>
                    @error('ringkasan') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Deskripsi Lengkap -->
                <div>
                    <label for="deskripsi" class="block text-xs font-bold text-slate-800 mb-1.5">
                        Deskripsi Lengkap &amp; Rencana Penyaluran Bantuan <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="deskripsi" name="deskripsi" rows="6" required
                              placeholder="Uraikan latar belakang masalah, profil penerima manfaat, rincian estimasi penggunaan dana, serta jadwal penyaluran..."
                              class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#087F5B] leading-relaxed">{{ old('deskripsi', $kausa->deskripsi) }}</textarea>
                    @error('deskripsi') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- SECTION 2: Dokumen Pendukung & Legalitas Kegiatan -->
            <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 sm:p-7 shadow-xs space-y-5">
                <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
                    <span class="p-2 rounded-xl bg-emerald-50 text-[#087F5B]">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                    </span>
                    <div>
                        <h2 class="font-heading font-bold text-base text-[#123B32]">Dokumen Pendukung &amp; Proposal</h2>
                        <p class="text-xs text-[#52615C]">Proposal kegiatan, RAB detail, foto kondisi lapangan, atau surat pengantar kelurahan/desa.</p>
                    </div>
                </div>

                <!-- Dokumen yang sudah ada -->
                @if($kausa->dokumen && $kausa->dokumen->count() > 0)
                    <div>
                        <h3 class="text-xs font-bold text-slate-700 mb-2">Dokumen Terunggah Saat Ini:</h3>
                        <div class="space-y-2">
                            @foreach($kausa->dokumen as $dok)
                                <div class="flex items-center justify-between p-3 rounded-xl border border-slate-200 bg-slate-50 text-xs">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <i data-lucide="file" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                                        <div class="truncate">
                                            <p class="font-semibold text-slate-800 truncate">{{ $dok->nama_file }}</p>
                                            <p class="text-[11px] text-slate-500">
                                                {{ number_format(($dok->ukuran_file ?? 0) / 1024, 1) }} KB &bull; {{ $dok->created_at?->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <label class="flex items-center gap-1.5 text-rose-600 hover:text-rose-700 font-semibold cursor-pointer shrink-0 ml-4">
                                        <input type="checkbox" name="hapus_dokumen[]" value="{{ $dok->id }}" class="rounded text-rose-600 focus:ring-rose-500">
                                        <span>Hapus</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1.5 italic">Centang "Hapus" pada dokumen yang ingin Anda ganti atau buang.</p>
                    </div>
                @endif

                <!-- Upload Dokumen Baru -->
                <div>
                    <label for="dokumen" class="block text-xs font-bold text-slate-800 mb-1.5">
                        Unggah Dokumen Tambahan / Dokumen Hasil Revisi
                    </label>
                    <input type="file" id="dokumen" name="dokumen[]" multiple accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full text-xs text-slate-700 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-700 file:text-white hover:file:bg-emerald-800 cursor-pointer border border-slate-300 rounded-xl p-1 bg-white">
                    <p class="text-[11px] text-slate-600 mt-1">Mendukung file PDF, JPG, PNG hingga 5MB per dokumen. Anda dapat memilih beberapa file sekaligus.</p>
                    @error('dokumen.*') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- SECTION 3: Catatan Penjelasan Perbaikan dari Instansi -->
            <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 sm:p-7 shadow-xs space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                    <span class="p-2 rounded-xl bg-emerald-50 text-[#087F5B]">
                        <i data-lucide="message-square" class="w-5 h-5"></i>
                    </span>
                    <div>
                        <h2 class="font-heading font-bold text-base text-[#123B32]">Catatan Tindak Lanjut Perbaikan</h2>
                        <p class="text-xs text-[#52615C]">Berikan penjelasan singkat mengenai perbaikan yang telah dilakukan untuk mempermudah verifikasi tim Pemkab.</p>
                    </div>
                </div>

                <div>
                    <textarea id="catatan_perbaikan" name="catatan_perbaikan" rows="3" maxlength="1000"
                              placeholder="Contoh: Kami telah melampirkan proposal revisi beserta surat rekomendasi Dinsos Kecamatan Sendang sesuai arahan verifikator..."
                              class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#087F5B]">{{ old('catatan_perbaikan') }}</textarea>
                    <p class="text-[11px] text-slate-500 mt-1">Catatan ini akan tersimpan di riwayat status kausa dan langsung terbaca oleh Admin Pemkab Tulungagung.</p>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                <div class="text-xs text-[#73817C]">
                    Pastikan seluruh data telah sesuai sebelum mengirim kembali pengajuan.
                </div>

                <div class="flex items-center gap-2.5 w-full sm:w-auto">
                    <button type="submit" name="action" value="draft"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition-all active:scale-95">
                        <i data-lucide="save" class="w-4 h-4 text-slate-500"></i>
                        <span>Simpan Perubahan (Draf)</span>
                    </button>

                    <button type="submit" name="action" value="submit"
                            onclick="return confirm('Apakah Anda yakin data telah diperbaiki dan siap diajukan kembali ke Admin Pemkab Tulungagung?')"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#087F5B] hover:bg-[#066A4C] text-white text-xs font-semibold shadow-sm transition-all active:scale-95">
                        <i data-lucide="send" class="w-4 h-4 text-white"></i>
                        <span>Ajukan Kembali ke Admin Pemkab</span>
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection
