@extends('layouts.dashboard-admin')

@section('content')
<div x-data="{
    drawerOpen: false,
    modalApprove: false,
    modalReject: {{ ($errors ?? null)?->has('alasan_penolakan') ? 'true' : 'false' }},
    modalRevise: {{ ($errors ?? null)?->has('catatan_revisi') ? 'true' : 'false' }},
    selectedKausa: null,
    openDrawer(item) {
        this.selectedKausa = item;
        this.drawerOpen = true;
    },
    openApprove(item) {
        this.selectedKausa = item;
        this.modalApprove = true;
    },
    openReject(item) {
        this.selectedKausa = item;
        this.modalReject = true;
    },
    openRevise(item) {
        this.selectedKausa = item;
        this.modalRevise = true;
    }
}" class="flex flex-col gap-6">

    {{-- HERO / BANNER HEADER TITLE (Mockup 008) --}}
    <section class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-5 sm:p-6 rounded-xl border border-[#D9E2DE] shadow-xs">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-[#087F5B] mb-1">
                <i data-lucide="shield-alert" class="w-4 h-4"></i>
                <span>Portal Donasi &amp; Transparansi PPID Tulungagung</span>
            </div>
            <h1 class="font-heading font-bold text-2xl sm:text-3xl text-[#123B32] tracking-tight">
                Dashboard Verifikator &amp; Antrean Kausa
            </h1>
            <p class="text-xs sm:text-sm text-[#52615C] mt-1 max-w-2xl leading-relaxed">
                Kurasi kelayakan administrasi, legalitas lembaga pengaju, dan target dana kausa sosial sebelum tayang resmi ke katalog donasi publik Kabupaten Tulungagung.
            </p>
        </div>

        {{-- Quick Top Action Buttons --}}
        <div class="flex items-center gap-2.5 shrink-0">
            <a href="{{ route('dashboard.admin') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-semibold bg-[#EEF3F1] hover:bg-[#D9E2DE] text-[#123B32] transition-all active:scale-95">
                <i data-lucide="rotate-cw" class="w-3.5 h-3.5 text-[#087F5B]"></i>
                <span>Segarkan Antrean</span>
            </a>
            <a href="{{ route('kausa.index') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold bg-[#087F5B] hover:bg-[#066A4C] text-white transition-all shadow-xs active:scale-95">
                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                <span>Pratinjau Portal Publik</span>
            </a>
        </div>
    </section>

    {{-- 5 KARTU STATISTIK (Focal Action pada Menunggu Uji ala Mockup 008) --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        
        {{-- 1. Total Kausa --}}
        <div class="bg-white p-5 rounded-xl border border-[#D9E2DE] shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Kausa</span>
                <div class="w-9 h-9 rounded-lg bg-[#E6F4EF] flex items-center justify-center text-[#087F5B]">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-[#17211E] font-heading">{{ $statusCounts['total'] ?? 0 }}</p>
            <div class="mt-3 pt-2.5 border-t border-[#EEF3F1] text-[11px] text-[#73817C]">Seluruh pengajuan terdaftar</div>
        </div>

        {{-- 2. Menunggu Uji (Focal Action Mockup 008: Border Amber + Animasi Ping + SLA) --}}
        <div class="bg-white p-5 rounded-xl border-2 border-amber-300 shadow-xs relative overflow-hidden flex flex-col justify-between bg-gradient-to-br from-white to-amber-50/50">
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-amber-900 uppercase tracking-wider">Menunggu Uji</span>
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                    </div>
                    <h3 class="font-heading font-bold text-2xl sm:text-3xl text-amber-900 mt-1">
                        {{ $statusCounts['menunggu_verifikasi'] ?? 0 }}
                    </h3>
                </div>
                <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center text-amber-800 shrink-0">
                    <i data-lucide="timer" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-amber-200/70 flex items-center justify-between text-[11px]">
                <span class="text-amber-800 font-medium">Batas SLA: 1x24 Jam</span>
                <span class="font-bold text-amber-900 bg-amber-200/80 px-2 py-0.5 rounded-full text-[10px]">Prioritas Tinggi</span>
            </div>
        </div>

        {{-- 3. Perlu Revisi --}}
        <div class="bg-white p-5 rounded-xl border border-orange-200 bg-orange-50/20 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-orange-800 uppercase tracking-wider">Perlu Revisi</span>
                <div class="w-9 h-9 rounded-lg bg-orange-100 flex items-center justify-center text-orange-700">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-orange-950 font-heading">{{ $statusCounts['perlu_diperbaiki'] ?? 0 }}</p>
            <div class="mt-3 pt-2.5 border-t border-orange-100 text-[11px] text-orange-800">Catatan perbaikan dikirim</div>
        </div>

        {{-- 4. Disetujui --}}
        <div class="bg-white p-5 rounded-xl border border-emerald-200 bg-emerald-50/20 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Disetujui</span>
                <div class="w-9 h-9 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                    <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-emerald-950 font-heading">{{ $statusCounts['disetujui'] ?? 0 }}</p>
            <div class="mt-3 pt-2.5 border-t border-emerald-100 text-[11px] text-emerald-800">Tayang di katalog publik</div>
        </div>

        {{-- 5. Ditolak --}}
        <div class="bg-white p-5 rounded-xl border border-red-200 bg-red-50/20 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-red-800 uppercase tracking-wider">Ditolak</span>
                <div class="w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center text-red-700">
                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-red-950 font-heading">{{ $statusCounts['ditolak'] ?? 0 }}</p>
            <div class="mt-3 pt-2.5 border-t border-red-100 text-[11px] text-red-800">Tidak memenuhi syarat SOP</div>
        </div>

    </section>

    {{-- AUDIT QUEUE SECTION & INTEGRATED TOOLBAR (Mockup 008) --}}
    <section id="antrean-verifikasi" class="bg-white rounded-xl border border-[#D9E2DE] shadow-xs overflow-hidden">
        
        {{-- Toolbar Terpadu --}}
        <div class="p-5 border-b border-[#D9E2DE] flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-[#FDFEFE]">
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h2 class="font-heading font-bold text-base sm:text-lg text-[#123B32]">Antrean Kurasi Pengajuan Kausa Baru</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                        {{ $statusCounts['menunggu_verifikasi'] ?? 0 }} Pengajuan Menunggu
                    </span>
                </div>
                <p class="text-xs text-[#52615C] mt-0.5">Semua pengajuan telah melalui pra-validasi identitas OPD (SSO) atau NIK penanggung jawab lembaga.</p>
            </div>

            {{-- Form Pencarian & Filter Status --}}
            <form method="GET" action="{{ route('dashboard.admin') }}" class="flex flex-wrap items-center gap-2.5">
                <div class="relative w-full sm:w-64">
                    <i data-lucide="search" class="w-4 h-4 text-[#73817C] absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Cari kausa, instansi, lokasi..." 
                        class="w-full pl-9 pr-3 py-2 text-xs rounded-lg border border-[#D9E2DE] focus:outline-none focus:ring-2 focus:ring-[#087F5B] focus:border-transparent bg-white text-[#17211E]"
                    >
                </div>

                <select name="status" class="px-3 py-2 text-xs rounded-lg border border-[#D9E2DE] focus:outline-none focus:ring-2 focus:ring-[#087F5B] bg-white text-[#17211E]">
                    <option value="">Antrean Prioritas (Menunggu &amp; Revisi)</option>
                    <option value="menunggu_verifikasi" @selected(request('status') === 'menunggu_verifikasi')>Menunggu Verifikasi Saja</option>
                    <option value="perlu_diperbaiki" @selected(request('status') === 'perlu_diperbaiki')>Perlu Diperbaiki Saja</option>
                    <option value="disetujui" @selected(request('status') === 'disetujui')>Disetujui Saja</option>
                    <option value="ditolak" @selected(request('status') === 'ditolak')>Ditolak Saja</option>
                </select>

                <button type="submit" class="px-3.5 py-2 rounded-lg bg-[#087F5B] hover:bg-[#066A4C] text-white font-semibold text-xs transition-all shadow-xs active:scale-95 flex items-center gap-1.5">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Terapkan</span>
                </button>

                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('dashboard.admin') }}" class="px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- TABEL ANTREAN KAUSA --}}
        @if ($kausa->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#17211E] border-collapse">
                    <thead>
                        <tr class="bg-[#EEF3F1] border-b border-[#D9E2DE] text-[#52615C] font-semibold uppercase tracking-wider text-[11px]">
                            <th scope="col" class="py-3.5 px-4">Program &amp; Sasaran Lokasi</th>
                            <th scope="col" class="py-3.5 px-4">Pengaju &amp; Legalitas</th>
                            <th scope="col" class="py-3.5 px-4">Kategori</th>
                            <th scope="col" class="py-3.5 px-4">Target Dana</th>
                            <th scope="col" class="py-3.5 px-4">Dokumen Fisik</th>
                            <th scope="col" class="py-3.5 px-4 text-center">Status</th>
                            <th scope="col" class="py-3.5 px-4 text-right">Tindakan Admin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#D9E2DE]/70">
                        @foreach ($kausa as $item)
                            @php
                                $payload = [
                                    'id' => $item->id,
                                    'judul' => $item->judul,
                                    'instansi' => $item->instansi->nama ?? 'Instansi',
                                    'nama_pj' => $item->instansi->nama_pj ?? '-',
                                    'kategori' => $item->kategori->nama ?? 'Sosial',
                                    'target_dana' => 'Rp ' . number_format($item->target_dana, 0, ',', '.'),
                                    'lokasi' => $item->lokasi ?? 'Kab. Tulungagung',
                                    'deskripsi' => $item->deskripsi ?? $item->ringkasan ?? '-',
                                    'created_at' => $item->created_at ? $item->created_at->format('d M Y, H:i') . ' WIB' : '-',
                                    'status' => $item->status,
                                    'dokumen' => $item->dokumen->map(fn($d) => [
                                        'id' => $d->id,
                                        'nama' => $d->nama_file,
                                        'url' => asset('storage/' . $d->path_file),
                                        'mime' => $d->mime_type,
                                        'ukuran' => round(($d->ukuran_file ?? 0) / 1024) . ' KB',
                                    ]),
                                    'verify_url' => route('dashboard.admin.verify', $item->id),
                                    'reject_url' => route('dashboard.admin.reject', $item->id),
                                    'revise_url' => route('dashboard.admin.revise', $item->id),
                                    'detail_url' => route('dashboard.admin.detail', $item->id),
                                ];
                            @endphp
                            <tr class="hover:bg-[#F6F8F7] transition-colors">
                                {{-- Judul & Lokasi --}}
                                <td class="py-4 px-4 align-top max-w-xs">
                                    <a href="{{ route('dashboard.admin.detail', $item->id) }}" class="font-bold text-sm text-[#123B32] hover:text-[#087F5B] transition-colors line-clamp-2">
                                        {{ $item->judul }}
                                    </a>
                                    <div class="flex items-center gap-1.5 text-xs text-[#52615C] mt-1">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#087F5B] shrink-0"></i>
                                        <span class="truncate">{{ $item->lokasi ?? 'Kab. Tulungagung' }}</span>
                                    </div>
                                    <div class="text-[11px] text-[#73817C] mt-1">
                                        Diajukan: {{ $item->created_at ? $item->created_at->format('d M Y, H:i') . ' WIB' : '-' }}
                                    </div>
                                </td>

                                {{-- Pengaju & Legalitas --}}
                                <td class="py-4 px-4 align-top whitespace-nowrap">
                                    <div class="font-semibold text-xs text-[#17211E]">{{ $item->instansi->nama ?? 'Instansi' }}</div>
                                    @if(str_contains(strtolower($item->instansi->user->email ?? ''), 'tulungagung.go.id'))
                                        <div class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-[#087F5B] border border-emerald-200">
                                            <i data-lucide="check-circle" class="w-3 h-3"></i>
                                            SSO @tulungagung.go.id
                                        </div>
                                    @else
                                        <div class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                            <i data-lucide="building-2" class="w-3 h-3"></i>
                                            Lembaga Terdaftar
                                        </div>
                                    @endif
                                    <div class="text-[11px] text-[#73817C] mt-1">PJ: {{ $item->instansi->nama_pj ?? 'Pimpinan' }}</div>
                                </td>

                                {{-- Kategori --}}
                                <td class="py-4 px-4 align-top whitespace-nowrap">
                                    @php
                                        $catName = $item->kategori->nama ?? 'Sosial';
                                        $catLower = strtolower($catName);
                                    @endphp
                                    @if(str_contains($catLower, 'bencana'))
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium bg-red-50 text-red-700 border border-red-200">
                                            {{ $catName }}
                                        </span>
                                    @elseif(str_contains($catLower, 'lansia') || str_contains($catLower, 'dhuafa'))
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                            {{ $catName }}
                                        </span>
                                    @elseif(str_contains($catLower, 'panti') || str_contains($catLower, 'asuhan') || str_contains($catLower, 'anak'))
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                            {{ $catName }}
                                        </span>
                                    @elseif(str_contains($catLower, 'ibadah') || str_contains($catLower, 'masjid'))
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium bg-purple-50 text-purple-700 border border-purple-200">
                                            {{ $catName }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            {{ $catName }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Target Dana --}}
                                <td class="py-4 px-4 align-top whitespace-nowrap">
                                    <div class="font-bold text-sm text-[#123B32]">Rp {{ number_format($item->target_dana, 0, ',', '.') }}</div>
                                    @if($item->tanggal_mulai && $item->tanggal_berakhir)
                                        <div class="text-[11px] text-[#52615C]">Durasi: {{ $item->tanggal_mulai->diffInDays($item->tanggal_berakhir) }} Hari</div>
                                    @else
                                        <div class="text-[11px] text-[#73817C]">Durasi: Fleksibel</div>
                                    @endif
                                </td>

                                {{-- Dokumen Fisik --}}
                                <td class="py-4 px-4 align-top whitespace-nowrap">
                                    <button 
                                        type="button" 
                                        @click="openDrawer({{ json_encode($payload) }})"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-[#D9E2DE] hover:border-[#087F5B] bg-white text-[#087F5B] text-xs font-semibold hover:bg-[#E6F4EF] transition-all"
                                    >
                                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                        <span>{{ $item->dokumen->count() }} Berkas &amp; Foto</span>
                                    </button>
                                    <div class="text-[10px] {{ $item->dokumen->count() > 0 ? 'text-emerald-700' : 'text-slate-400' }} font-medium mt-1">
                                        {{ $item->dokumen->count() > 0 ? 'Lampiran Tersedia' : 'Belum Ada Berkas' }}
                                    </div>
                                </td>

                                {{-- Status (Token Warna Mockup 008) --}}
                                <td class="py-4 px-4 align-top text-center whitespace-nowrap">
                                    @if ($item->status === 'disetujui')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#DCFCE7] text-[#166534] border border-[#86EFAC]">
                                            <i data-lucide="check-circle" class="w-3 h-3 text-[#166534]"></i> Disetujui
                                        </span>
                                    @elseif ($item->status === 'menunggu_verifikasi')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#FEF3C7] text-[#92400E] border border-amber-300">
                                            <i data-lucide="timer" class="w-3 h-3 text-[#92400E]"></i> Menunggu Uji
                                        </span>
                                    @elseif ($item->status === 'perlu_diperbaiki')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#FFEDD5] text-[#9A3412] border border-orange-300">
                                            <i data-lucide="alert-circle" class="w-3 h-3 text-[#9A3412]"></i> Perlu Revisi
                                        </span>
                                    @elseif ($item->status === 'ditolak')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#FEE2E2] text-[#991B1B] border border-red-300">
                                            <i data-lucide="x-circle" class="w-3 h-3 text-[#991B1B]"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-300">
                                            <i data-lucide="edit-3" class="w-3 h-3 text-slate-500"></i> Draf
                                        </span>
                                    @endif
                                </td>

                                {{-- Tindakan Admin --}}
                                <td class="py-4 px-4 align-top text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($item->status !== 'disetujui')
                                            <button 
                                                type="button" 
                                                @click="openApprove({{ json_encode($payload) }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-[#087F5B] hover:bg-[#066A4C] text-white transition-all active:scale-95 shadow-xs" 
                                                title="Setujui Kausa"
                                            >
                                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                                <span class="hidden sm:inline">Setujui</span>
                                            </button>
                                        @endif

                                        @if($item->status === 'menunggu_verifikasi')
                                            <button 
                                                type="button" 
                                                @click="openRevise({{ json_encode($payload) }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-amber-500 hover:bg-amber-600 text-white transition-all active:scale-95 shadow-xs" 
                                                title="Minta Revisi ke Instansi"
                                            >
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                                <span class="hidden sm:inline">Revisi</span>
                                            </button>
                                        @endif

                                        @if($item->status !== 'ditolak')
                                            <button 
                                                type="button" 
                                                @click="openReject({{ json_encode($payload) }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-white border border-red-300 text-red-600 hover:bg-red-50 transition-all active:scale-95" 
                                                title="Tolak Kausa"
                                            >
                                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                                <span class="hidden sm:inline">Tolak</span>
                                            </button>
                                        @endif

                                        <a href="{{ route('dashboard.admin.detail', $item->id) }}" class="p-1.5 rounded-lg border border-[#D9E2DE] text-[#52615C] hover:text-[#087F5B] hover:bg-slate-50 transition-colors" title="Lihat Detail Penuh">
                                            <i data-lucide="external-link" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Table Footer Pagination & Info --}}
            <div class="p-4 bg-[#F6F8F7] border-t border-[#D9E2DE] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#52615C]">
                <div class="flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-[#087F5B]"></span>
                    <span>Menampilkan <strong class="text-[#17211E]">{{ $kausa->firstItem() ?? 0 }} - {{ $kausa->lastItem() ?? 0 }}</strong> dari {{ $kausa->total() }} data pengajuan</span>
                </div>
                <div>
                    {{ $kausa->appends(request()->query())->links() }}
                </div>
            </div>
        @else
            {{-- Empty State (Mockup 008) --}}
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-[#E6F4EF] text-[#087F5B] mx-auto flex items-center justify-center mb-3 shadow-inner">
                    <i data-lucide="check-check" class="w-8 h-8"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-[#123B32]">Semua Pengajuan Telah Ditinjau!</h3>
                <p class="text-xs text-[#52615C] max-w-sm mx-auto mt-1 leading-relaxed">
                    Tidak ada permohonan kausa baru yang menunggu verifikasi saat ini. Sistem akan memperbarui antrean secara otomatis saat ada pengajuan baru.
                </p>
                @if(request()->hasAny(['search', 'status']))
                    <div class="mt-4">
                        <a href="{{ route('dashboard.admin') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-[#087F5B] hover:bg-[#066A4C] text-white text-xs font-semibold shadow-xs transition-all">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                            <span>Tampilkan Semua Antrean</span>
                        </a>
                    </div>
                @endif
            </div>
        @endif

    </section>

    {{-- RIWAYAT KEPUTUSAN VERIFIKASI TERKINI (Mockup 008 & Catatan 5) --}}
    <section class="bg-white rounded-xl border border-[#D9E2DE] p-5 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-heading font-bold text-base text-[#123B32]">Riwayat Keputusan Verifikasi Terkini</h3>
                <p class="text-xs text-[#52615C]">Catatan audit internal sistem peninjauan Pemkab Tulungagung</p>
            </div>
            <span class="text-[11px] text-[#73817C] flex items-center gap-1">
                <i data-lucide="shield-check" class="w-3.5 h-3.5 text-[#087F5B]"></i>
                <span class="font-semibold text-emerald-800">Audit Trail Aktif</span>
            </span>
        </div>

        @if(isset($riwayatKeputusan) && $riwayatKeputusan->count() > 0)
            <div class="space-y-3">
                @foreach($riwayatKeputusan as $r)
                    <div class="flex items-start justify-between p-3.5 rounded-lg bg-[#F6F8F7] border border-[#D9E2DE]/70 text-xs gap-3">
                        <div class="flex items-start gap-3 min-w-0">
                            @if($r->status_baru === 'disetujui')
                                <span class="p-1.5 rounded-full bg-emerald-100 text-[#166534] shrink-0 mt-0.5">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                </span>
                            @elseif($r->status_baru === 'ditolak')
                                <span class="p-1.5 rounded-full bg-red-100 text-red-800 shrink-0 mt-0.5">
                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                </span>
                            @else
                                <span class="p-1.5 rounded-full bg-orange-100 text-orange-800 shrink-0 mt-0.5">
                                    <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                                </span>
                            @endif
                            <div class="min-w-0">
                                <p class="font-semibold text-[#17211E] truncate">{{ $r->kausa->judul ?? 'Kausa #' . $r->kausa_id }}</p>
                                <p class="text-[11px] text-[#52615C] mt-0.5">
                                    Pengaju: <strong class="text-slate-700">{{ $r->kausa->instansi->nama ?? 'Instansi' }}</strong> &bull; Verifikator: {{ $r->user->name ?? 'Admin Verifikator' }}
                                </p>
                                @if($r->catatan)
                                    <p class="text-[11px] text-slate-600 mt-1 italic bg-white p-2 rounded border border-[#D9E2DE]/50">
                                        &ldquo;{{ $r->catatan }}&rdquo;
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            @if($r->status_baru === 'disetujui')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#DCFCE7] text-[#166534] border border-[#86EFAC]">Disetujui</span>
                            @elseif($r->status_baru === 'ditolak')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#FEE2E2] text-[#991B1B] border border-red-300">Ditolak</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#FFEDD5] text-[#9A3412] border border-orange-300">Revisi</span>
                            @endif
                            <p class="text-[10px] text-[#73817C] mt-1">{{ $r->created_at ? $r->created_at->format('d M Y, H:i') : '-' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-6 text-center text-xs text-[#73817C] bg-[#F6F8F7] rounded-lg border border-dashed border-[#D9E2DE]">
                Belum ada riwayat keputusan verifikasi yang tercatat pada sistem.
            </div>
        @endif
    </section>

    {{-- DRAWER: INSPEKSI BERKAS & LEGALITAS KAUSA (Mockup 008) --}}
    <div 
        x-show="drawerOpen" 
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="drawerOpen = false" 
        class="fixed inset-0 bg-black/50 z-50 backdrop-blur-xs" 
        style="display: none;"
    ></div>

    <aside 
        x-show="drawerOpen" 
        x-transition:enter="transform transition ease-in-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition ease-in-out duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 z-50 w-full max-w-lg bg-white shadow-2xl flex flex-col" 
        style="display: none;"
    >
        {{-- Drawer Header --}}
        <div class="p-5 border-b border-[#D9E2DE] flex items-center justify-between bg-[#123B32] text-white">
            <div>
                <div class="flex items-center gap-2">
                    <i data-lucide="file-check-2" class="w-5 h-5 text-emerald-300"></i>
                    <h3 class="font-heading font-bold text-base">Inspeksi Berkas &amp; Legalitas Kausa</h3>
                </div>
                <p class="text-xs text-emerald-200 mt-0.5 font-medium" x-text="selectedKausa ? 'ID Kausa #' + selectedKausa.id : 'Inspeksi Berkas'"></p>
            </div>
            <button @click="drawerOpen = false" type="button" class="p-1.5 rounded-lg text-emerald-100 hover:bg-white/10 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        {{-- Drawer Content Scrollable --}}
        <div class="flex-1 overflow-y-auto p-5 space-y-5 text-xs text-[#17211E]">
            <template x-if="selectedKausa">
                <div class="space-y-5">
                    {{-- Summary Card --}}
                    <div class="p-4 rounded-xl bg-[#F6F8F7] border border-[#D9E2DE]">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800" x-text="selectedKausa.kategori"></span>
                        <h4 class="font-heading font-bold text-sm text-[#123B32] mt-2 leading-snug" x-text="selectedKausa.judul"></h4>
                        <div class="grid grid-cols-2 gap-3 mt-3 pt-3 border-t border-[#D9E2DE] text-[11px]">
                            <div>
                                <span class="text-[#73817C] block">Instansi Pemohon</span>
                                <strong class="text-[#123B32] font-semibold" x-text="selectedKausa.instansi"></strong>
                                <span class="text-[10px] text-[#52615C] block" x-text="'PJ: ' + selectedKausa.nama_pj"></span>
                            </div>
                            <div>
                                <span class="text-[#73817C] block">Target Penggalangan</span>
                                <strong class="text-[#087F5B] font-bold text-xs" x-text="selectedKausa.target_dana"></strong>
                                <span class="text-[10px] text-[#52615C] block" x-text="selectedKausa.lokasi"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Description / Kronologi --}}
                    <div>
                        <h5 class="font-bold text-[#123B32] uppercase tracking-wider text-[11px] mb-1.5 flex items-center gap-1.5">
                            <i data-lucide="align-left" class="w-3.5 h-3.5 text-[#087F5B]"></i>
                            <span>Kronologi &amp; Latar Belakang Kebutuhan</span>
                        </h5>
                        <div class="p-3.5 rounded-xl border border-[#D9E2DE] bg-white leading-relaxed text-[#52615C] whitespace-pre-line" x-text="selectedKausa.deskripsi"></div>
                    </div>

                    {{-- Berkas & Lampiran --}}
                    <div>
                        <h5 class="font-bold text-[#123B32] uppercase tracking-wider text-[11px] mb-2 flex items-center gap-1.5">
                            <i data-lucide="file-badge" class="w-3.5 h-3.5 text-[#087F5B]"></i>
                            <span>Berkas Legalitas Terunggah (<span x-text="selectedKausa.dokumen.length"></span> File)</span>
                        </h5>

                        <template x-if="selectedKausa.dokumen.length === 0">
                            <div class="p-4 rounded-lg bg-amber-50 border border-amber-200 text-amber-900 text-xs">
                                Instansi belum melampirkan berkas dokumen fisik untuk kausa ini.
                            </div>
                        </template>

                        <div class="space-y-2">
                            <template x-for="(doc, idx) in selectedKausa.dokumen" :key="doc.id">
                                <div class="p-3 rounded-lg border border-[#D9E2DE] bg-white flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <i data-lucide="file-check" class="w-5 h-5 text-[#087F5B] shrink-0"></i>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-xs text-[#17211E] truncate" x-text="doc.nama"></p>
                                            <p class="text-[10px] text-[#73817C]" x-text="doc.ukuran + ' • ' + (doc.mime || 'Dokumen')"></p>
                                        </div>
                                    </div>
                                    <a :href="doc.url" target="_blank" class="px-2.5 py-1 rounded bg-[#EEF3F1] hover:bg-[#087F5B] hover:text-white text-[#123B32] font-semibold text-[10px] transition-colors shrink-0">
                                        Buka File
                                    </a>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Drawer Footer Actions --}}
        <div class="p-4 border-t border-[#D9E2DE] bg-[#F6F8F7] flex items-center gap-2">
            <template x-if="selectedKausa && selectedKausa.status !== 'disetujui'">
                <button 
                    @click="drawerOpen = false; openApprove(selectedKausa)" 
                    type="button" 
                    class="flex-1 py-2.5 px-3 rounded-lg bg-[#087F5B] hover:bg-[#066A4C] text-white font-semibold text-xs transition-all flex items-center justify-center gap-1.5 shadow-xs"
                >
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Setujui</span>
                </button>
            </template>

            <template x-if="selectedKausa && selectedKausa.status === 'menunggu_verifikasi'">
                <button 
                    @click="drawerOpen = false; openRevise(selectedKausa)" 
                    type="button" 
                    class="py-2.5 px-3 rounded-lg bg-amber-500 hover:bg-amber-600 text-white font-semibold text-xs transition-all flex items-center justify-center gap-1.5 shadow-xs"
                >
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                    <span>Revisi</span>
                </button>
            </template>

            <template x-if="selectedKausa && selectedKausa.status !== 'ditolak'">
                <button 
                    @click="drawerOpen = false; openReject(selectedKausa)" 
                    type="button" 
                    class="py-2.5 px-3 rounded-lg bg-white border border-red-300 text-red-600 hover:bg-red-50 font-semibold text-xs transition-all flex items-center justify-center gap-1.5"
                >
                    <i data-lucide="x" class="w-4 h-4"></i>
                    <span>Tolak</span>
                </button>
            </template>

            <template x-if="selectedKausa">
                <a :href="selectedKausa.detail_url" class="p-2.5 rounded-lg border border-[#D9E2DE] bg-white text-[#52615C] hover:text-[#087F5B] hover:bg-slate-50 transition-colors" title="Buka Detail Lengkap">
                    <i data-lucide="external-link" class="w-4 h-4"></i>
                </a>
            </template>
        </div>
    </aside>

    {{-- MODAL: KONFIRMASI PERSETUJUAN KAUSA (Mockup 008 & Catatan 6) --}}
    <div 
        x-show="modalApprove" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4" 
        style="display: none;"
    >
        <div @click="modalApprove = false" class="fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity"></div>
        <div class="relative bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-[#D9E2DE] z-10">
            <div class="w-12 h-12 rounded-full bg-emerald-100 text-[#087F5B] flex items-center justify-center mx-auto mb-4">
                <i data-lucide="badge-check" class="w-6 h-6"></i>
            </div>
            
            <div class="text-center">
                <h3 class="font-heading font-bold text-lg text-[#123B32]">Setujui &amp; Publikasikan Kausa?</h3>
                <p class="text-xs text-[#52615C] mt-1.5 leading-relaxed">
                    Kausa ini akan langsung tayang pada halaman utama portal publik Pemkab Tulungagung dengan badge resmi <strong class="text-[#087F5B]">&ldquo;Terverifikasi Pemkab&rdquo;</strong> serta dapat menerima donasi publik.
                </p>
            </div>

            <template x-if="selectedKausa">
                <div class="mt-4 p-3 bg-[#EEF3F1] rounded-xl text-xs space-y-1">
                    <div class="font-bold text-[#123B32] truncate" x-text="selectedKausa.judul"></div>
                    <div class="text-[11px] text-[#52615C]">Instansi: <span class="font-bold text-[#087F5B]" x-text="selectedKausa.instansi"></span></div>
                    <div class="text-[11px] text-[#52615C]">Target Anggaran: <span class="font-bold text-[#123B32]" x-text="selectedKausa.target_dana"></span></div>
                </div>
            </template>

            <form x-bind:action="selectedKausa ? selectedKausa.verify_url : '#'" method="POST" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-[#17211E] mb-1">Catatan Persetujuan (Opsional)</label>
                    <input type="text" name="catatan" placeholder="Contoh: Dokumen lengkap dan telah disetujui sesuai regulasi" class="w-full text-xs px-3 py-2 rounded-lg border border-[#D9E2DE] focus:ring-2 focus:ring-[#087F5B] focus:outline-none">
                </div>

                <div class="flex items-center gap-2.5 pt-2">
                    <button @click="modalApprove = false" type="button" class="w-1/2 py-2.5 rounded-lg border border-[#D9E2DE] text-[#52615C] hover:bg-slate-100 font-semibold text-xs transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="w-1/2 py-2.5 rounded-lg bg-[#087F5B] hover:bg-[#066A4C] text-white font-semibold text-xs transition-all shadow-xs active:scale-95 flex items-center justify-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span>Ya, Setujui Kausa</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: MINTA REVISI KAUSA (Catatan 6) --}}
    <div 
        x-show="modalRevise" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4" 
        style="display: none;"
    >
        <div @click="modalRevise = false" class="fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity"></div>
        <div class="relative bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-amber-300 z-10">
            <div class="flex items-start justify-between pb-3 border-b border-[#D9E2DE]">
                <div class="flex items-center gap-2 text-amber-700">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-700">
                        <i data-lucide="alert-circle" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-base text-[#123B32]">Formulir Permintaan Revisi Kausa</h3>
                        <p class="text-xs text-[#73817C]">Instruksi Perbaikan Berkas / Anggaran ke Instansi</p>
                    </div>
                </div>
                <button @click="modalRevise = false" type="button" class="text-[#73817C] hover:text-[#17211E]">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <template x-if="selectedKausa">
                <p class="text-xs text-[#52615C] my-3 leading-relaxed">
                    Pengajuan kausa: <strong class="text-[#17211E]" x-text="selectedKausa.judul"></strong> akan dikembalikan ke instansi untuk diperbaiki.
                </p>
            </template>

            <form x-bind:action="selectedKausa ? selectedKausa.revise_url : '#'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-[#17211E] mb-1">
                        Catatan &amp; Rincian yang Perlu Diperbaiki <span class="text-rose-500">*</span>
                    </label>
                    <textarea 
                        name="catatan_revisi" 
                        rows="4" 
                        required 
                        minlength="10"
                        placeholder="Jelaskan secara spesifik bagian yang wajib direvisi oleh instansi pengaju (misal: lampiran RAB perlu distempel pimpinan)..." 
                        class="w-full text-xs p-3 rounded-lg border border-[#D9E2DE] focus:ring-2 focus:ring-amber-500 focus:outline-none text-[#17211E]"
                    >{{ old('catatan_revisi') }}</textarea>
                    @error('catatan_revisi')
                        <p class="text-[11px] text-red-600 font-medium mt-1">
                            <i data-lucide="alert-circle" class="w-3 h-3 inline mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-[#D9E2DE]">
                    <button @click="modalRevise = false" type="button" class="px-4 py-2 rounded-lg border border-[#D9E2DE] text-[#52615C] hover:bg-slate-100 font-semibold text-xs transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white font-semibold text-xs transition-all shadow-xs active:scale-95 flex items-center gap-1.5">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <span>Kirim Permintaan Revisi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: FORM PENOLAKAN KAUSA (Mockup 008 & Catatan 6) --}}
    <div 
        x-show="modalReject" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4" 
        style="display: none;"
    >
        <div @click="modalReject = false" class="fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity"></div>
        <div class="relative bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-red-200 z-10">
            <div class="flex items-start justify-between pb-3 border-b border-[#D9E2DE]">
                <div class="flex items-center gap-2 text-red-600">
                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-red-600">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-base text-[#123B32]">Formulir Penolakan Kausa</h3>
                        <p class="text-xs text-[#73817C]">Kewajiban Pencatatan Alasan Regulasi Verifikator</p>
                    </div>
                </div>
                <button @click="modalReject = false" type="button" class="text-[#73817C] hover:text-[#17211E]">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <template x-if="selectedKausa">
                <p class="text-xs text-[#52615C] my-3 leading-relaxed">
                    Menolak pengajuan: <strong class="text-[#17211E]" x-text="selectedKausa.judul"></strong>. Pengaju akan menerima notifikasi resmi dan alasan penolakan ini secara transparan.
                </p>
            </template>

            <form x-bind:action="selectedKausa ? selectedKausa.reject_url : '#'" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-[#17211E] mb-1">
                        Kategori Alasan Penolakan
                    </label>
                    <select class="w-full text-xs p-2.5 rounded-lg border border-[#D9E2DE] focus:ring-2 focus:ring-red-500 bg-white text-[#17211E]">
                        <option value="legalitas_kurang">Dokumen Legalitas / Izin Operasional Tidak Lengkap</option>
                        <option value="di_luar_wilayah">Lokasi di luar Wilayah Administratif Kab. Tulungagung</option>
                        <option value="duplikasi">Terindikasi Duplikasi dengan Kausa Lain yang Sedang Aktif</option>
                        <option value="rab_tidak_wajar">Rincian Anggaran Biaya (RAB) Tidak Realistis / Tanpa Bukti</option>
                        <option value="bukan_lembaga">Pengaju Bukan Instansi Pemerintah / Lembaga Berbadan Hukum</option>
                        <option value="lainnya">Alasan Teknis Khusus Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#17211E] mb-1">
                        Catatan &amp; Instruksi Verifikator (Wajib Diisi Min. 15 Karakter) <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="alasan_penolakan" 
                        rows="4" 
                        required 
                        minlength="15"
                        placeholder="Tuliskan alasan penolakan secara spesifik, cantumkan dokumen apa yang keliru atau pasal regulasi yang belum dipenuhi pemohon..." 
                        class="w-full text-xs p-3 rounded-lg border border-[#D9E2DE] focus:ring-2 focus:ring-red-500 focus:outline-none text-[#17211E]"
                    >{{ old('alasan_penolakan') }}</textarea>
                    @error('alasan_penolakan')
                        <p class="text-[11px] text-red-600 font-medium mt-1">
                            <i data-lucide="alert-circle" class="w-3 h-3 inline mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-[#D9E2DE]">
                    <button @click="modalReject = false" type="button" class="px-4 py-2 rounded-lg border border-[#D9E2DE] text-[#52615C] hover:bg-slate-100 font-semibold text-xs transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold text-xs transition-all shadow-xs active:scale-95 flex items-center gap-1.5">
                        <i data-lucide="x-circle" class="w-4 h-4"></i>
                        <span>Tolak Kausa &amp; Kirim Catatan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
