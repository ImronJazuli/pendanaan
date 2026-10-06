@extends('layouts.dashboard-instansi')

@section('title', 'Ajukan Kausa Baru')
@section('topbar-title', 'Formulir Pengajuan Kausa Baru')
@section('topbar-subtitle', 'Lengkapi data kausa bantuan sosial atau kebencanaan di wilayah Kabupaten Tulungagung')

@section('topbar-actions')
    <div class="flex items-center gap-2">
        <button type="button" @click="$dispatch('trigger-save-draft')" class="text-xs sm:text-sm font-semibold px-3 sm:px-4 py-2 rounded-lg border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 transition-all active:scale-95 flex items-center gap-1.5 shadow-2xs">
            <i data-lucide="save" class="w-4 h-4 text-slate-500"></i>
            <span class="hidden sm:inline">Simpan Draf</span>
            <span class="sm:hidden">Draf</span>
        </button>
        <button type="button" @click="$dispatch('trigger-submit-modal')" class="text-xs sm:text-sm font-semibold px-3 sm:px-4 py-2 rounded-lg bg-[#087F5B] hover:bg-[#066A4C] text-white transition-all shadow-xs active:scale-95 flex items-center gap-1.5">
            <i data-lucide="send" class="w-4 h-4"></i>
            <span class="hidden sm:inline">Kirim Pengajuan</span>
            <span class="sm:hidden">Kirim</span>
        </button>
    </div>
@endsection

@section('content')
<div class="min-h-screen bg-[#F6F8F7] py-6 sm:py-8"
     x-data="kausaForm()"
     @trigger-save-draft.window="submitDraft()"
     @trigger-submit-modal.window="openConfirmModal()">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-4" aria-label="Breadcrumb">
            <a href="{{ route('dashboard.instansi') }}" class="hover:text-[#087F5B] transition-colors">Dashboard Instansi</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
            <a href="{{ route('dashboard.instansi') }}" class="hover:text-[#087F5B] transition-colors">Kausa Saya</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
            <span class="text-slate-800 font-semibold">Formulir Pengajuan Kausa Baru</span>
        </nav>

        <!-- Header Title & Status Badge -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-[#123B32] tracking-tight">Formulir Pengajuan Kausa Baru</h1>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-900 border border-amber-300">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        Status: Draf Sementara
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-600 mt-1.5 max-w-2xl">
                    Lengkapi data kausa bantuan sosial atau kebencanaan di wilayah Kabupaten Tulungagung. Seluruh pengajuan akan dikurasi dan diverifikasi oleh Tim PPID Pemkab sebelum tayang publik.
                </p>
            </div>

            <!-- Action Buttons Header -->
            <div class="flex items-center gap-2 self-start md:self-auto">
                <button type="button" @click="submitDraft()" class="text-xs sm:text-sm font-semibold px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 transition-all active:scale-95 flex items-center gap-2 shadow-2xs">
                    <i data-lucide="save" class="w-4 h-4 text-slate-500"></i>
                    <span>Simpan Draf</span>
                </button>
                <button type="button" @click="openConfirmModal()" class="text-xs sm:text-sm font-semibold px-4 py-2.5 rounded-lg bg-[#087F5B] hover:bg-[#066A4C] text-white transition-all shadow-xs active:scale-95 flex items-center gap-2">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Kirim Pengajuan</span>
                </button>
            </div>
        </div>

        <!-- Global Validation Error Banner -->
        @if (isset($errors) && $errors->any())
            <div class="mt-6 rounded-xl border border-red-300 bg-red-50 p-4 sm:p-5">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-lg bg-red-100 text-red-700 flex items-center justify-center shrink-0">
                        <i data-lucide="alert-octagon" class="w-5 h-5 text-red-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-bold text-red-900">Periksa Kembali Data Formulir</h3>
                        <p class="text-xs text-red-700 mt-1">Terdapat kesalahan pengisian data pada formulir. Silakan periksa kembali bagian yang ditandai:</p>
                        <ul class="list-disc list-inside mt-2 text-xs text-red-800 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Multi-Step Progress Stepper -->
        <div class="mt-6 sm:mt-8 bg-white border border-slate-200 rounded-xl p-4 sm:p-5 shadow-xs">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                
                <!-- Step 1 -->
                <button type="button" @click="goToStep(1)"
                        class="text-left flex items-center gap-3 p-2.5 rounded-lg transition-all"
                        :class="step === 1 ? 'bg-[#E6F4EF] text-[#123B32]' : (step > 1 ? 'text-emerald-800 hover:bg-slate-50' : 'text-slate-600 hover:bg-slate-50')">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0 shadow-xs"
                         :class="step === 1 ? 'bg-[#087F5B] text-white' : (step > 1 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700')">
                        <template x-if="step > 1">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-700"></i>
                        </template>
                        <template x-if="step <= 1">
                            <span>1</span>
                        </template>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-semibold uppercase tracking-wider"
                           :class="step === 1 ? 'text-[#087F5B]' : (step > 1 ? 'text-emerald-700' : 'text-slate-500')">Langkah 1</p>
                        <p class="text-xs sm:text-sm font-bold truncate">Identitas Kausa</p>
                    </div>
                </button>

                <!-- Step 2 -->
                <button type="button" @click="goToStep(2)"
                        class="text-left flex items-center gap-3 p-2.5 rounded-lg transition-all"
                        :class="step === 2 ? 'bg-[#E6F4EF] text-[#123B32]' : (step > 2 ? 'text-emerald-800 hover:bg-slate-50' : 'text-slate-600 hover:bg-slate-50')">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0"
                         :class="step === 2 ? 'bg-[#087F5B] text-white' : (step > 2 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700')">
                        <template x-if="step > 2">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-700"></i>
                        </template>
                        <template x-if="step <= 2">
                            <span>2</span>
                        </template>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-semibold uppercase tracking-wider"
                           :class="step === 2 ? 'text-[#087F5B]' : (step > 2 ? 'text-emerald-700' : 'text-slate-500')">Langkah 2</p>
                        <p class="text-xs sm:text-sm font-bold truncate">Target & Durasi</p>
                    </div>
                </button>

                <!-- Step 3 -->
                <button type="button" @click="goToStep(3)"
                        class="text-left flex items-center gap-3 p-2.5 rounded-lg transition-all"
                        :class="step === 3 ? 'bg-[#E6F4EF] text-[#123B32]' : (step > 3 ? 'text-emerald-800 hover:bg-slate-50' : 'text-slate-600 hover:bg-slate-50')">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0"
                         :class="step === 3 ? 'bg-[#087F5B] text-white' : (step > 3 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700')">
                        <template x-if="step > 3">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-700"></i>
                        </template>
                        <template x-if="step <= 3">
                            <span>3</span>
                        </template>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-semibold uppercase tracking-wider"
                           :class="step === 3 ? 'text-[#087F5B]' : (step > 3 ? 'text-emerald-700' : 'text-slate-500')">Langkah 3</p>
                        <p class="text-xs sm:text-sm font-bold truncate">Kronologi & Narasi</p>
                    </div>
                </button>

                <!-- Step 4 -->
                <button type="button" @click="goToStep(4)"
                        class="text-left flex items-center gap-3 p-2.5 rounded-lg transition-all"
                        :class="step === 4 ? 'bg-[#E6F4EF] text-[#123B32]' : 'text-slate-600 hover:bg-slate-50'">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0"
                         :class="step === 4 ? 'bg-[#087F5B] text-white' : 'bg-slate-200 text-slate-700'">
                        <span>4</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-semibold uppercase tracking-wider"
                           :class="step === 4 ? 'text-[#087F5B]' : 'text-slate-500'">Langkah 4</p>
                        <p class="text-xs sm:text-sm font-bold truncate">Galeri & Konfirmasi</p>
                    </div>
                </button>

            </div>

            <!-- Progress Bar -->
            <div class="w-full bg-slate-100 h-1.5 rounded-full mt-4 overflow-hidden">
                <div class="bg-[#087F5B] h-full transition-all duration-300"
                     :style="`width: ${step === 1 ? '25%' : (step === 2 ? '50%' : (step === 3 ? '75%' : '100%'))}`"></div>
            </div>
        </div>

        <!-- MAIN FORM BODY GRID -->
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- LEFT / MAIN: MULTI-STEP FORM WRAPPER (8 Columns) -->
            <div class="lg:col-span-8 space-y-6">
                <form id="form-pengajuan-kausa" action="{{ route('kausa.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="action" x-model="actionType" value="draft">

                    <!-- ================= STEP 1: IDENTITAS KAUSA & LOKASI TULUNGAGUNG ================= -->
                    <section x-show="step === 1" x-cloak class="bg-white border border-slate-200 rounded-xl p-5 sm:p-7 shadow-xs">
                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                            <div>
                                <span class="text-xs font-bold text-[#087F5B] uppercase tracking-wider">Bagian 1 dari 4</span>
                                <h2 class="text-lg sm:text-xl font-bold font-heading text-[#123B32]">Identitas Kausa & Kategori Sasaran</h2>
                            </div>
                            <span class="p-2 rounded-lg bg-emerald-50 text-[#087F5B]">
                                <i data-lucide="tag" class="w-5 h-5"></i>
                            </span>
                        </div>

                        <!-- Prasyarat Legalitas Pengaju -->
                        <div class="p-3.5 mb-6 rounded-lg bg-emerald-50/70 border border-emerald-200 flex items-center justify-between flex-wrap gap-2 text-xs">
                            <div class="flex items-center gap-2 text-emerald-900 font-medium">
                                <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                                <span>Status Entitas: <strong>{{ auth()->user()->instansi->nama ?? 'Instansi / OPD Pemkab Tulungagung' }}</strong></span>
                            </div>
                            <span class="text-emerald-700 bg-white px-2 py-0.5 rounded text-[11px] font-semibold border border-emerald-200">
                                Instansi Terdaftar
                            </span>
                        </div>

                        <div class="space-y-5">
                            
                            <!-- Judul Kausa -->
                            <div>
                                <label for="input-judul" class="block text-xs sm:text-sm font-semibold text-slate-800 mb-1.5">
                                    Judul Pengajuan Kausa <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" 
                                       id="input-judul" 
                                       name="judul"
                                       required
                                       maxlength="255"
                                       x-model="judul"
                                       placeholder="Contoh: Bantuan Darurat Korban Tanah Longsor di Kecamatan Sendang"
                                       class="w-full px-3.5 py-2.5 text-sm bg-white border @error('judul') border-rose-500 ring-1 ring-rose-500 @else border-slate-300 @enderror rounded-lg text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#087F5B] focus:border-[#087F5B] transition-all">
                                <div class="flex justify-between items-center mt-1 text-[11px] text-slate-500">
                                    <span>Gunakan kalimat jelas, sopan, dan mencerminkan kebutuhan riil masyarakat.</span>
                                    <span :class="judul.length > 200 ? 'text-amber-600 font-semibold' : ''" x-text="`${judul.length} / 255 Karakter`"></span>
                                </div>
                                @error('judul')
                                    <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Kategori Kausa (Dynamic from Database) -->
                            <div>
                                <label class="block text-xs sm:text-sm font-semibold text-slate-800 mb-1.5">
                                    Kategori Kausa Pemkab <span class="text-rose-500">*</span>
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="kategori-selector-group">
                                    @forelse($kategori as $kat)
                                        <label class="relative flex items-center p-3 rounded-lg border cursor-pointer transition-all"
                                               :class="kategori_kausa_id == '{{ $kat->id }}' ? 'border-2 border-[#087F5B] bg-[#E6F4EF]/40 shadow-2xs' : 'border-slate-200 bg-white hover:border-[#087F5B]'">
                                            <input type="radio" 
                                                   name="kategori_kausa_id" 
                                                   value="{{ $kat->id }}" 
                                                   class="sr-only" 
                                                   x-model="kategori_kausa_id"
                                                   @change="kategori_nama = '{{ $kat->nama }}'"
                                                   {{ old('kategori_kausa_id') == $kat->id ? 'checked' : '' }}>
                                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold shrink-0"
                                                     :class="kategori_kausa_id == '{{ $kat->id }}' ? 'bg-emerald-100 text-[#087F5B]' : 'bg-slate-100 text-slate-600'">
                                                    @if(str_contains(strtolower($kat->nama), 'bencana'))
                                                        <i data-lucide="cloud-lightning" class="w-4 h-4"></i>
                                                    @elseif(str_contains(strtolower($kat->nama), 'ibadah'))
                                                        <i data-lucide="landmark" class="w-4 h-4"></i>
                                                    @elseif(str_contains(strtolower($kat->nama), 'panti') || str_contains(strtolower($kat->nama), 'anak'))
                                                        <i data-lucide="users" class="w-4 h-4"></i>
                                                    @else
                                                        <i data-lucide="heart-handshake" class="w-4 h-4"></i>
                                                    @endif
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-xs font-bold text-slate-900 truncate">{{ $kat->nama }}</p>
                                                    <p class="text-[11px] text-slate-500 truncate">{{ $kat->deskripsi ?? 'Kategori program resmi' }}</p>
                                                </div>
                                            </div>
                                            <div class="ml-2 w-4 h-4 rounded-full flex items-center justify-center shrink-0"
                                                 :class="kategori_kausa_id == '{{ $kat->id }}' ? 'border-2 border-[#087F5B]' : 'border border-slate-300'">
                                                <div x-show="kategori_kausa_id == '{{ $kat->id }}'" class="w-2 h-2 rounded-full bg-[#087F5B]"></div>
                                            </div>
                                        </label>
                                    @empty
                                        <p class="text-xs text-slate-500 col-span-2">Kategori belum tersedia.</p>
                                    @endforelse
                                </div>
                                @error('kategori_kausa_id')
                                    <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Wilayah & Lokasi Spesifik Tulungagung -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="select-kecamatan" class="block text-xs sm:text-sm font-semibold text-slate-800 mb-1.5">
                                        Kecamatan di Kab. Tulungagung <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select id="select-kecamatan" 
                                                x-model="kecamatan"
                                                @change="syncLokasi()"
                                                class="w-full appearance-none px-3.5 py-2.5 text-sm bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#087F5B] focus:border-[#087F5B]">
                                            <option value="">Pilih Kecamatan...</option>
                                            <option value="Besuki">Besuki</option>
                                            <option value="Bandung">Bandung</option>
                                            <option value="Boyolangu">Boyolangu</option>
                                            <option value="Campurdarat">Campurdarat</option>
                                            <option value="Gondang">Gondang</option>
                                            <option value="Kalidawir">Kalidawir</option>
                                            <option value="Karangrejo">Karangrejo</option>
                                            <option value="Kauman">Kauman</option>
                                            <option value="Kedungwaru">Kedungwaru</option>
                                            <option value="Ngantru">Ngantru</option>
                                            <option value="Ngunut">Ngunut</option>
                                            <option value="Pagerwojo">Pagerwojo</option>
                                            <option value="Pakel">Pakel</option>
                                            <option value="Pucanglaban">Pucanglaban</option>
                                            <option value="Rejotangan">Rejotangan</option>
                                            <option value="Sendang">Sendang</option>
                                            <option value="Sumbergempol">Sumbergempol</option>
                                            <option value="Tanggunggunung">Tanggunggunung</option>
                                            <option value="Tulungagung">Kota Tulungagung</option>
                                        </select>
                                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-3.5 top-3 pointer-events-none"></i>
                                    </div>
                                </div>

                                <div>
                                    <label for="input-desa" class="block text-xs sm:text-sm font-semibold text-slate-800 mb-1.5">
                                        Desa / Kelurahan & Titik Lokasi <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="input-desa" 
                                           x-model="desa"
                                           @input="syncLokasi()"
                                           placeholder="Contoh: Desa Besole RT 03/RW 02"
                                           class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#087F5B] focus:border-[#087F5B]">
                                </div>
                            </div>

                            <!-- Combined Lokasi Input (Submitted to Server) -->
                            <div>
                                <label for="input-lokasi" class="block text-xs sm:text-sm font-semibold text-slate-800 mb-1.5">
                                    Alamat Lengkap Wilayah Sasaran <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" 
                                       id="input-lokasi" 
                                       name="lokasi"
                                       required
                                       x-model="lokasi"
                                       placeholder="Contoh: Desa Besole, Kec. Besuki, Kab. Tulungagung"
                                       class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border @error('lokasi') border-rose-500 ring-1 ring-rose-500 @else border-slate-300 @enderror rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#087F5B] focus:border-[#087F5B]">
                                <p class="text-[11px] text-slate-500 mt-1">Alamat ini otomatis disinkronkan dari pilihan kecamatan dan detail desa di atas.</p>
                                @error('lokasi')
                                    <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Ringkasan Kebutuhan Singkat -->
                            <div>
                                <label for="input-ringkasan" class="block text-xs sm:text-sm font-semibold text-slate-800 mb-1.5">
                                    Ringkasan Kebutuhan Mendesak (1 Paragraf untuk Card Publik)
                                </label>
                                <textarea id="input-ringkasan" 
                                          name="ringkasan"
                                          rows="2" 
                                          x-model="ringkasan"
                                          maxlength="1000"
                                          class="w-full px-3.5 py-2.5 text-sm bg-white border @error('ringkasan') border-rose-500 @else border-slate-300 @enderror rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#087F5B] focus:border-[#087F5B] resize-none"
                                          placeholder="Tuliskan intisari urgensi kausa dalam 2-3 kalimat ringkas untuk ditampilkan pada kartu pencarian publik..."></textarea>
                                @error('ringkasan')
                                    <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Geografis Tulungagung Info -->
                            <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg flex items-center justify-between">
                                <div class="flex items-center gap-2.5 text-xs text-slate-600">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-[#087F5B] shrink-0"></i>
                                    <span>Wilayah Terdaftar: <strong class="text-slate-800">Kabupaten Tulungagung, Jawa Timur</strong></span>
                                </div>
                                <span class="text-[11px] font-semibold text-[#087F5B] px-2 py-0.5 bg-white border border-slate-200 rounded">
                                    PPID Verified
                                </span>
                            </div>

                        </div>

                        <!-- Bottom Step Action -->
                        <div class="mt-8 pt-4 border-t border-slate-100 flex justify-end">
                            <button type="button" @click="goToStep(2)" class="px-5 py-2.5 rounded-lg bg-[#087F5B] hover:bg-[#066A4C] text-white text-xs sm:text-sm font-semibold inline-flex items-center gap-2 transition-all active:scale-95 shadow-xs">
                                <span>Lanjut: Target & Durasi</span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </section>

                    <!-- ================= STEP 2: TARGET NOMINAL & DURASI ================= -->
                    <section x-show="step === 2" x-cloak class="bg-white border border-slate-200 rounded-xl p-5 sm:p-7 shadow-xs">
                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                            <div>
                                <span class="text-xs font-bold text-[#087F5B] uppercase tracking-wider">Bagian 2 dari 4</span>
                                <h2 class="text-lg sm:text-xl font-bold font-heading text-[#123B32]">Target Nominal Donasi & Batas Waktu</h2>
                            </div>
                            <span class="p-2 rounded-lg bg-emerald-50 text-[#087F5B]">
                                <i data-lucide="banknote" class="w-5 h-5"></i>
                            </span>
                        </div>

                        <div class="space-y-6">
                            
                            <!-- Target Dana Rupiah -->
                            <div>
                                <label for="input-target-dana" class="block text-xs sm:text-sm font-semibold text-slate-800 mb-1.5">
                                    Target Nominal Dana Dibutuhkan <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 font-bold text-sm">
                                        Rp
                                    </div>
                                    <input type="text" 
                                           id="input-display-target-dana" 
                                           required 
                                           x-model="displayTargetDana"
                                           @input="handleTargetInput($event)"
                                           placeholder="Contoh: 50.000.000" 
                                           class="w-full pl-12 pr-4 py-2.5 text-base font-semibold bg-white border @error('target_dana') border-rose-500 ring-1 ring-rose-500 @else border-slate-300 @enderror rounded-lg text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#087F5B] focus:border-[#087F5B]">
                                    <!-- Hidden clean numeric value for server submission -->
                                    <input type="hidden" name="target_dana" :value="rawTargetDana">
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1.5 flex items-center gap-1">
                                    <i data-lucide="info" class="w-3.5 h-3.5 text-slate-400"></i>
                                    Terbilang: <span class="font-semibold text-[#087F5B]" x-text="nominalTerbilang">Nol Rupiah</span>
                                </p>
                                @error('target_dana')
                                    <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Quick Nominal Presets -->
                            <div>
                                <span class="text-xs text-slate-500 block mb-2 font-medium">Atau pilih rekomendasi nominal cepat:</span>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" @click="setPresetNominal(15000000)"
                                            class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors"
                                            :class="rawTargetDana == 15000000 ? 'border-[#087F5B] bg-[#E6F4EF] text-[#087F5B]' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-emerald-50 hover:border-[#087F5B]'">
                                        Rp 15.000.000
                                    </button>
                                    <button type="button" @click="setPresetNominal(25000000)"
                                            class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors"
                                            :class="rawTargetDana == 25000000 ? 'border-[#087F5B] bg-[#E6F4EF] text-[#087F5B]' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-emerald-50 hover:border-[#087F5B]'">
                                        Rp 25.000.000
                                    </button>
                                    <button type="button" @click="setPresetNominal(45000000)"
                                            class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors"
                                            :class="rawTargetDana == 45000000 ? 'border-[#087F5B] bg-[#E6F4EF] text-[#087F5B]' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-emerald-50 hover:border-[#087F5B]'">
                                        Rp 45.000.000
                                    </button>
                                    <button type="button" @click="setPresetNominal(75000000)"
                                            class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors"
                                            :class="rawTargetDana == 75000000 ? 'border-[#087F5B] bg-[#E6F4EF] text-[#087F5B]' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-emerald-50 hover:border-[#087F5B]'">
                                        Rp 75.000.000
                                    </button>
                                    <button type="button" @click="setPresetNominal(100000000)"
                                            class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors"
                                            :class="rawTargetDana == 100000000 ? 'border-[#087F5B] bg-[#E6F4EF] text-[#087F5B]' : 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-emerald-50 hover:border-[#087F5B]'">
                                        Rp 100.000.000
                                    </button>
                                </div>
                            </div>

                            <!-- Durasi Kampanye & Tanggal Berakhir -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                                <div>
                                    <label for="input-tanggal-mulai" class="block text-xs sm:text-sm font-semibold text-slate-800 mb-1.5">
                                        Tanggal Mulai Program <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" 
                                           id="input-tanggal-mulai" 
                                           name="tanggal_mulai"
                                           x-model="tanggal_mulai"
                                           @change="calculateDays()"
                                           required 
                                           class="w-full px-3.5 py-2.5 text-sm bg-white border @error('tanggal_mulai') border-rose-500 @else border-slate-300 @enderror rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#087F5B] focus:border-[#087F5B]">
                                    @error('tanggal_mulai')
                                        <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="input-tanggal-selesai" class="block text-xs sm:text-sm font-semibold text-slate-800 mb-1.5">
                                        Tanggal Selesai (Maksimal 60 Hari) <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" 
                                           id="input-tanggal-selesai" 
                                           name="tanggal_berakhir"
                                           x-model="tanggal_berakhir"
                                           @change="calculateDays()"
                                           required 
                                           class="w-full px-3.5 py-2.5 text-sm bg-white border @error('tanggal_berakhir') border-rose-500 @else border-slate-300 @enderror rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#087F5B] focus:border-[#087F5B]">
                                    <div class="mt-1 flex items-center justify-between text-[11px] text-slate-500">
                                        <span>Durasi kampanye donasi:</span>
                                        <span class="font-bold text-[#087F5B]" x-text="durasiHariText">30 Hari Kalender</span>
                                    </div>
                                    @error('tanggal_berakhir')
                                        <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Ketentuan Verifikasi Pencairan Dana -->
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-2">
                                <div class="flex items-center gap-2 font-semibold text-[#123B32]">
                                    <i data-lucide="shield-alert" class="w-4 h-4 text-[#087F5B]"></i>
                                    <span>Aturan Transparansi Penyaluran Pemkab Tulungagung:</span>
                                </div>
                                <p class="leading-relaxed">
                                    Seluruh dana yang masuk diproses terpusat via rekening escrow Pemkab Tulungagung (Simulasi Midtrans). Pencairan ke instansi dilakukan bertahap sesuai kebutuhan lapangan dan wajib disertai pelaporan nota belanja resmi pada Tab Transparansi Publik.
                                </p>
                            </div>

                        </div>

                        <!-- Step Navigation Buttons -->
                        <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <button type="button" @click="goToStep(1)" class="px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs sm:text-sm font-semibold inline-flex items-center gap-2 transition-all active:scale-95">
                                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                                <span>Kembali</span>
                            </button>
                            <button type="button" @click="goToStep(3)" class="px-5 py-2.5 rounded-lg bg-[#087F5B] hover:bg-[#066A4C] text-white text-xs sm:text-sm font-semibold inline-flex items-center gap-2 transition-all active:scale-95 shadow-xs">
                                <span>Lanjut: Narasi & Kronologi</span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </section>

                    <!-- ================= STEP 3: RICH KRONOLOGI & NARASI KEBUTUHAN ================= -->
                    <section x-show="step === 3" x-cloak class="bg-white border border-slate-200 rounded-xl p-5 sm:p-7 shadow-xs">
                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                            <div>
                                <span class="text-xs font-bold text-[#087F5B] uppercase tracking-wider">Bagian 3 dari 4</span>
                                <h2 class="text-lg sm:text-xl font-bold font-heading text-[#123B32]">Kronologi Kejadian & Rincian Urgensi</h2>
                            </div>
                            <span class="p-2 rounded-lg bg-emerald-50 text-[#087F5B]">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                            </span>
                        </div>

                        <div class="space-y-5">
                            
                            <!-- Narasi Kronologi Lengkap & Rencana Alokasi -->
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label for="input-deskripsi" class="block text-xs sm:text-sm font-semibold text-slate-800">
                                        Kronologi Lengkap & Rencana Rincian Alokasi Dana <span class="text-rose-500">*</span>
                                    </label>
                                    <span class="text-[11px] text-slate-500">Mendukung format paragraf & rincian</span>
                                </div>

                                <!-- Rich Format Toolbar Helper -->
                                <div class="border border-slate-300 rounded-t-lg bg-slate-50 px-3 py-2 flex items-center gap-1 border-b-0 flex-wrap">
                                    <button type="button" @click="insertFormat('**', '**')" class="p-1.5 rounded hover:bg-slate-200 text-slate-700 transition-colors" title="Tebal">
                                        <i data-lucide="bold" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <button type="button" @click="insertFormat('*', '*')" class="p-1.5 rounded hover:bg-slate-200 text-slate-700 transition-colors" title="Miring">
                                        <i data-lucide="italic" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <span class="w-px h-4 bg-slate-300 mx-1"></span>
                                    <button type="button" @click="insertPrefix('1. ')" class="p-1.5 rounded hover:bg-slate-200 text-slate-700 transition-colors" title="Daftar Angka">
                                        <i data-lucide="list-ordered" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <button type="button" @click="insertPrefix('- ')" class="p-1.5 rounded hover:bg-slate-200 text-slate-700 transition-colors" title="Daftar Bullet">
                                        <i data-lucide="list" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <span class="w-px h-4 bg-slate-300 mx-1"></span>
                                    <button type="button" @click="insertPrefix('> ')" class="p-1.5 rounded hover:bg-slate-200 text-slate-700 transition-colors" title="Kutipan">
                                        <i data-lucide="quote" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <span class="text-[11px] text-slate-400 ml-auto hidden sm:inline">Editor Markdown Tersedia</span>
                                </div>

                                <!-- Main Textarea Content -->
                                <textarea id="input-deskripsi" 
                                          name="deskripsi"
                                          rows="8" 
                                          required
                                          x-model="deskripsi"
                                          class="w-full px-3.5 py-3 text-sm bg-white border @error('deskripsi') border-rose-500 ring-1 ring-rose-500 @else border-slate-300 @enderror rounded-b-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#087F5B] focus:border-[#087F5B] font-sans leading-relaxed"
                                          placeholder="Ceritakan latar belakang peristiwa, tanggal kejadian, kondisi warga saat ini, instansi yang telah turun ke lapangan, serta rincian estimasi belanja bantuan..."></textarea>
                                @error('deskripsi')
                                    <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Petunjuk Verifikasi PPID -->
                            <div class="rounded-lg p-3 bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 flex items-start gap-2">
                                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-700 mt-0.5 shrink-0"></i>
                                <p>
                                    <strong>Kaidah Keterbukaan Informasi Publik:</strong> Cantumkan perkiraan rincian kebutuhan biaya secara logis. Transparansi narasi akan mempercepat proses kurasi dan persetujuan oleh Tim Verifikator Pemkab Tulungagung.
                                </p>
                            </div>

                        </div>

                        <!-- Step Navigation Buttons -->
                        <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <button type="button" @click="goToStep(2)" class="px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs sm:text-sm font-semibold inline-flex items-center gap-2 transition-all active:scale-95">
                                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                                <span>Kembali</span>
                            </button>
                            <button type="button" @click="goToStep(4)" class="px-5 py-2.5 rounded-lg bg-[#087F5B] hover:bg-[#066A4C] text-white text-xs sm:text-sm font-semibold inline-flex items-center gap-2 transition-all active:scale-95 shadow-xs">
                                <span>Lanjut: Dokumentasi Lapangan</span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </section>

                    <!-- ================= STEP 4: GALERI FOTO & PRATINJAU KONFIRMASI ================= -->
                    <section x-show="step === 4" x-cloak class="bg-white border border-slate-200 rounded-xl p-5 sm:p-7 shadow-xs">
                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                            <div>
                                <span class="text-xs font-bold text-[#087F5B] uppercase tracking-wider">Bagian 4 dari 4</span>
                                <h2 class="text-lg sm:text-xl font-bold font-heading text-[#123B32]">Dokumentasi Lapangan & Konfirmasi</h2>
                            </div>
                            <span class="p-2 rounded-lg bg-emerald-50 text-[#087F5B]">
                                <i data-lucide="image" class="w-5 h-5"></i>
                            </span>
                        </div>

                        <div class="space-y-6">
                            
                            <!-- File Drag & Drop Area -->
                            <div>
                                <label class="block text-xs sm:text-sm font-semibold text-slate-800 mb-1.5">
                                    Unggah Foto Kondisi Lapangan & Dokumen Pendukung (Maksimal 10 File)
                                </label>

                                <div @click="$refs.fileInput.click()"
                                     @dragover.prevent="dragOver = true"
                                     @dragleave.prevent="dragOver = false"
                                     @drop.prevent="handleFileDrop($event)"
                                     class="border-2 border-dashed rounded-xl p-6 sm:p-8 text-center transition-all cursor-pointer"
                                     :class="dragOver ? 'border-[#087F5B] bg-[#E6F4EF]/50 scale-[1.01]' : 'border-slate-300 hover:border-[#087F5B] bg-slate-50/50 hover:bg-[#E6F4EF]/20'">
                                    
                                    <input type="file" 
                                           name="dokumen[]" 
                                           x-ref="fileInput"
                                           @change="handleFileInput($event)"
                                           multiple 
                                           accept="image/png, image/jpeg, image/webp, application/pdf" 
                                           class="hidden">
                                    
                                    <div class="mx-auto w-12 h-12 rounded-full bg-[#E6F4EF] text-[#087F5B] flex items-center justify-center mb-3">
                                        <i data-lucide="upload-cloud" class="w-6 h-6"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-800">Tarik berkas foto ke sini atau <span class="text-[#087F5B] underline">Pilih dari Komputer</span></p>
                                    <p class="text-xs text-slate-500 mt-1">Format JPG, PNG, WEBP, atau PDF (Maksimal 5MB per file)</p>
                                    <div class="mt-3 inline-flex items-center gap-2 text-[11px] font-medium text-slate-600 bg-white px-3 py-1 rounded-full border border-slate-200">
                                        <i data-lucide="check-check" class="w-3.5 h-3.5 text-emerald-600"></i> Validasi berkas otomatis tersimpan aman di server
                                    </div>
                                </div>
                                @error('dokumen')
                                    <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                                    </p>
                                @enderror
                                @error('dokumen.*')
                                    <p class="text-xs text-rose-600 mt-1 flex items-center gap-1">
                                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Uploaded Gallery Items Preview -->
                            <div x-show="previewFiles.length > 0">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-slate-700">Berkas Terpilih (<span x-text="previewFiles.length">0</span> file):</span>
                                    <span class="text-[11px] text-slate-500">Foto pertama akan menjadi thumbnail publik</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <template x-for="(file, index) in previewFiles" :key="index">
                                        <div class="relative group border rounded-lg overflow-hidden bg-slate-100 aspect-video shadow-xs"
                                             :class="index === 0 ? 'border-2 border-[#087F5B]' : 'border-slate-200'">
                                            
                                            <template x-if="file.isImage">
                                                <img :src="file.previewUrl" :alt="file.name" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!file.isImage">
                                                <div class="w-full h-full flex flex-col items-center justify-center p-3 text-slate-600 bg-slate-100">
                                                    <i data-lucide="file" class="w-8 h-8 text-slate-400 mb-1"></i>
                                                    <span class="text-[11px] font-semibold truncate w-full text-center" x-text="file.name"></span>
                                                </div>
                                            </template>

                                            <template x-if="index === 0 && file.isImage">
                                                <div class="absolute top-2 left-2 bg-[#087F5B] text-white text-[10px] font-bold px-2 py-0.5 rounded shadow">
                                                    Foto Sampul
                                                </div>
                                            </template>

                                            <button type="button" @click="removeFile(index)" class="absolute top-2 right-2 bg-black/60 hover:bg-rose-600 text-white p-1 rounded transition-colors" title="Hapus berkas">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>

                                            <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/80 to-transparent p-2 text-white">
                                                <p class="text-[11px] truncate font-medium" x-text="file.name"></p>
                                                <span class="text-[10px] text-slate-300" x-text="file.formattedSize"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Pakta Integritas / Pernyataan Tanggung Jawab Hukum -->
                            <div class="pt-4 border-t border-slate-200">
                                <label class="flex items-start gap-3 cursor-pointer select-none">
                                    <input type="checkbox" 
                                           id="check-pakta-integritas" 
                                           x-model="paktaIntegritas" 
                                           required 
                                           class="mt-1 w-4 h-4 rounded text-[#087F5B] focus:ring-[#087F5B] border-slate-300">
                                    <span class="text-xs text-slate-700 leading-relaxed">
                                        Saya menyatakan secara sadar bahwa data yang diajukan adalah benar, berlokasi nyata di Kabupaten Tulungagung, dan instansi kami bersedia mempertanggungjawabkan pengelolaan donasi sesuai regulasi pelaporan audit Pemkab Tulungagung.
                                    </span>
                                </label>
                            </div>

                        </div>

                        <!-- Step Navigation Buttons -->
                        <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-between flex-wrap gap-3">
                            <button type="button" @click="goToStep(3)" class="px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs sm:text-sm font-semibold inline-flex items-center gap-2 transition-all active:scale-95">
                                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                                <span>Kembali</span>
                            </button>
                            
                            <div class="flex items-center gap-2">
                                <button type="button" @click="submitDraft()" class="px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs sm:text-sm font-semibold inline-flex items-center gap-2 transition-all active:scale-95 shadow-2xs">
                                    <i data-lucide="save" class="w-4 h-4 text-slate-500"></i>
                                    <span>Simpan Draf</span>
                                </button>
                                <button type="button" @click="openConfirmModal()" class="px-5 py-2.5 rounded-lg bg-[#087F5B] hover:bg-[#066A4C] text-white text-xs sm:text-sm font-semibold inline-flex items-center gap-2 transition-all shadow-xs active:scale-95">
                                    <i data-lucide="send" class="w-4 h-4"></i>
                                    <span>Kirim Pengajuan</span>
                                </button>
                            </div>
                        </div>
                    </section>

                </form>
            </div>

            <!-- RIGHT / SIDEBAR: PRATINJAU KARTU KAUSA PUBLIK & RINGKASAN (4 Columns) -->
            <aside class="lg:col-span-4 space-y-6">
                
                <!-- Sticky Live Preview Card -->
                <div class="sticky top-20 space-y-5">
                    
                    <div class="bg-white border border-slate-200 rounded-xl p-4 sm:p-5 shadow-xs">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-700">
                                <i data-lucide="eye" class="w-4 h-4 text-[#087F5B]"></i>
                                <span>Pratinjau Card Publik</span>
                            </div>
                            <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                                Live Preview
                            </span>
                        </div>

                        <!-- Preview Card Miniature -->
                        <div class="border border-slate-200 rounded-xl overflow-hidden bg-white shadow-xs">
                            <!-- Thumbnail -->
                            <div class="relative aspect-video bg-slate-100">
                                <img :src="coverImageUrl" 
                                     alt="Pratinjau Kausa" 
                                     class="w-full h-full object-cover">
                                <div class="absolute top-2.5 left-2.5">
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full bg-[#123B32]/90 text-white shadow-xs backdrop-blur-xs">
                                        <i data-lucide="tag" class="w-3 h-3 text-emerald-300"></i>
                                        <span x-text="kategori_nama || 'Kategori'">Bencana Alam</span>
                                    </span>
                                </div>
                                <div class="absolute top-2.5 right-2.5">
                                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-md bg-white/95 text-[#123B32] shadow-xs">
                                        <i data-lucide="map-pin" class="w-3 h-3 text-[#087F5B]"></i>
                                        <span x-text="kecamatan || 'Tulungagung'">Tulungagung</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="p-4 space-y-3">
                                <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                    <i data-lucide="building" class="w-3.5 h-3.5 text-slate-400"></i>
                                    <span class="font-medium truncate">{{ auth()->user()->instansi->nama ?? 'Instansi Pengaju' }}</span>
                                    <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600 shrink-0" title="Terverifikasi"></i>
                                </div>

                                <h3 class="text-sm font-bold text-slate-900 line-clamp-2 leading-snug"
                                    x-text="judul.trim() || 'Judul Pengajuan Kausa Baru Akan Tampil di Sini'">
                                    Judul Pengajuan Kausa Baru Akan Tampil di Sini
                                </h3>

                                <!-- Progress Mockup -->
                                <div class="space-y-1.5 pt-1">
                                    <div class="flex justify-between text-xs">
                                        <span class="text-slate-500">Terkumpul</span>
                                        <span class="font-bold text-slate-800">Rp 0 <span class="text-slate-400 font-normal">(0%)</span></span>
                                    </div>
                                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                        <div class="bg-[#087F5B] h-full w-[2%]"></div>
                                    </div>
                                    <div class="flex justify-between items-center text-[11px] text-slate-500">
                                        <span>Target: <strong class="text-slate-800" x-text="formatRupiah(rawTargetDana)">Rp 0</strong></span>
                                        <span class="font-semibold text-[#087F5B]" x-text="durasiHariText">30 Hari Lagi</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Mock Footer -->
                            <div class="px-4 py-2.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="text-slate-500 text-[11px]">Portal PPID Tulungagung</span>
                                <span class="text-[#087F5B] font-semibold text-xs flex items-center gap-1">
                                    Detail <i data-lucide="chevron-right" class="w-3 h-3"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Help Checklist Card -->
                        <div class="mt-4 pt-4 border-t border-slate-100 space-y-2.5 text-xs text-slate-600">
                            <span class="font-bold text-slate-800 block">Kelengkapan Formulir:</span>
                            <div class="flex items-center gap-2">
                                <i data-lucide="check-circle-2" class="w-4 h-4" :class="judul && lokasi && kategori_kausa_id ? 'text-emerald-600' : 'text-slate-300'"></i>
                                <span :class="judul && lokasi && kategori_kausa_id ? 'text-slate-800 font-medium' : 'text-slate-500'">Identitas & Lokasi Tulungagung</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="check-circle-2" class="w-4 h-4" :class="rawTargetDana > 0 && tanggal_mulai && tanggal_berakhir ? 'text-emerald-600' : 'text-slate-300'"></i>
                                <span :class="rawTargetDana > 0 && tanggal_mulai && tanggal_berakhir ? 'text-slate-800 font-medium' : 'text-slate-500'">Nominal Target & Rentang Waktu</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="check-circle-2" class="w-4 h-4" :class="deskripsi.trim().length >= 20 ? 'text-emerald-600' : 'text-slate-300'"></i>
                                <span :class="deskripsi.trim().length >= 20 ? 'text-slate-800 font-medium' : 'text-slate-500'">Rincian Narasi & Alokasi Dana</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="check-circle-2" class="w-4 h-4" :class="paktaIntegritas ? 'text-emerald-600' : 'text-slate-300'"></i>
                                <span :class="paktaIntegritas ? 'text-slate-800 font-medium' : 'text-slate-500'">Pakta Integritas Disetujui</span>
                            </div>
                        </div>
                    </div>

                    <!-- PPID Contact Card -->
                    <div class="bg-[#123B32] text-white rounded-xl p-4 sm:p-5 shadow-xs">
                        <div class="flex items-start gap-3">
                            <div class="p-2 rounded-lg bg-white/10 text-emerald-300 shrink-0">
                                <i data-lucide="phone-call" class="w-4 h-4"></i>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold font-heading text-white">Butuh Bantuan Verifikasi?</h4>
                                <p class="text-[11px] text-slate-300 mt-1 leading-relaxed">
                                    Hubungi Helpdesk Admin PPID Dinsos Tulungagung pada jam kerja untuk konsultasi kelayakan berkas kausa: <strong>(0355) 321890</strong>.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </aside>

        </div>
    </div>

    <!-- ================= MODAL: KONFIRMASI FINAL PENGIRIMAN ================= -->
    <div x-show="confirmModalOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        
        <div @click.outside="confirmModalOpen = false"
             class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-slate-200 space-y-4">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-full bg-emerald-100 text-[#087F5B] flex items-center justify-center">
                        <i data-lucide="shield-alert" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-[#123B32] font-heading">Konfirmasi Pengiriman Kausa</h3>
                        <p class="text-xs text-slate-500">Pemeriksaan integritas permohonan</p>
                    </div>
                </div>
                <button type="button" @click="confirmModalOpen = false" class="text-slate-400 hover:text-slate-700 p-1 rounded-md">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="space-y-3 text-xs sm:text-sm text-slate-600">
                <p>Apakah Anda yakin seluruh rincian kausa berikut telah valid dan siap ditinjau oleh Admin Verifikator Pemkab Tulungagung?</p>
                
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 space-y-1.5 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Judul Kausa:</span>
                        <span class="font-semibold text-slate-900 text-right max-w-[240px] truncate" x-text="judul || 'Tanpa Judul'"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Target Nominal:</span>
                        <span class="font-bold text-[#087F5B]" x-text="formatRupiah(rawTargetDana)"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Wilayah Sasaran:</span>
                        <span class="font-semibold text-slate-800 text-right max-w-[240px] truncate" x-text="lokasi || 'Kabupaten Tulungagung'"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Status Pasca Kirim:</span>
                        <span class="inline-flex items-center gap-1 font-semibold text-amber-700 bg-amber-100 px-2 py-0.5 rounded text-[11px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Menunggu Verifikasi
                        </span>
                    </div>
                </div>

                <p class="text-[11px] text-slate-500 italic">
                    Setelah dikirim, status permohonan akan beralih ke <strong>Menunggu Verifikasi</strong> dan tidak dapat disunting kembali hingga verifikator memeriksa berkas Anda.
                </p>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" @click="confirmModalOpen = false" class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition-all">
                    Periksa Lagi
                </button>
                <button type="button" @click="submitFinal()" class="px-5 py-2 text-xs sm:text-sm font-semibold rounded-lg bg-[#087F5B] hover:bg-[#066A4C] text-white flex items-center gap-2 shadow-xs transition-all active:scale-95">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Ya, Kirim ke Pemkab</span>
                </button>
            </div>

        </div>
    </div>

</div>

@push('scripts')
<script>
function kausaForm() {
    return {
        step: 1,
        actionType: 'draft',
        confirmModalOpen: false,
        dragOver: false,

        // Step 1
        judul: @json(old('judul', '')),
        kategori_kausa_id: @json(old('kategori_kausa_id', $kategori->first()?->id ?? '')),
        kategori_nama: @json($kategori->firstWhere('id', old('kategori_kausa_id', $kategori->first()?->id ?? null))?->nama ?? 'Bencana Alam'),
        kecamatan: '',
        desa: '',
        lokasi: @json(old('lokasi', '')),
        ringkasan: @json(old('ringkasan', '')),

        // Step 2
        rawTargetDana: @json((int) old('target_dana', 45000000)),
        displayTargetDana: '',
        nominalTerbilang: 'Nol Rupiah',
        tanggal_mulai: @json(old('tanggal_mulai', date('Y-m-d'))),
        tanggal_berakhir: @json(old('tanggal_berakhir', date('Y-m-d', strtotime('+30 days')))),
        durasiHariText: '30 Hari Kalender',

        // Step 3
        deskripsi: @json(old('deskripsi', '')),

        // Step 4
        previewFiles: [],
        paktaIntegritas: true,
        coverImageUrl: 'https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&w=600&q=80',

        init() {
            // Setup initial values
            if (this.rawTargetDana) {
                this.displayTargetDana = new Intl.NumberFormat('id-ID').format(this.rawTargetDana);
                this.nominalTerbilang = this.terbilang(this.rawTargetDana);
            }
            this.calculateDays();
            
            // Auto open step if server error on specific step
            @if (isset($errors))
                @if ($errors->hasAny(['judul', 'kategori_kausa_id', 'lokasi', 'ringkasan']))
                    this.step = 1;
                @elseif ($errors->hasAny(['target_dana', 'tanggal_mulai', 'tanggal_berakhir']))
                    this.step = 2;
                @elseif ($errors->has('deskripsi'))
                    this.step = 3;
                @elseif ($errors->hasAny(['dokumen', 'dokumen.*']))
                    this.step = 4;
                @endif
            @endif

            this.$nextTick(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        },

        goToStep(s) {
            if (s < 1 || s > 4) return;
            this.step = s;
            window.scrollTo({ top: 120, behavior: 'smooth' });
            this.$nextTick(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        },

        syncLokasi() {
            let parts = [];
            if (this.desa.trim()) parts.push(this.desa.trim());
            if (this.kecamatan) parts.push('Kec. ' + this.kecamatan);
            parts.push('Kab. Tulungagung');
            this.lokasi = parts.join(', ');
        },

        handleTargetInput(e) {
            let val = e.target.value.replace(/[^0-9]/g, '');
            if (val) {
                this.rawTargetDana = parseInt(val, 10);
                this.displayTargetDana = new Intl.NumberFormat('id-ID').format(this.rawTargetDana);
                this.nominalTerbilang = this.terbilang(this.rawTargetDana);
            } else {
                this.rawTargetDana = 0;
                this.displayTargetDana = '';
                this.nominalTerbilang = 'Nol Rupiah';
            }
        },

        setPresetNominal(amount) {
            this.rawTargetDana = amount;
            this.displayTargetDana = new Intl.NumberFormat('id-ID').format(amount);
            this.nominalTerbilang = this.terbilang(amount);
        },

        formatRupiah(amount) {
            if (!amount || isNaN(amount)) return 'Rp 0';
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        },

        calculateDays() {
            if (!this.tanggal_mulai || !this.tanggal_berakhir) return;
            const d1 = new Date(this.tanggal_mulai);
            const d2 = new Date(this.tanggal_berakhir);
            const diffTime = d2 - d1;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            if (diffDays > 0) {
                this.durasiHariText = `${diffDays} Hari Kalender`;
            } else {
                this.durasiHariText = `Tanggal tidak valid`;
            }
        },

        insertFormat(before, after) {
            const textarea = document.getElementById('input-deskripsi');
            if (!textarea) return;
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;
            const selected = text.substring(start, end) || 'teks';
            const replacement = before + selected + after;
            textarea.value = text.substring(0, start) + replacement + text.substring(end);
            this.deskripsi = textarea.value;
            textarea.focus();
            textarea.setSelectionRange(start + before.length, start + before.length + selected.length);
        },

        insertPrefix(prefix) {
            const textarea = document.getElementById('input-deskripsi');
            if (!textarea) return;
            const start = textarea.selectionStart;
            const text = textarea.value;
            textarea.value = text.substring(0, start) + "\n" + prefix + text.substring(start);
            this.deskripsi = textarea.value;
            textarea.focus();
        },

        handleFileInput(e) {
            this.processFiles(e.target.files);
        },

        handleFileDrop(e) {
            this.dragOver = false;
            if (e.dataTransfer.files) {
                this.processFiles(e.dataTransfer.files);
                // Also set to file input
                const dataTransfer = new DataTransfer();
                for (let i = 0; i < e.dataTransfer.files.length; i++) {
                    dataTransfer.items.add(e.dataTransfer.files[i]);
                }
                this.$refs.fileInput.files = dataTransfer.files;
            }
        },

        processFiles(fileList) {
            for (let i = 0; i < fileList.length; i++) {
                const f = fileList[i];
                const isImage = f.type.startsWith('image/');
                const sizeKb = (f.size / 1024).toFixed(0);
                const item = {
                    name: f.name,
                    size: f.size,
                    formattedSize: sizeKb > 1024 ? (sizeKb / 1024).toFixed(1) + ' MB' : sizeKb + ' KB',
                    isImage: isImage,
                    previewUrl: isImage ? URL.createObjectURL(f) : null
                };
                this.previewFiles.push(item);
                if (isImage && (!this.coverImageUrl || this.coverImageUrl.includes('unsplash'))) {
                    this.coverImageUrl = item.previewUrl;
                }
            }
            this.$nextTick(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        },

        removeFile(idx) {
            this.previewFiles.splice(idx, 1);
            if (this.previewFiles.length === 0) {
                this.coverImageUrl = 'https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&w=600&q=80';
            } else if (this.previewFiles[0].isImage) {
                this.coverImageUrl = this.previewFiles[0].previewUrl;
            }
        },

        submitDraft() {
            this.actionType = 'draft';
            const actionInput = document.querySelector('input[name="action"]');
            if (actionInput) actionInput.value = 'draft';
            document.getElementById('form-pengajuan-kausa').submit();
        },

        openConfirmModal() {
            this.confirmModalOpen = true;
            this.$nextTick(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        },

        submitFinal() {
            this.confirmModalOpen = false;
            this.actionType = 'submit';
            const actionInput = document.querySelector('input[name="action"]');
            if (actionInput) actionInput.value = 'submit';
            document.getElementById('form-pengajuan-kausa').submit();
        },

        terbilang(angka) {
            if (!angka || isNaN(angka)) return 'Nol Rupiah';
            const bilangan = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
            let temp = '';
            angka = Math.floor(angka);

            if (angka < 12) {
                temp = ' ' + bilangan[angka];
            } else if (angka < 20) {
                temp = this.terbilang(angka - 10) + ' Belas';
            } else if (angka < 100) {
                temp = this.terbilang(Math.floor(angka / 10)) + ' Puluh' + this.terbilang(angka % 10);
            } else if (angka < 200) {
                temp = ' Seratus' + this.terbilang(angka - 100);
            } else if (angka < 1000) {
                temp = this.terbilang(Math.floor(angka / 100)) + ' Ratus' + this.terbilang(angka % 100);
            } else if (angka < 2000) {
                temp = ' Seribu' + this.terbilang(angka - 1000);
            } else if (angka < 1000000) {
                temp = this.terbilang(Math.floor(angka / 1000)) + ' Ribu' + this.terbilang(angka % 1000);
            } else if (angka < 1000000000) {
                temp = this.terbilang(Math.floor(angka / 1000000)) + ' Juta' + this.terbilang(angka % 1000000);
            } else if (angka < 1000000000000) {
                temp = this.terbilang(Math.floor(angka / 1000000000)) + ' Miliar' + this.terbilang(angka % 1000000000);
            }
            return (temp.trim() + ' Rupiah');
        }
    };
}
</script>
@endpush
@endsection
