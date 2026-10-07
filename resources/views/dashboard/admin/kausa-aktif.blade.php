@extends('layouts.dashboard-admin')

@section('title', 'Daftar Kausa Aktif')

@section('content')
<div class="space-y-6" x-data="adminKausaAktif()" x-cloak>

    <!-- Top Breadcrumb & Title -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#D9E2DE]">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-1.5" aria-label="Breadcrumb">
                <a href="{{ route('dashboard.admin') }}" class="hover:text-[#087F5B] transition-colors">Dashboard Admin</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                <span class="text-slate-800 font-semibold">Monitoring Kausa Tayang</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-[#123B32] tracking-tight">
                Monitoring Program Kausa Aktif
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-3xl">
                Pantau perkembangan penghimpunan dana donasi masyarakat, durasi tayang, dan target capaian seluruh kausa resmi yang sedang dipublikasikan di Kabupaten Tulungagung.
            </p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('kausa.index') }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-[#087F5B] text-[#087F5B] hover:bg-emerald-50 text-xs font-semibold transition-colors">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                Katalog Publik Donasi
            </a>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session('status'))
        <div class="rounded-2xl border border-emerald-300 bg-emerald-50 p-4 text-xs sm:text-sm text-emerald-900 flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2.5">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                <span>{{ session('status') }}</span>
            </div>
        </div>
    @endif

    <!-- Metric Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Kausa Tayang</span>
                <i data-lucide="layout-grid" class="w-4 h-4 text-[#087F5B]"></i>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-[#17211E] font-heading">{{ $metrics['totalAktif'] }}</p>
            <span class="text-[10px] text-emerald-700 font-semibold mt-1 block">&bull; Aktif Menerima Donasi</span>
        </div>

        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Target Kebutuhan</span>
                <i data-lucide="target" class="w-4 h-4 text-slate-400"></i>
            </div>
            <p class="text-xl sm:text-2xl font-extrabold text-slate-800 font-heading">
                Rp {{ number_format($metrics['totalTarget'] / 1000000, 1) }}M
            </p>
            <span class="text-[10px] text-slate-400 mt-1 block">Rp {{ number_format($metrics['totalTarget'], 0, ',', '.') }}</span>
        </div>

        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Total Donasi Masuk</span>
                <i data-lucide="wallet" class="w-4 h-4 text-[#087F5B]"></i>
            </div>
            <p class="text-xl sm:text-2xl font-extrabold text-[#087F5B] font-heading">
                Rp {{ number_format($metrics['totalTerkumpul'] / 1000000, 1) }}M
            </p>
            <span class="text-[10px] text-emerald-700 font-semibold mt-1 block">Rp {{ number_format($metrics['totalTerkumpul'], 0, ',', '.') }}</span>
        </div>

        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Capai Target</span>
                <i data-lucide="sparkles" class="w-4 h-4 text-amber-500"></i>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-amber-900 font-heading">{{ $metrics['capaiTarget'] }}</p>
            <span class="text-[10px] text-amber-700 font-semibold mt-1 block">Dana 100% Terpenuhi</span>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl border border-[#D9E2DE] p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.kausa.aktif') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <div class="sm:col-span-5">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Cari Kausa / Instansi</label>
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-3"></i>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Judul program, lokasi, dinas pengaju..."
                           class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#087F5B]/30 focus:border-[#087F5B] bg-[#F6F8F7] focus:bg-white transition-all">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kategori</label>
                <select name="kategori" class="w-full px-3.5 py-2 text-xs sm:text-sm rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#087F5B]/30 focus:border-[#087F5B] bg-[#F6F8F7] focus:bg-white text-slate-800 transition-all">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoris as $k)
                        <option value="{{ $k->id }}" @selected(request('kategori') == $k->id)>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Urutan</label>
                <select name="sort" class="w-full px-3.5 py-2 text-xs sm:text-sm rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#087F5B]/30 focus:border-[#087F5B] bg-[#F6F8F7] focus:bg-white text-slate-800 transition-all">
                    <option value="terbaru" @selected(request('sort') === 'terbaru')>Terbaru Tayang</option>
                    <option value="tercapai" @selected(request('sort') === 'tercapai')>Persentase Tertinggi</option>
                    <option value="sisa_hari" @selected(request('sort') === 'sisa_hari')>Segera Berakhir</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-4 rounded-xl bg-[#087F5B] hover:bg-[#066A4C] text-white font-bold text-xs shadow-xs transition-all flex items-center justify-center gap-1.5 active:scale-95">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Terapkan</span>
                </button>
                @if (request()->hasAny(['search', 'kategori', 'sort']))
                    <a href="{{ route('admin.kausa.aktif') }}" class="py-2 px-3 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-semibold transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Cards Grid Kausa Aktif -->
    @if ($kausas->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($kausas as $item)
                @php
                    $terkumpul = (float) $item->total_terkumpul;
                    $target = (float) $item->target_dana > 0 ? $item->target_dana : 1;
                    $persen = min(100, round(($terkumpul / $target) * 100));
                    $sisaHari = $item->tanggal_berakhir ? max(0, now()->diffInDays($item->tanggal_berakhir, false)) : null;
                @endphp
                <div class="bg-white rounded-3xl border border-[#D9E2DE] p-5 shadow-xs flex flex-col justify-between space-y-4 hover:border-emerald-300 transition-all">
                    <div class="space-y-3">
                        <!-- Top Tag -->
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                {{ $item->kategori->nama ?? 'Sosial' }}
                            </span>
                            @if ($sisaHari !== null)
                                <span class="text-[11px] font-semibold text-slate-500 flex items-center gap-1">
                                    <i data-lucide="calendar" class="w-3 h-3 text-slate-400"></i>
                                    Sisa {{ $sisaHari }} hari
                                </span>
                            @endif
                        </div>

                        <!-- Title & Org -->
                        <div>
                            <h3 class="font-heading font-extrabold text-base text-[#17211E] line-clamp-2 leading-snug">
                                <a href="{{ route('kausa.show', $item->slug) }}" target="_blank" class="hover:text-[#087F5B] transition-colors">
                                    {{ $item->judul }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1 truncate">
                                <i data-lucide="building" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                                <span class="truncate">{{ $item->instansi->nama ?? 'Instansi' }}</span>
                            </p>
                        </div>

                        <!-- Progress Bar & Target -->
                        <div class="space-y-1.5 pt-2 border-t border-slate-100">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-[#087F5B]">Rp {{ number_format($terkumpul, 0, ',', '.') }}</span>
                                <span class="font-extrabold text-slate-700">{{ $persen }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-gradient-to-r from-[#087F5B] to-emerald-400 h-2 rounded-full" style="width: {{ $persen }}%"></div>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-slate-400 pt-0.5">
                                <span>Target: Rp {{ number_format($target, 0, ',', '.') }}</span>
                                <span>{{ $item->donasi->count() }} Donasi</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('dashboard.admin.detail', $item->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors flex items-center gap-1" title="Detail Verifikasi">
                                <i data-lucide="file-search" class="w-3.5 h-3.5"></i>
                                <span>Detail</span>
                            </a>
                            <a href="{{ route('kausa.show', $item->slug) }}" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-white border border-[#D9E2DE] hover:border-[#087F5B] text-slate-600 hover:text-[#087F5B] text-xs transition-colors" title="Lihat Tampilan Publik">
                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>

                        <!-- Tombol Selesaikan Kausa -->
                        <button type="button"
                                @click="openCloseModal({{ $item->id }}, '{{ addslashes($item->judul) }}')"
                                class="px-3 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-900 font-bold text-xs transition-colors flex items-center gap-1"
                                title="Tutup / Tandai Selesai">
                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-amber-700"></i>
                            <span>Selesaikan</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="p-4 bg-white rounded-2xl border border-[#D9E2DE]">
            {{ $kausas->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-3xl border border-[#D9E2DE] p-8 shadow-xs">
            <i data-lucide="layout-list" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i>
            <h3 class="font-bold text-slate-800 text-sm font-heading">Tidak Ada Program Kausa Aktif</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                Kausa yang disetujui pada antrean verifikasi akan otomatis tampil aktif di sini dan di katalog publik.
            </p>
        </div>
    @endif

    <!-- Modal Konfirmasi Selesaikan Kausa -->
    <div x-show="showCloseModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
         style="display: none;">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 text-center space-y-4 shadow-xl border border-slate-200"
             @click.outside="showCloseModal = false">
            <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center mx-auto">
                <i data-lucide="check-circle" class="w-7 h-7"></i>
            </div>
            <div>
                <h3 class="font-display font-bold text-lg text-slate-900">Selesaikan Program Kausa?</h3>
                <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                    Program <strong class="text-slate-900" x-text="targetJudul"></strong> akan ditutup dari penerimaan donasi baru dan instansi akan diminta menyusun Laporan Pertanggungjawaban (LPJ).
                </p>
            </div>
            <form :action="'/dashboard/admin/kausa/' + targetId + '/selesai'" method="POST" class="pt-2">
                @csrf
                <div class="flex items-center gap-3">
                    <button type="button"
                            @click="showCloseModal = false"
                            class="flex-1 py-2.5 px-4 rounded-xl border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 px-4 rounded-xl bg-amber-600 hover:bg-amber-700 text-xs font-bold text-white shadow-xs transition-colors">
                        Ya, Selesaikan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
function adminKausaAktif() {
    return {
        showCloseModal: false,
        targetId: null,
        targetJudul: '',

        openCloseModal(id, judul) {
            this.targetId = id;
            this.targetJudul = judul;
            this.showCloseModal = true;
        }
    }
}
</script>
@endpush
@endsection
