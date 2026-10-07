@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background">
    <!-- Breadcrumb & Top Header -->
    <div class="bg-surface border-b border-border">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-primary-subtle text-primary border border-primary/20">
                            Dashboard Donatur
                        </span>
                        <span class="text-xs text-text-muted">&bull;</span>
                        <span class="text-xs text-text-muted">Partisipasi Kebaikan Publik</span>
                    </div>
                    <h1 class="font-display font-bold text-2xl sm:text-3xl text-text-primary tracking-tight">Riwayat Donasi & Jejak Kebaikan Anda</h1>
                    <p class="text-xs sm:text-sm text-text-secondary mt-1">Pantau seluruh kontribusi yang telah Anda salurkan melalui portal resmi Pemkab Tulungagung.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('transparansi.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-border bg-white text-text-secondary hover:text-primary hover:border-primary text-xs font-semibold shadow-xs transition-all">
                        <i data-lucide="file-check-2" class="w-4 h-4 text-primary"></i>
                        Portal Transparansi
                    </a>
                    <a href="{{ route('kausa.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary hover:bg-primary-hover text-white text-xs font-semibold shadow-sm transition-all active:scale-98">
                        <i data-lucide="heart" class="w-4 h-4"></i>
                        Donasi Lagi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        
        <!-- KPI Metrics Grid -->
        <section aria-label="Metrik Donasi">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Total Donasi Berhasil -->
                <div class="bg-surface rounded-2xl border border-border p-5 shadow-card hover:border-border-hover transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-text-secondary uppercase tracking-wider">Total Donasi Berhasil</span>
                        <div class="p-2 rounded-xl bg-emerald-50 text-emerald-800">
                            <i data-lucide="wallet" class="w-5 h-5 text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="font-display font-bold text-2xl sm:text-3xl text-primary">
                            Rp {{ number_format($totalDonasi, 0, ',', '.') }}
                        </span>
                    </div>
                    <p class="text-[11px] text-text-muted mt-2 flex items-center gap-1">
                        <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                        Tercatat resmi di Kas Daerah Pemkab
                    </p>
                </div>

                <!-- Card 2: Kausa Dibantu -->
                <div class="bg-surface rounded-2xl border border-border p-5 shadow-card hover:border-border-hover transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-text-secondary uppercase tracking-wider">Kausa yang Dibantu</span>
                        <div class="p-2 rounded-xl bg-surface-muted text-secondary">
                            <i data-lucide="folder-heart" class="w-5 h-5 text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="font-display font-bold text-2xl sm:text-3xl text-text-primary">{{ $totalKausaDibantu }}</span>
                        <span class="text-xs text-text-muted">Program Sosial</span>
                    </div>
                    <p class="text-[11px] text-text-muted mt-2 flex items-center gap-1">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5 text-amber-500"></i>
                        Dampak nyata bagi warga Tulungagung
                    </p>
                </div>

                <!-- Card 3: Donasi Pending -->
                <div class="bg-surface rounded-2xl border border-border p-5 shadow-card hover:border-border-hover transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-text-secondary uppercase tracking-wider">Menunggu Pembayaran</span>
                        <div class="p-2 rounded-xl bg-amber-50 text-amber-800">
                            <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="font-display font-bold text-2xl sm:text-3xl {{ $statCounts['pending'] > 0 ? 'text-amber-600' : 'text-text-primary' }}">
                            {{ $statCounts['pending'] }}
                        </span>
                        <span class="text-xs text-text-muted">Transaksi</span>
                    </div>
                    <p class="text-[11px] text-text-muted mt-2">
                        @if ($statCounts['pending'] > 0)
                            <span class="text-amber-700 font-medium">Segera selesaikan sebelum kedaluwarsa</span>
                        @else
                            Tidak ada pembayaran tertunda
                        @endif
                    </p>
                </div>

                <!-- Card 4: Total Transaksi -->
                <div class="bg-surface rounded-2xl border border-border p-5 shadow-card hover:border-border-hover transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-text-secondary uppercase tracking-wider">Total Transaksi</span>
                        <div class="p-2 rounded-xl bg-surface-muted text-secondary">
                            <i data-lucide="receipt" class="w-5 h-5 text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="font-display font-bold text-2xl sm:text-3xl text-text-primary">{{ $statCounts['total'] }}</span>
                        <span class="text-xs text-text-muted">Aktivitas</span>
                    </div>
                    <p class="text-[11px] text-text-muted mt-2">
                        Semua riwayat transaksi Anda
                    </p>
                </div>
            </div>
        </section>

        <!-- Filter & Search Toolbar -->
        <section class="bg-surface rounded-2xl border border-border p-5 shadow-xs">
            <form method="GET" action="{{ Route::has('dashboard.donatur') ? route('dashboard.donatur') : route('donatur.dashboard') }}" class="flex flex-col sm:flex-row items-stretch sm:items-end justify-between gap-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 flex-1">
                    <!-- Status Filter -->
                    <div>
                        <label class="block text-xs font-semibold text-text-secondary uppercase tracking-wider mb-1.5">Status Donasi</label>
                        <select name="status" class="w-full px-3.5 py-2 text-xs rounded-xl border border-border bg-white text-text-primary focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <option value="">Semua Status</option>
                            <option value="success" @selected(request('status') === 'success')>✓ Berhasil (Sukses)</option>
                            <option value="pending" @selected(request('status') === 'pending')>⏳ Menunggu Pembayaran</option>
                            <option value="failed" @selected(request('status') === 'failed')>✗ Dibatalkan / Gagal</option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label class="block text-xs font-semibold text-text-secondary uppercase tracking-wider mb-1.5">Urutan</label>
                        <select name="sort" class="w-full px-3.5 py-2 text-xs rounded-xl border border-border bg-white text-text-primary focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <option value="terbaru" @selected(request('sort') === 'terbaru' || !request('sort'))>Terbaru Dilakukan</option>
                            <option value="nominal_tinggi" @selected(request('sort') === 'nominal_tinggi')>Nominal Terbesar</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2 sm:pt-0">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-primary hover:bg-primary-hover text-white text-xs font-semibold shadow-xs transition-all">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                        Terapkan
                    </button>
                    <a href="{{ Route::has('dashboard.donatur') ? route('dashboard.donatur') : route('donatur.dashboard') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-border text-text-secondary hover:text-text-primary hover:bg-surface-muted text-xs font-medium transition-all">
                        Reset
                    </a>
                </div>
            </form>
        </section>

        <!-- Donasi List -->
        <section aria-label="Daftar Donasi">
            @if ($donasi->count() > 0)
                <div class="space-y-4">
                    @foreach ($donasi as $item)
                        @php
                            $target = $item->kausa?->target_dana ?? 1;
                            $terkumpul = $item->kausa?->dana_terkumpul ?? 0;
                            $pct = min(100, round(($terkumpul / max(1, $target)) * 100));
                        @endphp
                        <div class="bg-surface border border-border rounded-2xl p-5 sm:p-6 shadow-card hover:border-border-hover transition-all">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                                
                                <!-- Left Info -->
                                <div class="flex items-start gap-4 flex-1">
                                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-surface-muted overflow-hidden shrink-0 border border-border relative">
                                        @if ($item->kausa?->gambar_sampul ?? false)
                                            <img src="{{ Storage::url($item->kausa->gambar_sampul) }}" alt="{{ $item->kausa->judul ?? 'Kausa' }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-primary-subtle text-primary">
                                                <i data-lucide="hand-heart" class="w-7 h-7"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                {{ $item->kausa?->kategori?->nama ?? 'Sosial' }}
                                            </span>
                                            <span class="text-xs text-text-muted">&bull;</span>
                                            <span class="text-xs text-text-muted flex items-center gap-1">
                                                <i data-lucide="map-pin" class="w-3 h-3"></i>
                                                {{ $item->kausa?->lokasi ?? 'Kab. Tulungagung' }}
                                            </span>
                                        </div>

                                        <a href="{{ $item->kausa ? route('kausa.show', $item->kausa->slug) : '#' }}" class="font-display font-bold text-sm sm:text-base text-text-primary hover:text-primary transition-colors block line-clamp-1">
                                            {{ $item->kausa?->judul ?? 'Program Kausa Sosial' }}
                                        </a>

                                        <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-text-secondary">
                                            <span class="flex items-center gap-1.5">
                                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-text-muted"></i>
                                                {{ $item->created_at ? $item->created_at->translatedFormat('d M Y, H:i') : '-' }} WIB
                                            </span>
                                            <span class="text-text-muted">&bull;</span>
                                            <span class="text-[11px] text-text-muted font-mono">
                                                Kode: {{ $item->pesanan_pembayaran ?? $item->kode_transaksi ?? ('DON-'.$item->id) }}
                                            </span>
                                        </div>

                                        <!-- Progress Kausa Mini Bar -->
                                        <div class="mt-3 max-w-md">
                                            <div class="flex items-center justify-between text-[11px] text-text-muted mb-1">
                                                <span>Capaian Kausa: <strong class="text-text-primary">Rp {{ number_format($terkumpul, 0, ',', '.') }}</strong></span>
                                                <span class="font-semibold text-primary">{{ $pct }}%</span>
                                            </div>
                                            <div class="w-full h-1.5 rounded-full bg-surface-muted overflow-hidden">
                                                <div class="h-full bg-primary rounded-full" style="width: {{ $pct }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Nominal & Status -->
                                <div class="flex flex-row lg:flex-col items-center lg:items-end justify-between border-t lg:border-t-0 pt-4 lg:pt-0 border-border gap-3 shrink-0">
                                    <div class="text-left lg:text-right">
                                        <span class="text-[11px] font-semibold text-text-secondary uppercase tracking-wider block">Nominal Donasi</span>
                                        <span class="font-display font-extrabold text-lg sm:text-xl text-primary block">
                                            Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2 flex-wrap justify-end">
                                        @if ($item->status === 'success' || $item->status === 'berhasil')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-300">
                                                <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-600"></i>
                                                Berhasil
                                            </span>
                                            <a href="{{ route('donasi.receipt', $item->pesanan_pembayaran ?? $item->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200 text-xs font-bold transition-all" title="Unduh / Cetak Kuitansi Resmi">
                                                <i data-lucide="printer" class="w-3.5 h-3.5 text-emerald-700"></i>
                                                <span>Kuitansi Sah</span>
                                            </a>
                                        @elseif ($item->status === 'pending')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-300">
                                                <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600"></i>
                                                Menunggu Bayar
                                            </span>
                                            <a href="{{ route('donasi.payment', $item->pesanan_pembayaran ?? $item->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#087F5B] text-white hover:bg-[#066A4C] text-xs font-bold transition-all shadow-2xs">
                                                <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                                                <span>Bayar</span>
                                            </a>
                                        @elseif (in_array($item->status, ['failed', 'gagal', 'expired']))
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-300">
                                                <i data-lucide="x-circle" class="w-3.5 h-3.5 text-rose-600"></i>
                                                {{ $item->status === 'expired' ? 'Kedaluwarsa' : 'Gagal' }}
                                            </span>
                                        @endif

                                        @if ($item->status === 'success' || $item->status === 'berhasil')
                                            <a href="{{ route('donasi.kuitansi', $item->pesanan_pembayaran) }}" target="_blank"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-[#087F5B] border border-emerald-200 text-xs font-semibold hover:bg-emerald-100 transition-all shadow-2xs">
                                                <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                                                <span>Bukti Donasi</span>
                                            </a>
                                        @elseif ($item->status === 'pending')
                                            <a href="{{ route('donasi.bayar', $item->pesanan_pembayaran) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold transition-all shadow-2xs">
                                                <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                                                <span>Bayar</span>
                                            </a>
                                        @endif

                                        <a href="{{ route('kausa.show', $item->kausa->slug) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-border text-xs font-medium text-text-secondary hover:text-primary hover:border-primary transition-all">
                                            <span>Detail Kausa</span>
                                            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($donasi->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $donasi->links() }}
                    </div>
                @endif
            @else
                <div class="bg-surface rounded-2xl border border-border p-12 text-center shadow-card">
                    <div class="w-16 h-16 rounded-2xl bg-primary-subtle text-primary flex items-center justify-center mx-auto mb-4 ring-8 ring-primary-subtle/50">
                        <i data-lucide="heart-handshake" class="w-8 h-8"></i>
                    </div>
                    <h3 class="font-display font-bold text-lg text-text-primary">Belum Ada Donasi yang Ditemukan</h3>
                    <p class="text-xs sm:text-sm text-text-secondary mt-1 max-w-sm mx-auto">
                        Anda belum memiliki riwayat transaksi donasi atau tidak ada data yang cocok dengan filter yang dipilih.
                    </p>
                    <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                        <a href="{{ route('dashboard.donatur') }}" class="px-4 py-2 rounded-xl border border-border text-xs font-semibold text-text-secondary hover:bg-surface-muted transition-all">
                            Reset Filter
                        </a>
                        <a href="{{ route('kausa.index') }}" class="px-5 py-2 rounded-xl bg-primary hover:bg-primary-hover text-white text-xs font-semibold shadow-xs transition-all">
                            Mulai Donasi Sekarang &rarr;
                        </a>
                    </div>
                </div>
            @endif
        </section>

    </div>
</div>
@endsection
