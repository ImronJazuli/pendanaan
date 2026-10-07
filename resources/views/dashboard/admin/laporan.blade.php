@extends('layouts.dashboard-admin')

@section('title', 'Verifikasi Laporan Transparansi Dana (LPJ)')

@section('content')
<div class="space-y-6" x-data="adminLaporan()" x-cloak>

    <!-- Top Breadcrumb & Title -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#D9E2DE]">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-1.5" aria-label="Breadcrumb">
                <a href="{{ route('dashboard.admin') }}" class="hover:text-[#087F5B] transition-colors">Dashboard Admin</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                <span class="text-slate-800 font-semibold">Verifikasi Laporan Penggunaan Dana</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-[#123B32] tracking-tight">
                Verifikasi Laporan Transparansi Dana (LPJ)
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-3xl">
                Audit rincian pembelanjaan dana donasi, kuitansi bermeterai, nota toko, serta dokumentasi Berita Acara Serah Terima (BAST) sebelum dipublikasikan ke portal transparansi publik.
            </p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('transparansi.index') }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-[#087F5B] text-[#087F5B] hover:bg-emerald-50 text-xs font-semibold transition-colors">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                Portal Transparansi Publik
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

    @if ($errors->any())
        <div class="rounded-2xl border border-red-300 bg-red-50 p-4 text-xs text-red-900 space-y-1 shadow-2xs">
            <div class="flex items-center gap-2 font-bold">
                <i data-lucide="alert-circle" class="w-4 h-4 text-red-600"></i>
                <span>Terjadi kesalahan:</span>
            </div>
            <ul class="list-disc list-inside pl-5 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Metric Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Total Laporan</span>
                <i data-lucide="file-text" class="w-4 h-4 text-[#087F5B]"></i>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-[#17211E] font-heading">{{ $stats['total'] }}</p>
            <span class="text-[10px] text-slate-500 mt-1 block">Seluruh Berkas LPJ</span>
        </div>

        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Menunggu Uji</span>
                <i data-lucide="timer" class="w-4 h-4 text-amber-500"></i>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-amber-900 font-heading">{{ $stats['menunggu_verifikasi'] }}</p>
            <span class="text-[10px] text-amber-700 font-semibold mt-1 block">Perlu Audit Verifikator</span>
        </div>

        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Disetujui Tayang</span>
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-[#087F5B] font-heading">{{ $stats['disetujui'] }}</p>
            <span class="text-[10px] text-emerald-700 font-semibold mt-1 block">Tayang di Portal Publik</span>
        </div>

        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-4 sm:p-5 shadow-xs">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Dana Tervalidasi</span>
                <i data-lucide="wallet" class="w-4 h-4 text-blue-600"></i>
            </div>
            <p class="text-xl sm:text-2xl font-extrabold text-blue-900 font-heading">
                Rp {{ number_format($stats['totalDanaDilaporkan'] / 1000000, 1) }}M
            </p>
            <span class="text-[10px] text-slate-500 mt-1 block">Rp {{ number_format($stats['totalDanaDilaporkan'], 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl border border-[#D9E2DE] p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.laporan') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <div class="sm:col-span-6">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Cari Judul Laporan / Program Kausa</label>
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-3"></i>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Judul laporan, program kausa..."
                           class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#087F5B]/30 focus:border-[#087F5B] bg-[#F6F8F7] focus:bg-white transition-all">
                </div>
            </div>

            <div class="sm:col-span-4">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status Verifikasi LPJ</label>
                <select name="status" class="w-full px-3.5 py-2 text-xs sm:text-sm rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#087F5B]/30 focus:border-[#087F5B] bg-[#F6F8F7] focus:bg-white text-slate-800 transition-all">
                    <option value="">Semua Status</option>
                    <option value="menunggu_verifikasi" @selected(request('status') === 'menunggu_verifikasi')>Menunggu Verifikasi (Pending)</option>
                    <option value="disetujui" @selected(request('status') === 'disetujui')>Disetujui (Tayang Publik)</option>
                    <option value="perlu_revisi" @selected(request('status') === 'perlu_revisi')>Perlu Revisi SPJ</option>
                    <option value="ditolak" @selected(request('status') === 'ditolak')>Ditolak</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-4 rounded-xl bg-[#087F5B] hover:bg-[#066A4C] text-white font-bold text-xs shadow-xs transition-all flex items-center justify-center gap-1.5 active:scale-95">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Filter</span>
                </button>
                @if (request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.laporan') }}" class="py-2 px-3 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-semibold transition-colors" title="Reset Filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Laporan LPJ -->
    <div class="bg-white rounded-3xl border border-[#D9E2DE] overflow-hidden shadow-xs">
        @if ($laporans->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-[#F6F8F7] text-slate-700 font-bold border-b border-[#D9E2DE] uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-6">Program &amp; Judul Laporan</th>
                            <th class="py-3.5 px-4">Instansi Pelaksana</th>
                            <th class="py-3.5 px-4">Realisasi Dana</th>
                            <th class="py-3.5 px-4">Status LPJ</th>
                            <th class="py-3.5 px-6 text-right">Audit Verifikator</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($laporans as $lpj)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <!-- Program & Judul Laporan -->
                                <td class="py-4 px-6 max-w-sm">
                                    <div class="space-y-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            Kausa: {{ $lpj->kausa->judul ?? 'Program Kausa' }}
                                        </span>
                                        <p class="font-bold text-slate-900 text-sm leading-snug">{{ $lpj->judul }}</p>
                                        <p class="text-[11px] text-slate-400">
                                            Periode: {{ $lpj->periode_mulai ? $lpj->periode_mulai->format('d M Y') : '-' }} s/d {{ $lpj->periode_selesai ? $lpj->periode_selesai->format('d M Y') : '-' }}
                                        </p>
                                    </div>
                                </td>

                                <!-- Instansi Pelaksana -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <p class="font-semibold text-slate-800 text-xs">{{ $lpj->kausa->instansi->nama ?? $lpj->user->name ?? 'Instansi' }}</p>
                                    <span class="text-[10px] text-slate-400 block mt-0.5">PIC: {{ $lpj->user->name ?? '-' }}</span>
                                </td>

                                <!-- Realisasi Dana -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <p class="font-extrabold text-[#087F5B] text-sm">Rp {{ number_format($lpj->total_digunakan, 0, ',', '.') }}</p>
                                    <span class="text-[10px] text-slate-500 font-semibold block mt-0.5">
                                        {{ $lpj->rincian->count() }} Item Nota Belanja
                                    </span>
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if ($lpj->status === 'disetujui')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i> Disetujui (Tayang)
                                        </span>
                                    @elseif ($lpj->status === 'perlu_revisi')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-orange-100 text-orange-900 border border-orange-300">
                                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-orange-600"></i> Perlu Revisi
                                        </span>
                                    @elseif ($lpj->status === 'ditolak')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                            <i data-lucide="x-circle" class="w-3.5 h-3.5 text-rose-600"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                            <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600"></i> Menunggu Audit
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi Verifikator -->
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Tombol Periksa Rincian Nota -->
                                        <button type="button"
                                                @click="openDetailModal({{ json_encode($lpj) }}, {{ json_encode($lpj->rincian) }}, '{{ addslashes($lpj->kausa->judul ?? '') }}')"
                                                class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors flex items-center gap-1"
                                                title="Lihat Rincian & Nota">
                                            <i data-lucide="receipt" class="w-3.5 h-3.5 text-slate-500"></i>
                                            <span>Rincian Nota</span>
                                        </button>

                                        @if ($lpj->status !== 'disetujui')
                                            <!-- Tombol Setujui -->
                                            <button type="button"
                                                    @click="openVerifyModal({{ $lpj->id }}, '{{ addslashes($lpj->judul) }}')"
                                                    class="px-2.5 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-[#087F5B] font-bold text-xs transition-colors flex items-center gap-1"
                                                    title="Setujui & Publikasikan">
                                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                                <span class="hidden sm:inline">Setujui</span>
                                            </button>

                                            <!-- Tombol Minta Revisi -->
                                            <button type="button"
                                                    @click="openReviseModal({{ $lpj->id }}, '{{ addslashes($lpj->judul) }}')"
                                                    class="px-2.5 py-1.5 rounded-lg bg-orange-50 hover:bg-orange-100 text-orange-800 font-bold text-xs transition-colors flex items-center gap-1"
                                                    title="Minta Perbaikan Nota">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                                <span class="hidden sm:inline">Revisi</span>
                                            </button>
                                        @else
                                            <a href="{{ route('transparansi.index') }}" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-emerald-50 text-[#087F5B] hover:bg-emerald-100 font-semibold text-xs flex items-center gap-1" title="Lihat di Portal Publik">
                                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                                <span>Publik</span>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-slate-100">
                {{ $laporans->links() }}
            </div>
        @else
            <div class="text-center py-16 p-8">
                <i data-lucide="file-check" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i>
                <h3 class="font-bold text-slate-800 text-sm font-heading">Tidak Ditemukan Laporan Penggunaan Dana</h3>
                <p class="text-xs text-slate-500 mt-1">Belum ada pengajuan LPJ atau sesuaikan filter pencarian Anda.</p>
            </div>
        @endif
    </div>

    <!-- Modal Rincian Belanja & Nota Fisik -->
    <div x-show="showDetailModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
         style="display: none;">
        <div class="bg-white rounded-3xl max-w-2xl w-full p-6 space-y-4 shadow-xl border border-slate-200 text-left max-h-[90vh] flex flex-col"
             @click.outside="showDetailModal = false">
            <div class="flex items-start justify-between pb-3 border-b border-slate-100">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded" x-text="detailKausaJudul"></span>
                    <h3 class="font-display font-bold text-lg text-slate-900 mt-1" x-text="selectedLpj?.judul"></h3>
                    <p class="text-xs text-slate-500" x-text="'Total Realisasi: Rp ' + new Intl.NumberFormat('id-ID').format(selectedLpj?.total_digunakan ?? 0)"></p>
                </div>
                <button type="button" @click="showDetailModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Ringkasan / Narasi Laporan -->
            <template x-if="selectedLpj?.ringkasan">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-700 leading-relaxed">
                    <p class="font-bold text-slate-900 mb-0.5">Narasi Pelaksanaan Kegiatan:</p>
                    <p x-text="selectedLpj?.ringkasan"></p>
                </div>
            </template>

            <!-- Tabel Rincian Belanja -->
            <div class="overflow-y-auto flex-1 space-y-2 pr-1">
                <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Item Pengeluaran &amp; Bukti Kuitansi Toko:</p>
                <div class="divide-y divide-slate-100 border border-slate-200 rounded-xl overflow-hidden bg-slate-50/30 text-xs">
                    <template x-for="(item, idx) in selectedRincian" :key="idx">
                        <div class="p-3 flex items-start justify-between gap-3">
                            <div class="space-y-0.5">
                                <p class="font-bold text-slate-800" x-text="item.uraian"></p>
                                <p class="text-[11px] text-slate-500" x-text="'Penerima: ' + (item.penerima_manfaat || '-') + ' • Tgl: ' + (item.tanggal_pengeluaran || '-')"></p>
                                <p class="text-[11px] text-slate-400 italic" x-text="item.keterangan ? 'Ket: ' + item.keterangan : ''"></p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="font-bold text-emerald-800" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.nominal)"></p>
                                <template x-if="item.path_bukti">
                                    <a :href="'/storage/' + item.path_bukti" target="_blank" class="text-[10px] text-[#087F5B] hover:underline flex items-center justify-end gap-1 mt-1 font-semibold">
                                        <i data-lucide="paperclip" class="w-3 h-3"></i>
                                        <span>Buka Nota Fisik</span>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end">
                <button type="button" @click="showDetailModal = false" class="py-2 px-5 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-bold text-slate-700 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Setujui LPJ -->
    <div x-show="showVerifyModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
         style="display: none;">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 text-center space-y-4 shadow-xl border border-slate-200"
             @click.outside="showVerifyModal = false">
            <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-[#087F5B] flex items-center justify-center mx-auto">
                <i data-lucide="check-circle-2" class="w-7 h-7"></i>
            </div>
            <div>
                <h3 class="font-display font-bold text-lg text-slate-900">Setujui &amp; Publikasikan LPJ?</h3>
                <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                    Laporan <strong class="text-slate-900" x-text="targetJudul"></strong> akan diverifikasi sah dan otomatis ditayangkan ke <strong>Portal Transparansi Publik</strong> Pemkab Tulungagung.
                </p>
            </div>
            <form :action="'/dashboard/admin/laporan/' + targetId + '/verify'" method="POST" class="pt-2">
                @csrf
                <div class="flex items-center gap-3">
                    <button type="button"
                            @click="showVerifyModal = false"
                            class="flex-1 py-2.5 px-4 rounded-xl border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 px-4 rounded-xl bg-[#087F5B] hover:bg-[#066A4C] text-xs font-bold text-white shadow-xs transition-colors">
                        Ya, Publikasikan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Minta Revisi LPJ -->
    <div x-show="showReviseModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
         style="display: none;">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-xl border border-slate-200 text-left"
             @click.outside="showReviseModal = false">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-800 flex items-center justify-center shrink-0">
                    <i data-lucide="edit-3" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="font-display font-bold text-base text-slate-900">Minta Revisi SPJ / Nota</h3>
                    <p class="text-xs text-slate-500" x-text="targetJudul"></p>
                </div>
            </div>

            <form :action="'/dashboard/admin/laporan/' + targetId + '/revise'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Catatan Koreksi untuk Instansi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="catatan_revisi"
                              rows="3"
                              required
                              placeholder="Tuliskan nota yang belum lengkap, kuitansi belum bermeterai, atau ketidaksesuaian nominal..."
                              class="w-full text-xs sm:text-sm rounded-xl border border-slate-300 p-3 focus:outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 transition-all"></textarea>
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <button type="button"
                            @click="showReviseModal = false"
                            class="flex-1 py-2.5 px-4 rounded-xl border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 px-4 rounded-xl bg-orange-600 hover:bg-orange-700 text-xs font-bold text-white shadow-xs transition-colors">
                        Kirim Arahan Revisi
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
function adminLaporan() {
    return {
        showVerifyModal: false,
        showReviseModal: false,
        showDetailModal: false,
        targetId: null,
        targetJudul: '',
        selectedLpj: null,
        selectedRincian: [],
        detailKausaJudul: '',

        openVerifyModal(id, judul) {
            this.targetId = id;
            this.targetJudul = judul;
            this.showVerifyModal = true;
        },

        openReviseModal(id, judul) {
            this.targetId = id;
            this.targetJudul = judul;
            this.showReviseModal = true;
        },

        openDetailModal(lpj, rincian, kausaJudul) {
            this.selectedLpj = lpj;
            this.selectedRincian = rincian || [];
            this.detailKausaJudul = kausaJudul || 'Program Kausa';
            this.showDetailModal = true;
        }
    }
}
</script>
@endpush
@endsection
