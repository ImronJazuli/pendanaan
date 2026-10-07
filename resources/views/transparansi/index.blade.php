@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F6F8F7] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-[#73817C]">
            <a href="{{ route('landing') }}" class="hover:text-[#087F5B] flex items-center gap-1">
                <i data-lucide="home" class="w-3.5 h-3.5"></i> Beranda
            </a>
            <i data-lucide="chevron-right" class="w-3 h-3 text-slate-300"></i>
            <span class="text-[#17211E] font-semibold">Pusat Transparansi Penyaluran Dana</span>
        </nav>

        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-[#123B32] via-[#0E322A] to-[#0A241E] rounded-3xl p-6 sm:p-8 text-white shadow-lg border border-emerald-900/60 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 space-y-3 max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-semibold">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> Keterbukaan Informasi Publik (UU No. 14 Tahun 2008)
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-white tracking-tight">
                    Pusat Transparansi &amp; Akuntabilitas Penyaluran Dana
                </h1>
                <p class="text-xs sm:text-sm text-emerald-100/80 leading-relaxed">
                    Setiap rupiah donasi masyarakat diaudit secara terbuka. Dokumen kuitansi toko, foto penyerahan fisik barang, dan Berita Acara Serah Terima (BAST) dipublikasikan untuk pengawasan bersama.
                </p>
            </div>
        </div>

        <!-- Metric Counter Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Laporan Dipublikasi</span>
                    <i data-lucide="file-check-2" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <p class="text-2xl sm:text-3xl font-extrabold text-[#17211E] font-heading">{{ $totalLaporanCount ?? $laporan->total() }} Laporan</p>
                <span class="text-[11px] text-emerald-700 font-semibold mt-1 block">&check; 100% Diaudit Verifikator Pemkab</span>
            </div>

            <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Dana Tervalidasi</span>
                    <i data-lucide="wallet" class="w-5 h-5 text-[#087F5B]"></i>
                </div>
                <p class="text-xl sm:text-2xl font-bold text-[#123B32] font-heading">
                    Rp {{ number_format($totalDanaDisalurkan ?? 0, 0, ',', '.') }}
                </p>
                <span class="text-[11px] text-slate-500 mt-1 block">Tersalurkan Tepat Sasaran</span>
            </div>

            <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Audit Lapangan</span>
                    <i data-lucide="eye" class="w-5 h-5 text-blue-600"></i>
                </div>
                <p class="text-lg font-bold text-[#123B32] font-heading">Inspektorat &amp; Dinsos</p>
                <span class="text-[11px] text-slate-500 mt-1 block">Pemeriksaan Berkala Multi-Instansi</span>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 shadow-xs">
            <form method="GET" action="{{ route('transparansi.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                <div class="sm:col-span-6">
                    <label class="block text-xs font-bold text-[#17211E] mb-1.5 uppercase tracking-wider">Cari Laporan Program</label>
                    <div class="relative">
                        <i data-lucide="search" class="w-4 h-4 text-emerald-700 absolute left-3.5 top-3"></i>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Judul kausa..." 
                            class="w-full pl-10 pr-4 py-2.5 bg-[#F6F8F7] border border-[#D9E2DE] focus:border-[#087F5B] focus:bg-white rounded-xl text-xs sm:text-sm outline-none transition-all"
                        >
                    </div>
                </div>

                <div class="sm:col-span-4">
                    <label class="block text-xs font-bold text-[#17211E] mb-1.5 uppercase tracking-wider">Kategori Program</label>
                    <select name="kategori" class="w-full px-3.5 py-2.5 bg-[#F6F8F7] border border-[#D9E2DE] focus:border-[#087F5B] focus:bg-white rounded-xl text-xs sm:text-sm outline-none transition-all">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoris as $k)
                            <option value="{{ $k->id }}" @selected(request('kategori') == $k->id)>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2 flex items-end gap-2">
                    <button type="submit" class="flex-1 py-2.5 bg-[#087F5B] hover:bg-[#066A4C] text-white font-bold text-xs rounded-xl transition-all shadow-xs active:scale-95 flex items-center justify-center gap-1.5">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                        <span>Filter</span>
                    </button>
                    @if (request()->hasAny(['search', 'kategori']))
                        <a href="{{ route('transparansi.index') }}" class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Laporan Cards List -->
        @if ($laporan->count() > 0)
            <div class="space-y-6">
                @foreach ($laporan as $item)
                    @php
                        $danaTerkumpul = (float) ($item->kausa->dana_terkumpul ?? $item->kausa->total_terkumpul ?? 0);
                        $danaDigunakan = (float) ($item->total_digunakan ?? 0);
                        $persen = $danaTerkumpul > 0 ? min(100, round(($danaDigunakan / $danaTerkumpul) * 100)) : 0;
                    @endphp
                    <div class="bg-white rounded-3xl border border-[#D9E2DE] overflow-hidden shadow-xs">
                        <!-- Card Header -->
                        <div class="p-6 border-b border-[#D9E2DE] flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-[#F6F8F7]">
                            <div class="space-y-1">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#E6F4EF] text-[#087F5B]">
                                    {{ $item->kausa->kategori->nama ?? 'Sosial' }}
                                </span>
                                <h3 class="font-heading font-extrabold text-base sm:text-lg text-[#17211E] mt-1">
                                    <a href="{{ route('kausa.show', $item->kausa->slug) }}" class="hover:text-[#087F5B] transition-colors">
                                        {{ $item->judul ?? $item->kausa->judul }}
                                    </a>
                                </h3>
                                <p class="text-xs text-slate-500">
                                    Program: <strong class="text-slate-800">{{ $item->kausa->judul }}</strong> &bull; Lokasi: {{ $item->kausa->lokasi ?? 'Kab. Tulungagung' }} &bull; Penanggung Jawab: <strong class="text-slate-700">{{ $item->kausa->instansi->nama ?? 'Instansi' }}</strong>
                                </p>
                            </div>
                            <a href="{{ route('kausa.show', $item->kausa->slug) }}" class="px-4 py-2 rounded-xl bg-white border border-[#D9E2DE] hover:border-[#087F5B] text-slate-700 hover:text-[#087F5B] text-xs font-bold transition-all shadow-xs shrink-0 flex items-center gap-1.5 self-start sm:self-center">
                                <span>Lihat Kausa</span>
                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>

                        <!-- Card Body Metrics -->
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200">
                                    <span class="text-[11px] text-slate-400 block mb-0.5">Target Total</span>
                                    <p class="font-bold text-slate-800 text-sm">Rp {{ number_format($item->kausa->target_dana, 0, ',', '.') }}</p>
                                </div>
                                <div class="p-3.5 rounded-2xl bg-emerald-50/60 border border-emerald-200">
                                    <span class="text-[11px] text-emerald-800 block mb-0.5">Dana Terkumpul</span>
                                    <p class="font-extrabold text-[#087F5B] text-sm">Rp {{ number_format($danaTerkumpul, 0, ',', '.') }}</p>
                                </div>
                                <div class="p-3.5 rounded-2xl bg-amber-50/60 border border-amber-200">
                                    <span class="text-[11px] text-amber-800 block mb-0.5">Telah Disalurkan</span>
                                    <p class="font-extrabold text-amber-900 text-sm">Rp {{ number_format($danaDigunakan, 0, ',', '.') }}</p>
                                </div>
                                <div class="p-3.5 rounded-2xl bg-blue-50/60 border border-blue-200">
                                    <span class="text-[11px] text-blue-800 block mb-0.5">Sisa Saldo Kas</span>
                                    <p class="font-extrabold text-blue-900 text-sm">Rp {{ number_format(max(0, $danaTerkumpul - $danaDigunakan), 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="space-y-1.5">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="font-semibold text-slate-600">Realisasi Penyerapan Dana</span>
                                    <span class="font-bold text-[#087F5B]">{{ $persen }}% Terserap</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                    <div class="bg-gradient-to-r from-[#087F5B] to-emerald-400 h-2.5 rounded-full" style="width: {{ $persen }}%"></div>
                                </div>
                            </div>

                            <!-- Rincian Nota & Transaksi -->
                            @if ($item->rincian && $item->rincian->count() > 0)
                                <div class="pt-4 border-t border-slate-100 space-y-3">
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rincian Pengeluaran Terverifikasi</h4>
                                    <div class="space-y-2">
                                        @foreach ($item->rincian as $rincian)
                                            <div class="flex items-center justify-between p-3 rounded-xl bg-[#F6F8F7] border border-[#D9E2DE] text-xs">
                                                <div class="flex items-center gap-3">
                                                    <i data-lucide="receipt" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                                                    <div>
                                                        <p class="font-bold text-slate-800">{{ $rincian->uraian ?? $rincian->keterangan }}</p>
                                                        <span class="text-[10px] text-slate-400">
                                                            Penerima: {{ $rincian->penerima_manfaat ?? '-' }} &bull; Tgl: {{ $rincian->tanggal_pengeluaran ? $rincian->tanggal_pengeluaran->format('d M Y') : ($rincian->created_at ? $rincian->created_at->format('d M Y') : '-') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <span class="font-bold text-slate-900">Rp {{ number_format($rincian->nominal, 0, ',', '.') }}</span>
                                                    @if ($rincian->path_bukti)
                                                        <a href="{{ asset('storage/' . $rincian->path_bukti) }}" target="_blank" class="px-2 py-1 rounded bg-white border border-slate-200 text-[#087F5B] hover:underline font-semibold text-[10px] flex items-center gap-1" title="Lihat Bukti Nota">
                                                            <i data-lucide="paperclip" class="w-3 h-3"></i>
                                                            <span>Nota</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-3xl border border-slate-200 p-8 shadow-xs">
                <i data-lucide="file-search" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i>
                <h3 class="font-heading font-bold text-slate-800 text-base">Belum Ada Laporan Transparansi Ditayangkan</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    Kausa yang selesai atau sedang dalam tahap penyaluran akan mengunggah nota kuitansi fisik di sini setelah diaudit verifikator.
                </p>
            </div>
        @endif

    </div>
</div>
@endsection
