@extends('layouts.dashboard-instansi')

@section('topbar-title', 'Detail Program Kausa')
@section('topbar-subtitle', 'Kabupaten Tulungagung • Monitoring Kausa')

@section('content')
<div class="min-h-screen bg-background" x-data="{ activeTab: 'ringkasan' }">
    <!-- Header -->
    <div class="bg-surface border-b border-border">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
            <!-- Breadcrumbs -->
            <div class="flex items-center gap-2 text-xs text-text-muted mb-3">
                <a href="{{ route('dashboard.instansi') }}" class="hover:text-primary transition-colors flex items-center gap-1 font-medium">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    Kembali ke Dashboard Instansi
                </a>
                <span>/</span>
                <span>Detail Program Kausa</span>
                <span>/</span>
                <span class="text-text-primary font-mono font-semibold">REG-TA/{{ $kausa->created_at->format('Y') }}/{{ str_pad($kausa->id, 3, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="space-y-1.5 min-w-0">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary-subtle text-primary border border-primary/20">
                            {{ $kausa->kategori->nama ?? 'Sosial & Kemanusiaan' }}
                        </span>
                        
                        <!-- Status Badge -->
                        @if ($kausa->status === 'disetujui')
                            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Disetujui Pemkab (Tayang)
                            </span>
                        @elseif ($kausa->status === 'menunggu_verifikasi')
                            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-300">
                                <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600"></i> Menunggu Verifikasi
                            </span>
                        @elseif ($kausa->status === 'perlu_diperbaiki')
                            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-semibold bg-orange-50 text-orange-900 border border-orange-300">
                                <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-orange-600"></i> Perlu Diperbaiki
                            </span>
                        @elseif ($kausa->status === 'ditolak')
                            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-300">
                                <i data-lucide="x-circle" class="w-3.5 h-3.5 text-rose-600"></i> Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-300">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Draf Pengajuan
                            </span>
                        @endif

                        <span class="text-xs text-text-muted font-mono">
                            ID: #{{ $kausa->id }}
                        </span>
                    </div>

                    <h1 class="font-display font-bold text-2xl sm:text-3xl text-text-primary tracking-tight">
                        {{ $kausa->judul }}
                    </h1>

                    <p class="text-xs sm:text-sm text-text-muted flex items-center gap-2">
                        <span class="flex items-center gap-1">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-primary"></i>
                            {{ $kausa->lokasi }}
                        </span>
                        <span>&bull;</span>
                        <span>Diajukan: {{ $kausa->created_at->translatedFormat('d F Y') }}</span>
                    </p>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-3 shrink-0">
                    @if ($kausa->status === 'disetujui')
                        <a href="{{ route('kausa.show', $kausa->slug) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-primary text-primary hover:bg-primary-subtle text-xs font-semibold shadow-xs transition-all">
                            <i data-lucide="external-link" class="w-4 h-4"></i>
                            Lihat di Katalog Publik
                        </a>
                    @endif

                    @if (in_array($kausa->status, ['draf', 'perlu_diperbaiki']))
                        <a href="{{ route('kausa.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-xs font-semibold shadow-sm transition-all active:scale-98">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                            Perbaiki / Edit Kausa
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <!-- Banner jika ada catatan perbaikan dari verifikator -->
        @if ($kausa->status === 'perlu_diperbaiki' && $kausa->catatan_admin)
            <div class="rounded-2xl border-2 border-orange-300 bg-orange-50/70 p-5 shadow-sm">
                <div class="flex items-start gap-3.5">
                    <div class="p-2 rounded-xl bg-orange-100 text-orange-800 shrink-0">
                        <i data-lucide="message-square-warning" class="w-5 h-5 text-orange-700"></i>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-sm text-orange-950">Catatan Perbaikan dari Verifikator Pemkab Tulungagung</h3>
                        <p class="text-xs sm:text-sm text-orange-900 mt-1 leading-relaxed bg-white/80 p-3 rounded-xl border border-orange-200 mt-2 font-mono">
                            "{{ $kausa->catatan_admin }}"
                        </p>
                        <p class="text-[11px] text-orange-800/80 mt-2">
                            Silakan perbarui dokumen atau rincian kausa sesuai instruksi di atas agar dapat segera ditinjau kembali.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- KPI Performance Metrics -->
        @php
            $target = $kausa->target_dana > 0 ? $kausa->target_dana : 1;
            $terkumpul = $kausa->total_terkumpul;
            $pct = $kausa->persentase_progress;
            $sisaHari = max(0, now()->diffInDays($kausa->tanggal_berakhir, false));
            $totalDonatur = $kausa->jumlah_donatur;
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Dana Masuk -->
            <div class="bg-surface rounded-2xl border border-border p-5 shadow-card">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-text-secondary uppercase tracking-wider">Dana Masuk Terkumpul</span>
                    <div class="p-2 rounded-xl bg-emerald-50 text-emerald-800">
                        <i data-lucide="wallet" class="w-5 h-5 text-primary"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="font-display font-bold text-2xl text-primary">
                        Rp {{ number_format($terkumpul, 0, ',', '.') }}
                    </span>
                </div>
                <div class="mt-3">
                    <div class="flex items-center justify-between text-[11px] text-text-muted mb-1">
                        <span>Target: Rp {{ number_format($target, 0, ',', '.') }}</span>
                        <span class="font-bold text-primary">{{ $pct }}%</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-surface-muted overflow-hidden">
                        <div class="h-full bg-primary rounded-full transition-all" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Donatur -->
            <div class="bg-surface rounded-2xl border border-border p-5 shadow-card">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-text-secondary uppercase tracking-wider">Partisipasi Donatur</span>
                    <div class="p-2 rounded-xl bg-surface-muted text-secondary">
                        <i data-lucide="users" class="w-5 h-5 text-primary"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="font-display font-bold text-2xl text-text-primary">{{ $totalDonatur }}</span>
                    <span class="text-xs text-text-muted">Masyarakat</span>
                </div>
                <p class="text-[11px] text-text-muted mt-3 flex items-center gap-1">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-600"></i>
                    Transaksi terverifikasi Midtrans
                </p>
            </div>

            <!-- Card 3: Masa Aktif -->
            <div class="bg-surface rounded-2xl border border-border p-5 shadow-card">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-text-secondary uppercase tracking-wider">Sisa Waktu Kampanye</span>
                    <div class="p-2 rounded-xl bg-surface-muted text-secondary">
                        <i data-lucide="calendar" class="w-5 h-5 text-primary"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="font-display font-bold text-2xl text-text-primary">{{ $sisaHari }}</span>
                    <span class="text-xs text-text-muted">Hari Tersisa</span>
                </div>
                <p class="text-[11px] text-text-muted mt-3">
                    Berakhir: {{ $kausa->tanggal_berakhir ? $kausa->tanggal_berakhir->translatedFormat('d M Y') : '-' }}
                </p>
            </div>

            <!-- Card 4: Kepatuhan SPJ -->
            <div class="bg-surface rounded-2xl border border-border p-5 shadow-card">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-text-secondary uppercase tracking-wider">Status Laporan SPJ</span>
                    <div class="p-2 rounded-xl bg-blue-50 text-blue-700">
                        <i data-lucide="receipt-text" class="w-5 h-5 text-blue-600"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="font-display font-bold text-base text-text-primary">
                        {{ $kausa->laporanDana->count() }} Laporan Diserahkan
                    </span>
                </div>
                <p class="text-[11px] text-text-muted mt-3 flex items-center gap-1">
                    <i data-lucide="file-check" class="w-3.5 h-3.5 text-primary"></i>
                    Standar Akuntabilitas Pemkab Tulungagung
                </p>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-3">
            
            <!-- Main Column: Tabs & Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Tab Headers -->
                <div class="bg-surface rounded-2xl border border-border p-1.5 shadow-xs flex flex-wrap gap-1">
                    <button type="button" @click="activeTab = 'ringkasan'" :class="activeTab === 'ringkasan' ? 'bg-primary text-white shadow-xs' : 'text-text-secondary hover:text-text-primary hover:bg-surface-muted'" class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-xs transition-all">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        Ringkasan & Detail
                    </button>
                    <button type="button" @click="activeTab = 'laporan'" :class="activeTab === 'laporan' ? 'bg-primary text-white shadow-xs' : 'text-text-secondary hover:text-text-primary hover:bg-surface-muted'" class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-xs transition-all">
                        <i data-lucide="receipt" class="w-4 h-4"></i>
                        Realisasi Dana & SPJ ({{ $kausa->laporanDana->count() }})
                    </button>
                    <button type="button" @click="activeTab = 'dokumen'" :class="activeTab === 'dokumen' ? 'bg-primary text-white shadow-xs' : 'text-text-secondary hover:text-text-primary hover:bg-surface-muted'" class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-xs transition-all">
                        <i data-lucide="folder" class="w-4 h-4"></i>
                        Dokumen Legalitas ({{ $kausa->dokumen->count() }})
                    </button>
                    <button type="button" @click="activeTab = 'riwayat'" :class="activeTab === 'riwayat' ? 'bg-primary text-white shadow-xs' : 'text-text-secondary hover:text-text-primary hover:bg-surface-muted'" class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-xs transition-all">
                        <i data-lucide="history" class="w-4 h-4"></i>
                        Riwayat Kurasi
                    </button>
                </div>

                <!-- Tab 1: Ringkasan & Detail -->
                <div x-show="activeTab === 'ringkasan'" class="space-y-6">
                    <div class="bg-surface border border-border rounded-2xl p-6 shadow-card space-y-5">
                        <h2 class="font-display font-bold text-base text-text-primary">Ringkasan Program</h2>
                        <p class="text-sm text-text-secondary leading-relaxed bg-surface-muted/50 p-4 rounded-xl border border-border">
                            {{ $kausa->ringkasan }}
                        </p>

                        <h2 class="font-display font-bold text-base text-text-primary pt-2">Deskripsi Lengkap & Latar Belakang</h2>
                        <div class="prose max-w-none text-sm text-text-secondary leading-relaxed whitespace-pre-wrap">
                            {{ $kausa->deskripsi }}
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-border">
                            <div>
                                <span class="text-xs font-semibold text-text-secondary uppercase tracking-wider block mb-1">Wilayah / Lokasi Sasaran</span>
                                <p class="text-sm font-semibold text-text-primary flex items-center gap-1.5">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-primary"></i>
                                    {{ $kausa->lokasi }}
                                </p>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-text-secondary uppercase tracking-wider block mb-1">Periode Penggalangan</span>
                                <p class="text-sm font-semibold text-text-primary flex items-center gap-1.5">
                                    <i data-lucide="calendar-range" class="w-4 h-4 text-primary"></i>
                                    {{ $kausa->tanggal_mulai ? $kausa->tanggal_mulai->translatedFormat('d M Y') : '-' }} s/d {{ $kausa->tanggal_berakhir ? $kausa->tanggal_berakhir->translatedFormat('d M Y') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Laporan Realisasi Dana & SPJ -->
                <div x-show="activeTab === 'laporan'" class="space-y-6" style="display: none;">
                    <div class="bg-surface border border-border rounded-2xl p-6 shadow-card space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-border">
                            <div>
                                <h3 class="font-display font-bold text-base text-text-primary">Laporan Penggunaan Dana & Nota Fisik</h3>
                                <p class="text-xs text-text-secondary mt-0.5">Seluruh kuitansi, faktur belanja, dan dokumentasi serah terima bantuan warga.</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                Standar PPID Tulungagung
                            </span>
                        </div>

                        @if ($kausa->laporanDana->count() > 0)
                            <div class="space-y-4">
                                @foreach ($kausa->laporanDana as $laporan)
                                    <div class="rounded-xl border border-border p-4 bg-surface hover:border-border-hover transition-all">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="text-xs font-semibold px-2 py-0.5 rounded bg-surface-muted text-text-secondary">
                                                        Tahap {{ $loop->iteration }}
                                                    </span>
                                                    <span class="text-xs text-text-muted">{{ $laporan->created_at->translatedFormat('d M Y') }}</span>
                                                </div>
                                                <h4 class="font-display font-bold text-sm text-text-primary">{{ $laporan->judul }}</h4>
                                                <p class="text-xs text-text-secondary mt-1">{{ $laporan->deskripsi }}</p>
                                                <p class="text-xs font-bold text-primary mt-2">
                                                    Realisasi: Rp {{ number_format($laporan->nominal_digunakan, 0, ',', '.') }}
                                                </p>
                                            </div>
                                            <div class="shrink-0">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-300">
                                                    <i data-lucide="check" class="w-3.5 h-3.5"></i> Terverifikasi
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-10 px-4 bg-surface-muted/30 rounded-xl border border-dashed border-border">
                                <div class="w-12 h-12 rounded-xl bg-surface-muted text-text-muted flex items-center justify-center mx-auto mb-3">
                                    <i data-lucide="receipt" class="w-6 h-6"></i>
                                </div>
                                <h4 class="font-display font-bold text-sm text-text-primary">Belum Ada Laporan SPJ</h4>
                                <p class="text-xs text-text-muted mt-1 max-w-sm mx-auto">
                                    Setelah dana disalurkan atau dicairkan, Anda dapat mengunggah bukti kuitansi toko dan foto serah terima bantuan.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tab 3: Dokumen Legalitas & Berkas Pendukung -->
                <div x-show="activeTab === 'dokumen'" class="space-y-6" style="display: none;">
                    <div class="bg-surface border border-border rounded-2xl p-6 shadow-card space-y-4">
                        <div class="pb-3 border-b border-border">
                            <h3 class="font-display font-bold text-base text-text-primary">Dokumen Pendukung & Verifikasi</h3>
                            <p class="text-xs text-text-secondary mt-0.5">Berkas kelengkapan administrasi yang diunggah saat pengajuan program.</p>
                        </div>

                        @if ($kausa->dokumen->count() > 0)
                            <div class="divide-y divide-border/60">
                                @foreach ($kausa->dokumen as $doc)
                                    <div class="py-3.5 flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-10 h-10 rounded-xl bg-primary-subtle text-primary flex items-center justify-center shrink-0">
                                                <i data-lucide="file-check" class="w-5 h-5"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-semibold text-xs text-text-primary truncate">{{ $doc->nama_file }}</p>
                                                <p class="text-[11px] text-text-muted mt-0.5">
                                                    {{ number_format(($doc->ukuran_file ?? 0) / 1024, 1) }} KB &bull; Berkas Terunggah
                                                </p>
                                            </div>
                                        </div>
                                        <a href="{{ Storage::url($doc->path_file) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-border text-xs font-semibold text-text-secondary hover:text-primary hover:border-primary transition-all">
                                            <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                            Unduh
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-xs text-text-muted">
                                Tidak ada dokumen lampiran tambahan.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tab 4: Riwayat Status & Kurasi -->
                <div x-show="activeTab === 'riwayat'" class="space-y-6" style="display: none;">
                    <div class="bg-surface border border-border rounded-2xl p-6 shadow-card space-y-6">
                        <div class="pb-3 border-b border-border">
                            <h3 class="font-display font-bold text-base text-text-primary">Riwayat Status & Audit Trail</h3>
                            <p class="text-xs text-text-secondary mt-0.5">Jejak perubahan status dan pemeriksaan oleh tim kurasi Pemkab Tulungagung.</p>
                        </div>

                        <div class="space-y-6 pl-2">
                            @forelse ($kausa->riwayatStatus->reverse() as $history)
                                <div class="relative flex gap-4">
                                    <div class="flex flex-col items-center">
                                        <div class="w-4 h-4 rounded-full bg-primary ring-4 ring-primary-subtle shrink-0"></div>
                                        @if (!$loop->last)
                                            <div class="w-0.5 h-full bg-border mt-2"></div>
                                        @endif
                                    </div>
                                    <div class="pb-6 min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <span class="font-display font-bold text-xs text-text-primary uppercase tracking-wide">
                                                Status: {{ ucfirst(str_replace('_', ' ', $history->status_baru)) }}
                                            </span>
                                            <span class="text-xs text-text-muted">&bull;</span>
                                            <span class="text-[11px] text-text-muted">{{ $history->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                                        </div>
                                        @if ($history->catatan)
                                            <div class="text-xs text-text-secondary bg-surface-muted/60 p-3 rounded-xl border border-border mt-2">
                                                {{ $history->catatan }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-text-muted">Belum ada catatan riwayat status.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sidebar Info -->
            <aside class="lg:col-span-1 space-y-6">
                <!-- Legal Entity Box -->
                <div class="bg-surface border border-border rounded-2xl p-6 shadow-card space-y-4">
                    <div class="flex items-center gap-3 pb-3 border-b border-border">
                        <div class="w-10 h-10 rounded-xl bg-secondary text-white flex items-center justify-center font-bold text-sm shrink-0">
                            <i data-lucide="building-2" class="w-5 h-5"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-display font-bold text-xs text-text-primary truncate">
                                {{ $kausa->instansi->nama ?? 'Instansi Pengaju' }}
                            </h3>
                            <span class="inline-flex items-center gap-1 text-[11px] text-emerald-700 font-semibold">
                                <i data-lucide="check-circle" class="w-3 h-3 text-emerald-600"></i> Lembaga Terverifikasi
                            </span>
                        </div>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div>
                            <span class="text-text-muted block text-[11px] uppercase tracking-wider font-semibold">Nomor Registrasi PPID</span>
                            <span class="font-mono text-text-primary font-medium">PPID-TA/{{ $kausa->instansi->id ?? '01' }}/REG</span>
                        </div>
                        <div>
                            <span class="text-text-muted block text-[11px] uppercase tracking-wider font-semibold">Rekening Penampung Kas Daerah</span>
                            <span class="text-text-primary font-medium">Bank Jatim Cabang Tulungagung</span>
                        </div>
                        <div>
                            <span class="text-text-muted block text-[11px] uppercase tracking-wider font-semibold">Pengawasan</span>
                            <span class="text-text-primary font-medium">Dinas Sosial Kab. Tulungagung</span>
                        </div>
                    </div>
                </div>

                <!-- Help & SOP Card -->
                <div class="bg-gradient-to-br from-emerald-50 via-white to-primary-subtle border border-emerald-200/80 rounded-2xl p-6 shadow-xs space-y-3">
                    <div class="flex items-center gap-2 text-primary font-bold text-xs uppercase tracking-wider">
                        <i data-lucide="info" class="w-4 h-4"></i>
                        Bantuan & SOP SPJ
                    </div>
                    <p class="text-xs text-text-secondary leading-relaxed">
                        Jika terdapat kendala revisi berkas atau pelaporan dana, Anda dapat berkonsultasi langsung dengan Tim Verifikator Dinsos Pemkab Tulungagung.
                    </p>
                    <a href="{{ route('pages.kontak') }}" class="inline-flex items-center gap-1 text-xs font-bold text-primary hover:text-primary-hover">
                        Hubungi Layanan Terpadu &rarr;
                    </a>
                </div>
            </aside>

        </div>
    </div>
</div>
@endsection
