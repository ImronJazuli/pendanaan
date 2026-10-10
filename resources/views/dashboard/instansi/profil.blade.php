@extends('layouts.dashboard-instansi')

@section('title', 'Profil & Dokumen Legalitas')
@section('topbar-title', 'Profil & Verifikasi Legalitas Lembaga')
@section('topbar-subtitle', 'Kabupaten Tulungagung • Sistem Monitoring Akuntabilitas Bantuan Kemasyarakatan')

@section('content')
<div class="bg-[#F6F8F7] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Page Header & Breadcrumb -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-[#D9E2DE]">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#73817C] mb-2" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard.instansi') }}" class="hover:text-[#087F5B] transition-colors">Dashboard Instansi</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span class="text-[#17211E] font-medium">Profil &amp; Legalitas Lembaga</span>
                </nav>
                <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-[#17211E] tracking-tight">
                    Profil &amp; Legalitas Lembaga Sosial
                </h1>
                <p class="text-xs sm:text-sm text-[#52615C] mt-1 max-w-3xl leading-relaxed">
                    Sesuai Perbup Tulungagung terkait Akuntabilitas Donasi Kausa, seluruh lembaga non-pemerintah wajib melengkapi identitas legalitas badan hukum dan mengunggah dokumen otentik sebelum membuka penggalangan bantuan.
                </p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('kausa.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#087F5B] hover:bg-[#066A4C] text-white text-xs font-semibold shadow-sm transition-all active:scale-95">
                    <i data-lucide="plus-circle" class="w-4 h-4 text-white"></i>
                    <span>Ajukan Kausa Baru</span>
                </a>
            </div>
        </div>

        <!-- Banner Status Verifikasi & 4 Tahapan -->
        @php
            $status = $instansi->status_verifikasi ?? 'belum_diverifikasi';
        @endphp

        @if($status === 'terverifikasi')
            <div class="rounded-2xl border border-emerald-300 bg-emerald-50/90 p-5 shadow-xs relative overflow-hidden">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative z-10">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 border border-emerald-300 flex items-center justify-center text-emerald-800 shrink-0 mt-0.5">
                            <i data-lucide="shield-check" class="w-5 h-5 text-emerald-700"></i>
                        </div>
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-heading font-bold text-sm sm:text-base text-emerald-950">
                                    Dokumen Legalitas Terverifikasi Resmi Pemkab Tulungagung
                                </h3>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-200 text-emerald-900 border border-emerald-300">
                                    <i data-lucide="check" class="w-3 h-3 text-emerald-800"></i> Terverifikasi
                                </span>
                            </div>
                            <p class="text-xs sm:text-sm text-emerald-800 leading-relaxed max-w-3xl">
                                Lembaga Anda telah memenuhi seluruh persyaratan legalitas badan hukum dan terdaftar aktif di Dinas Sosial Kabupaten Tulungagung. Lembaga memiliki izin penuh untuk mengajukan kausa pendanaan publik.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($status === 'menunggu_verifikasi')
            <div class="rounded-2xl border border-amber-300 bg-amber-50/90 p-5 shadow-xs relative overflow-hidden">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative z-10">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 border border-amber-300 flex items-center justify-center text-amber-800 shrink-0 mt-0.5">
                            <i data-lucide="shield-alert" class="w-5 h-5 text-amber-700"></i>
                        </div>
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-heading font-bold text-sm sm:text-base text-amber-950">
                                    Dokumen Legalitas Sedang Ditinjau Tim Verifikator Pemkab Tulungagung
                                </h3>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-200 text-amber-900 border border-amber-300">
                                    <i data-lucide="timer" class="w-3 h-3 text-amber-800"></i> Menunggu Verifikasi
                                </span>
                            </div>
                            <p class="text-xs sm:text-sm text-amber-800 leading-relaxed max-w-3xl">
                                Tim verifikator Dinas Sosial dan Bagian Hukum Sekretariat Daerah Tulungagung sedang mencocokkan nomor AHU Kemenkumham dan dokumen kelengkapan. Estimasi waktu proses 1x24 jam kerja.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($status === 'ditolak')
            <div class="rounded-2xl border border-red-300 bg-red-50/90 p-5 shadow-xs relative overflow-hidden">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative z-10">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-red-100 border border-red-300 flex items-center justify-center text-red-800 shrink-0 mt-0.5">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-700"></i>
                        </div>
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-heading font-bold text-sm sm:text-base text-red-950">
                                    Verifikasi Dokumen Legalitas Memerlukan Perbaikan / Ditolak
                                </h3>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-200 text-red-900 border border-red-300">
                                    <i data-lucide="x" class="w-3 h-3 text-red-800"></i> Perlu Perbaikan
                                </span>
                            </div>
                            <p class="text-xs sm:text-sm text-red-800 leading-relaxed max-w-3xl">
                                Dokumen legalitas atau data lembaga belum memenuhi ketentuan verifikasi Pemkab Tulungagung. Silakan periksa kembali kelengkapan nomor registrasi dan data lembaga di bawah ini.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="rounded-2xl border border-blue-300 bg-blue-50/90 p-5 shadow-xs relative overflow-hidden">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative z-10">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 border border-blue-300 flex items-center justify-center text-blue-800 shrink-0 mt-0.5">
                            <i data-lucide="info" class="w-5 h-5 text-blue-700"></i>
                        </div>
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-heading font-bold text-sm sm:text-base text-blue-950">
                                    Lengkapi Data Identitas &amp; Dokumen Legalitas Lembaga
                                </h3>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-200 text-blue-900 border border-blue-300">
                                    <i data-lucide="alert-triangle" class="w-3 h-3 text-blue-800"></i> Belum Diverifikasi
                                </span>
                            </div>
                            <p class="text-xs sm:text-sm text-blue-800 leading-relaxed max-w-3xl">
                                Sebelum mengajukan program kausa baru, pastikan Anda melengkapi profil lembaga resmi serta informasi penanggung jawab (PIC) yang valid sesuai dokumen notaris dan kependudukan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- 4-Tahapan Progres Verifikasi -->
        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 shadow-xs">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Alur &amp; Tahapan Legalitas Lembaga</h4>
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
                <div class="flex items-center gap-2.5 p-2 rounded-xl {{ $instansi->nama && $instansi->alamat ? 'bg-emerald-50 text-emerald-900 font-semibold' : 'bg-slate-50 text-slate-700' }}">
                    <span class="w-6 h-6 rounded-full {{ $instansi->nama && $instansi->alamat ? 'bg-emerald-600 text-white' : 'bg-slate-300 text-slate-700' }} flex items-center justify-center text-[11px] font-bold shrink-0">1</span>
                    <span class="truncate">Identitas Lembaga</span>
                    @if($instansi->nama && $instansi->alamat)
                        <i data-lucide="check" class="w-4 h-4 text-emerald-600 ml-auto hidden sm:inline shrink-0"></i>
                    @endif
                </div>
                <div class="flex items-center gap-2.5 p-2 rounded-xl {{ $instansi->dokumen->count() > 0 ? 'bg-emerald-50 text-emerald-900 font-semibold' : 'bg-slate-50 text-slate-700' }}">
                    <span class="w-6 h-6 rounded-full {{ $instansi->dokumen->count() > 0 ? 'bg-emerald-600 text-white' : 'bg-slate-300 text-slate-700' }} flex items-center justify-center text-[11px] font-bold shrink-0">2</span>
                    <span class="truncate">Berkas Legalitas</span>
                    @if($instansi->dokumen->count() > 0)
                        <i data-lucide="check" class="w-4 h-4 text-emerald-600 ml-auto hidden sm:inline shrink-0"></i>
                    @endif
                </div>
                <div class="flex items-center gap-2.5 p-2 rounded-xl {{ $status === 'menunggu_verifikasi' ? 'bg-amber-100 text-amber-950 font-bold ring-1 ring-amber-300' : ($status === 'terverifikasi' ? 'bg-emerald-50 text-emerald-900 font-semibold' : 'bg-slate-50 text-slate-700') }}">
                    <span class="w-6 h-6 rounded-full {{ $status === 'menunggu_verifikasi' ? 'bg-amber-500 text-white' : ($status === 'terverifikasi' ? 'bg-emerald-600 text-white' : 'bg-slate-300 text-slate-700') }} flex items-center justify-center text-[11px] font-bold shrink-0">3</span>
                    <span class="truncate">Kurasi Dinsos &amp; PPID</span>
                    @if($status === 'terverifikasi')
                        <i data-lucide="check" class="w-4 h-4 text-emerald-600 ml-auto hidden sm:inline shrink-0"></i>
                    @endif
                </div>
                <div class="flex items-center gap-2.5 p-2 rounded-xl {{ $status === 'terverifikasi' ? 'bg-emerald-100 text-emerald-950 font-bold ring-1 ring-emerald-400' : 'bg-slate-50 text-slate-700' }}">
                    <span class="w-6 h-6 rounded-full {{ $status === 'terverifikasi' ? 'bg-[#087F5B] text-white' : 'bg-slate-300 text-slate-700' }} flex items-center justify-center text-[11px] font-bold shrink-0">4</span>
                    <span class="truncate">Akses Kausa Aktif</span>
                    @if($status === 'terverifikasi')
                        <i data-lucide="unlock" class="w-4 h-4 text-emerald-700 ml-auto hidden sm:inline shrink-0"></i>
                    @else
                        <i data-lucide="lock" class="w-4 h-4 text-slate-400 ml-auto hidden sm:inline shrink-0"></i>
                    @endif
                </div>
            </div>
        </div>

        <!-- Alert Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-900 p-4 rounded-2xl shadow-xs space-y-2">
                <div class="flex items-center gap-2 font-bold text-xs sm:text-sm">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-red-600 shrink-0"></i>
                    <span>Terdapat kesalahan pengisian formulir:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 text-red-800 pl-6">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulir Profil & Legalitas -->
        <form method="POST" action="{{ route('instansi.profil.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- SECTION 1: Identitas Lembaga & Penanggung Jawab -->
            <div class="bg-white rounded-3xl border border-[#D9E2DE] p-6 sm:p-8 shadow-xs">
                <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#E6F4EF] text-[#087F5B] flex items-center justify-center font-bold shrink-0">
                            <i data-lucide="building-2" class="w-5 h-5 text-[#087F5B]"></i>
                        </div>
                        <div>
                            <h2 class="font-heading font-bold text-base sm:text-lg text-[#17211E]">
                                1. Identitas Lembaga &amp; Penanggung Jawab
                            </h2>
                            <p class="text-xs text-[#73817C] mt-0.5">
                                Sesuai data Akta Notaris, SK Kemenkumham, dan NIK e-KTP pengurus resmi.
                            </p>
                        </div>
                    </div>
                    <span class="hidden sm:inline-flex px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                        Wajib Lengkap
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Nama Lembaga Resmi -->
                    <div class="space-y-1.5 md:col-span-2">
                        <label for="nama" class="block text-xs font-bold text-[#17211E] uppercase tracking-wider">
                            Nama Resmi Entitas / Yayasan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="award" class="w-4 h-4 text-emerald-700"></i>
                            </span>
                            <input
                                type="text"
                                id="nama"
                                name="nama"
                                value="{{ old('nama', old('nama_lembaga', $instansi->nama ?? '')) }}"
                                required
                                placeholder="Contoh: Yayasan Peduli Sesama Tulungagung"
                                class="block w-full pl-10 pr-3.5 py-2.5 bg-[#F6F8F7] border @error('nama') border-red-500 @else border-[#D9E2DE] @enderror rounded-xl text-xs sm:text-sm text-[#17211E] focus:bg-white focus:border-[#087F5B] outline-none transition-all"
                            >
                        </div>
                        <p class="text-[11px] text-[#73817C]">Tuliskan nama lengkap tanpa singkatan sebagaimana tercantum di SK Kemenkumham.</p>
                        @error('nama')
                            <p class="text-[11px] text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bentuk Badan Hukum -->
                    <div class="space-y-1.5">
                        <label for="jenis" class="block text-xs font-bold text-[#17211E] uppercase tracking-wider">
                            Bentuk Entitas / Badan Hukum <span class="text-red-500">*</span>
                        </label>
                        @php
                            $currentJenis = old('jenis', old('jenis_badan_hukum', $instansi->jenis ?? 'Yayasan'));
                        @endphp
                        <select
                            id="jenis"
                            name="jenis"
                            required
                            class="block w-full px-3.5 py-2.5 bg-[#F6F8F7] border @error('jenis') border-red-500 @else border-[#D9E2DE] @enderror rounded-xl text-xs sm:text-sm text-[#17211E] focus:bg-white focus:border-[#087F5B] outline-none transition-all"
                        >
                            <option value="Yayasan" @selected(in_array($currentJenis, ['Yayasan', 'yayasan']))>Yayasan Sosial Kemasyarakatan</option>
                            <option value="Perkumpulan" @selected(in_array($currentJenis, ['Perkumpulan', 'perkumpulan']))>Perkumpulan Berbadan Hukum (Ormas Terdaftar)</option>
                            <option value="LKS" @selected(in_array($currentJenis, ['LKS', 'lks']))>Lembaga Kesejahteraan Sosial (LKS Terakreditasi)</option>
                            <option value="Komunitas Desa" @selected(in_array($currentJenis, ['Komunitas Desa', 'komunitas_desa']))>Kelompok Swadaya Rekomendasi Desa</option>
                            <option value="OPD" @selected(in_array($currentJenis, ['OPD', 'opd']))>Organisasi Perangkat Daerah (OPD Pemkab)</option>
                        </select>
                        <p class="text-[11px] text-[#73817C]">Kausa donasi perorangan pribadi ditolak secara sistem.</p>
                        @error('jenis')
                            <p class="text-[11px] text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor SK Kemenkumham / AHU -->
                    <div class="space-y-1.5">
                        <label for="nomor_registrasi" class="block text-xs font-bold text-[#17211E] uppercase tracking-wider">
                            Nomor Keputusan Kemenkumham / AHU
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="hash" class="w-4 h-4 text-emerald-700"></i>
                            </span>
                            <input
                                type="text"
                                id="nomor_registrasi"
                                name="nomor_registrasi"
                                value="{{ old('nomor_registrasi', old('no_sk_kemenkumham', $instansi->nomor_registrasi ?? '')) }}"
                                placeholder="Contoh: AHU-0019284.AH.01.04.TA.2021"
                                class="block w-full pl-10 pr-3.5 py-2.5 bg-[#F6F8F7] border border-[#D9E2DE] font-mono rounded-xl text-xs sm:text-sm text-[#17211E] focus:bg-white focus:border-[#087F5B] outline-none transition-all"
                            >
                        </div>
                        <p class="text-[11px] text-[#73817C]">Nomor pengesahan akta pendirian yayasan oleh Menkumham (bila ada).</p>
                    </div>

                    <!-- NPWP Lembaga -->
                    <div class="space-y-1.5">
                        <label for="npwp_lembaga" class="block text-xs font-bold text-[#17211E] uppercase tracking-wider">
                            NPWP Lembaga
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="credit-card" class="w-4 h-4 text-emerald-700"></i>
                            </span>
                            <input
                                type="text"
                                id="npwp_lembaga"
                                name="npwp_lembaga"
                                value="{{ old('npwp_lembaga', $user->npwp ?? '') }}"
                                placeholder="Contoh: 03.884.912.4-629.000"
                                class="block w-full pl-10 pr-3.5 py-2.5 bg-[#F6F8F7] border border-[#D9E2DE] font-mono rounded-xl text-xs sm:text-sm text-[#17211E] focus:bg-white focus:border-[#087F5B] outline-none transition-all"
                            >
                        </div>
                        <p class="text-[11px] text-[#73817C]">Nomor Pokok Wajib Pajak atas nama lembaga atau pengurus.</p>
                    </div>

                    <!-- Nomor Registrasi Tanda Daftar Dinsos -->
                    <div class="space-y-1.5">
                        <label for="no_dinsos" class="block text-xs font-bold text-[#17211E] uppercase tracking-wider">
                            No. Tanda Daftar Dinsos Tulungagung <span class="text-slate-400 font-normal">(Bila ada)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="file-badge" class="w-4 h-4 text-emerald-700"></i>
                            </span>
                            <input
                                type="text"
                                id="no_dinsos"
                                name="no_dinsos"
                                value="{{ old('no_dinsos', '') }}"
                                placeholder="Contoh: 460/88/DINSOS-TA/LKS/2023"
                                class="block w-full pl-10 pr-3.5 py-2.5 bg-[#F6F8F7] border border-[#D9E2DE] font-mono rounded-xl text-xs sm:text-sm text-[#17211E] focus:bg-white focus:border-[#087F5B] outline-none transition-all"
                            >
                        </div>
                        <p class="text-[11px] text-[#73817C]">Membantu percepatan verifikasi program bantuan sosial.</p>
                    </div>

                    <!-- Alamat Domisili Kantor di Tulungagung -->
                    <div class="space-y-1.5 md:col-span-2">
                        <label for="alamat" class="block text-xs font-bold text-[#17211E] uppercase tracking-wider">
                            Alamat Sekretariat / Domisili di Tulungagung <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute top-3 left-3.5 flex items-start pointer-events-none text-slate-400">
                                <i data-lucide="map-pin" class="w-4 h-4 text-emerald-700"></i>
                            </span>
                            <textarea
                                id="alamat"
                                name="alamat"
                                rows="2"
                                required
                                placeholder="Jl. Supriadi No. 42, RT 02 / RW 04, Kelurahan Kepatihan, Kecamatan Tulungagung, Kabupaten Tulungagung"
                                class="block w-full pl-10 pr-3.5 py-2.5 bg-[#F6F8F7] border @error('alamat') border-red-500 @else border-[#D9E2DE] @enderror rounded-xl text-xs sm:text-sm text-[#17211E] focus:bg-white focus:border-[#087F5B] outline-none transition-all"
                            >{{ old('alamat', old('alamat_kantor', $instansi->alamat ?? '')) }}</textarea>
                        </div>
                        <p class="text-[11px] text-[#73817C]">Wajib beralamat dalam wilayah yurisdiksi Kabupaten Tulungagung.</p>
                        @error('alamat')
                            <p class="text-[11px] text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sub-section: Penanggung Jawab NIK e-KTP -->
                    <div class="md:col-span-2 pt-4 mt-2 border-t border-slate-100">
                        <h3 class="font-heading font-bold text-xs uppercase tracking-wide text-slate-700 mb-3 flex items-center gap-2">
                            <i data-lucide="user-check" class="w-4 h-4 text-[#087F5B]"></i>
                            Identitas Penanggung Jawab Resmi (Ketua / Direktur Eksekutif)
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Nama Penanggung Jawab -->
                            <div class="space-y-1.5">
                                <label for="nama_pj" class="block text-xs font-bold text-[#17211E] uppercase tracking-wider">
                                    Nama Lengkap PIC (e-KTP)
                                </label>
                                <input
                                    type="text"
                                    id="nama_pj"
                                    name="nama_pj"
                                    value="{{ old('nama_pj', $user->name ?? '') }}"
                                    placeholder="Contoh: Bambang Suherman"
                                    class="block w-full px-3.5 py-2.5 bg-[#F6F8F7] border border-[#D9E2DE] rounded-xl text-xs sm:text-sm text-[#17211E] focus:bg-white focus:border-[#087F5B] outline-none transition-all"
                                >
                            </div>

                            <!-- NIK e-KTP -->
                            <div class="space-y-1.5">
                                <label for="nik_pj" class="block text-xs font-bold text-[#17211E] uppercase tracking-wider">
                                    NIK e-KTP Penanggung Jawab
                                </label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        id="nik_pj"
                                        name="nik_pj"
                                        maxlength="16"
                                        value="{{ old('nik_pj', $user->nik ?? '') }}"
                                        placeholder="16 digit NIK"
                                        class="block w-full px-3.5 py-2.5 bg-[#F6F8F7] border border-[#D9E2DE] font-mono rounded-xl text-xs sm:text-sm text-[#17211E] focus:bg-white focus:border-[#087F5B] outline-none transition-all"
                                    >
                                </div>
                                <p class="text-[10px] text-slate-500">16 digit NIK pengurus yang dapat dipertanggungjawabkan.</p>
                            </div>

                            <!-- Nomor Telepon / WhatsApp Resmi -->
                            <div class="space-y-1.5">
                                <label for="nomor_telepon" class="block text-xs font-bold text-[#17211E] uppercase tracking-wider">
                                    Nomor WhatsApp / Telp Resmi <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        id="nomor_telepon"
                                        name="nomor_telepon"
                                        required
                                        value="{{ old('nomor_telepon', old('wa_pj', $instansi->nomor_telepon ?? $user->phone_number ?? '')) }}"
                                        placeholder="Contoh: 081234567890"
                                        class="block w-full px-3.5 py-2.5 bg-[#F6F8F7] border @error('nomor_telepon') border-red-500 @else border-[#D9E2DE] @enderror font-mono rounded-xl text-xs sm:text-sm text-[#17211E] focus:bg-white focus:border-[#087F5B] outline-none transition-all"
                                    >
                                </div>
                                <p class="text-[10px] text-slate-500">Nomor aktif untuk verifikasi dan konfirmasi pelaporan.</p>
                                @error('nomor_telepon')
                                    <p class="text-[11px] text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Berkas & Dokumen Legalitas Fisik -->
            <div class="bg-white rounded-3xl border border-[#D9E2DE] p-6 sm:p-8 shadow-xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-6 border-b border-slate-100 gap-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#E6F4EF] text-[#087F5B] flex items-center justify-center font-bold shrink-0">
                            <i data-lucide="folder-check" class="w-5 h-5 text-[#087F5B]"></i>
                        </div>
                        <div>
                            <h2 class="font-heading font-bold text-base sm:text-lg text-[#17211E]">
                                2. Berkas &amp; Dokumen Legalitas Fisik (Maks. 5MB)
                            </h2>
                            <p class="text-xs text-[#73817C] mt-0.5">
                                Salinan pemindaian dokumen otentik berformat PDF, JPG, atau PNG.
                            </p>
                        </div>
                    </div>
                    <div>
                        @php
                            $dokumenCount = $instansi->dokumen->count();
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $dokumenCount >= 4 ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                            <i data-lucide="file-text" class="w-3.5 h-3.5 {{ $dokumenCount >= 4 ? 'text-emerald-600' : 'text-slate-500' }}"></i>
                            {{ $dokumenCount }} Dokumen Terunggah
                        </span>
                    </div>
                </div>

                @php
                    $dokumenList = [
                        [
                            'kode' => 'sk_kemenkumham',
                            'judul' => 'SK Pengesahan Kemenkumham',
                            'keterangan' => 'Surat Keputusan Menteri Hukum dan HAM RI tentang Pengesahan Badan Hukum Yayasan / Lembaga.',
                            'wajib' => true,
                            'icon' => 'file-text',
                            'color' => 'blue',
                        ],
                        [
                            'kode' => 'surat_keterangan_desa',
                            'judul' => 'Surat Keterangan Domisili Desa / Kelurahan',
                            'keterangan' => 'Surat Keterangan Domisili Organisasi dari Kepala Desa atau Lurah setempat di Tulungagung.',
                            'wajib' => true,
                            'icon' => 'map-pinned',
                            'color' => 'emerald',
                        ],
                        [
                            'kode' => 'npwp',
                            'judul' => 'Scan Kartu NPWP Lembaga',
                            'keterangan' => 'Kartu NPWP badan hukum lembaga berstatus aktif yang diterbitkan KPP Pratama.',
                            'wajib' => true,
                            'icon' => 'receipt-text',
                            'color' => 'indigo',
                        ],
                        [
                            'kode' => 'tanda_daftar_dinsos',
                            'judul' => 'Tanda Terdaftar Dinsos / Sertifikat Akreditasi',
                            'keterangan' => 'Tanda Terdaftar Lembaga Kesejahteraan Sosial (LKS) dari Dinas Sosial Kabupaten Tulungagung.',
                            'wajib' => false,
                            'icon' => 'shield-check',
                            'color' => 'teal',
                        ],
                    ];

                    $mappedDokumen = $instansi->dokumen->keyBy('jenis_dokumen');
                @endphp

                <!-- Documents Grid Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($dokumenList as $item)
                        @php
                            $doc = $mappedDokumen->get($item['kode']);
                        @endphp
                        <div class="border border-[#D9E2DE] hover:border-[#087F5B]/50 rounded-2xl p-4 bg-[#F6F8F7]/60 transition-all flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-[#087F5B] flex items-center justify-center shrink-0">
                                            <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-heading font-bold text-xs text-[#17211E]">{{ $item['judul'] }}</h4>
                                            @if($item['wajib'])
                                                <span class="text-[10px] text-red-600 font-semibold">* Wajib Mutlak</span>
                                            @else
                                                <span class="text-[10px] text-emerald-700 font-semibold">Pendukung Utama</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($doc)
                                        @if($doc->status_verifikasi === 'terverifikasi')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                Valid
                                            </span>
                                        @elseif($doc->status_verifikasi === 'ditolak')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800 border border-red-300">
                                                Ditolak
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                                Menunggu Verifikasi
                                            </span>
                                        @endif
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-200 text-slate-700">
                                            Belum Diunggah
                                        </span>
                                    @endif
                                </div>

                                <p class="text-[11px] text-[#73817C] mb-3 leading-relaxed">
                                    {{ $item['keterangan'] }}
                                </p>

                                @if($doc)
                                    <div class="flex items-center justify-between p-2.5 bg-white border border-[#D9E2DE] rounded-xl">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <i data-lucide="file-check" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                                            <div class="min-w-0">
                                                <p class="text-xs font-semibold text-[#17211E] truncate">{{ $doc->nama_file }}</p>
                                                <p class="text-[10px] text-slate-400">
                                                    {{ $doc->ukuran_file ? number_format($doc->ukuran_file / 1024, 0) . ' KB • ' : '' }}
                                                    Diunggah {{ $doc->created_at ? $doc->created_at->format('d M Y') : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @if($doc->catatan_admin)
                                        <div class="mt-2.5 pt-2 border-t border-slate-200/60 flex items-center justify-between text-[11px]">
                                            <span class="text-slate-500">Catatan Verifikator:</span>
                                            <span class="text-amber-700 italic font-medium truncate max-w-[200px]">{{ $doc->catatan_admin }}</span>
                                        </div>
                                    @endif
                                @else
                                    <div class="p-2.5 bg-white border border-dashed border-slate-300 rounded-xl text-center">
                                        <p class="text-[11px] text-slate-500">
                                            Berkas belum diunggah. Hubungi admin Dinsos untuk penyerahan fisik atau upload berkas.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Petunjuk & Catatan Privasi -->
                <div class="mt-5 p-4 rounded-2xl bg-[#E6F4EF]/50 border border-[#087F5B]/20 flex items-start gap-3 text-xs text-[#17211E]">
                    <i data-lucide="shield-check" class="w-4 h-4 text-[#087F5B] shrink-0 mt-0.5"></i>
                    <p class="leading-relaxed text-[#52615C]">
                        Dokumen legalitas lembaga Anda disimpan terenkripsi dan hanya dapat diakses oleh Tim Verifikator Dinas Sosial dan Bagian Hukum Setda Kabupaten Tulungagung untuk keperluan validasi akuntabilitas publik.
                    </p>
                </div>
            </div>

            <!-- Action Toolbar (Bottom Save Button) -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white rounded-2xl p-4 sm:p-5 border border-[#D9E2DE] shadow-xs">
                <div class="flex items-center gap-2 text-xs text-[#73817C] text-center sm:text-left">
                    <i data-lucide="info" class="w-4 h-4 text-[#087F5B] shrink-0"></i>
                    <span>Perubahan data profil akan dicatat pada riwayat audit sistem kepatuhan Pemkab Tulungagung.</span>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('dashboard.instansi') }}" class="flex-1 sm:flex-initial px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-semibold text-xs text-center transition-colors">
                        Kembali
                    </a>
                    <button type="submit" class="flex-1 sm:flex-initial px-6 py-2.5 rounded-xl bg-[#087F5B] hover:bg-[#066A4C] active:scale-95 text-white font-semibold text-xs shadow-sm transition-all flex items-center justify-center gap-2">
                        <i data-lucide="save" class="w-4 h-4 text-white"></i>
                        <span>Simpan Pembaruan Profil</span>
                    </button>
                </div>
            </div>
        </form>

        <!-- ACCORDION / REGULASI SECTION: Kebijakan Pemkab Tulungagung -->
        <section id="panduan-verifikasi" class="bg-white rounded-3xl border border-[#D9E2DE] p-6 shadow-xs space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-[#123B32] text-white flex items-center justify-center shrink-0">
                    <i data-lucide="scale" class="w-4 h-4 text-emerald-300"></i>
                </div>
                <div>
                    <h3 class="font-heading font-bold text-sm sm:text-base text-[#17211E]">
                        Dasar Regulasi &amp; Kebijakan Verifikasi Lembaga Pemkab Tulungagung
                    </h3>
                    <p class="text-xs text-[#73817C]">
                        Pedoman Pelaksanaan Penggalangan Dana Sosial Berbasis Integritas dan Pencegahan Kausa Fiktif
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs pt-1">
                <div class="p-3.5 rounded-xl bg-[#F6F8F7] border border-[#D9E2DE] space-y-1">
                    <div class="flex items-center gap-2 font-bold text-[#17211E]">
                        <i data-lucide="check-circle" class="w-4 h-4 text-[#087F5B]"></i>
                        <span>Perbup Akuntabilitas Donasi</span>
                    </div>
                    <p class="text-[#73817C] text-[11px] leading-relaxed">
                        Mengatur tata cara penggalangan dana sosial terbuka bagi OPD dan yayasan kemasyarakatan di Kabupaten Tulungagung.
                    </p>
                </div>

                <div class="p-3.5 rounded-xl bg-[#F6F8F7] border border-[#D9E2DE] space-y-1">
                    <div class="flex items-center gap-2 font-bold text-[#17211E]">
                        <i data-lucide="shield" class="w-4 h-4 text-[#087F5B]"></i>
                        <span>Verifikasi Non-SSO</span>
                    </div>
                    <p class="text-[#73817C] text-[11px] leading-relaxed">
                        Pencocokan data Kemenkumham AHU Online dan verifikasi lapangan oleh Dinas Sosial untuk memastikan keabsahan lembaga.
                    </p>
                </div>

                <div class="p-3.5 rounded-xl bg-[#F6F8F7] border border-[#D9E2DE] space-y-1">
                    <div class="flex items-center gap-2 font-bold text-[#17211E]">
                        <i data-lucide="receipt" class="w-4 h-4 text-[#087F5B]"></i>
                        <span>Kewajiban SPJ &amp; Transparansi</span>
                    </div>
                    <p class="text-[#73817C] text-[11px] leading-relaxed">
                        Setiap dana yang dicairkan wajib dilaporkan realisasinya disertai kuitansi, nota belanja resmi, dan bukti penyaluran.
                    </p>
                </div>
            </div>
        </section>

    </div>
</div>
@endsection
