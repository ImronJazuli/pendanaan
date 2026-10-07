@extends('layouts.dashboard-instansi')

@section('title', 'Laporan Pertanggungjawaban (LPJ) Penggunaan Dana')
@section('topbar-title', 'Laporan Penggunaan Dana (LPJ)')
@section('topbar-subtitle', 'Kabupaten Tulungagung • Sistem Monitoring Akuntabilitas Bantuan Kemasyarakatan')

@section('content')
<div class="bg-[#F6F8F7] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Header & Breadcrumb -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-[#D9E2DE]">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#73817C] mb-2" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard.instansi') }}" class="hover:text-[#087F5B] transition-colors">Dashboard Instansi</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span class="text-[#17211E] font-medium">Laporan Penggunaan Dana</span>
                </nav>
                <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-[#17211E] tracking-tight">
                    Laporan Pertanggungjawaban (LPJ) Dana
                </h1>
                <p class="text-xs sm:text-sm text-[#52615C] mt-1 max-w-3xl leading-relaxed">
                    Setiap bantuan sosial yang tersalurkan wajib dilaporkan rincian realisasi belanjanya secara transparan beserta kuitansi/nota dan BAST sebelum dipublikasikan ke kanal transparansi Pemkab Tulungagung.
                </p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('instansi.panduan') }}" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition-all">
                    <i data-lucide="book-open" class="w-4 h-4 text-emerald-600"></i>
                    <span>Panduan SPJ Resmi</span>
                </a>
                <a href="{{ route('instansi.laporan.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#087F5B] hover:bg-[#066A4C] text-white text-xs font-semibold shadow-sm transition-all active:scale-95">
                    <i data-lucide="plus-circle" class="w-4 h-4 text-white"></i>
                    <span>Buat Laporan Baru</span>
                </a>
            </div>
        </div>

        @if(session('status'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs flex items-center gap-2 font-medium">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <!-- Metric KPI Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
            <div class="bg-white rounded-2xl border border-[#D9E2DE] p-4 sm:p-5 shadow-xs">
                <div class="flex items-center justify-between text-xs text-slate-500 font-semibold mb-1">
                    <span>Total LPJ</span>
                    <i data-lucide="file-text" class="w-4 h-4 text-slate-400"></i>
                </div>
                <div class="text-xl sm:text-2xl font-heading font-extrabold text-[#17211E]">
                    {{ $metrics['total_laporan'] ?? 0 }}
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Keseluruhan berkas</p>
            </div>

            <div class="bg-white rounded-2xl border border-amber-200 bg-amber-50/30 p-4 sm:p-5 shadow-xs">
                <div class="flex items-center justify-between text-xs text-amber-700 font-semibold mb-1">
                    <span>Menunggu Verifikasi</span>
                    <i data-lucide="clock" class="w-4 h-4 text-amber-500"></i>
                </div>
                <div class="text-xl sm:text-2xl font-heading font-extrabold text-amber-800">
                    {{ $metrics['menunggu_verifikasi'] ?? 0 }}
                </div>
                <p class="text-[11px] text-amber-600 mt-1">Sedang diteliti Admin</p>
            </div>

            <div class="bg-white rounded-2xl border border-emerald-200 bg-emerald-50/30 p-4 sm:p-5 shadow-xs">
                <div class="flex items-center justify-between text-xs text-emerald-700 font-semibold mb-1">
                    <span>Disetujui / Tayang</span>
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i>
                </div>
                <div class="text-xl sm:text-2xl font-heading font-extrabold text-emerald-800">
                    {{ $metrics['disetujui'] ?? 0 }}
                </div>
                <p class="text-[11px] text-emerald-600 mt-1">Transparan ke publik</p>
            </div>

            <div class="bg-white rounded-2xl border border-rose-200 bg-rose-50/30 p-4 sm:p-5 shadow-xs">
                <div class="flex items-center justify-between text-xs text-rose-700 font-semibold mb-1">
                    <span>Perlu Revisi</span>
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-500"></i>
                </div>
                <div class="text-xl sm:text-2xl font-heading font-extrabold text-rose-800">
                    {{ $metrics['perlu_revisi'] ?? 0 }}
                </div>
                <p class="text-[11px] text-rose-600 mt-1">Ada catatan SPJ</p>
            </div>

            <div class="col-span-2 lg:col-span-1 bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs">
                <div class="flex items-center justify-between text-xs text-slate-500 font-semibold mb-1">
                    <span>Total Realisasi</span>
                    <i data-lucide="wallet" class="w-4 h-4 text-emerald-600"></i>
                </div>
                <div class="text-base sm:text-lg font-heading font-extrabold text-emerald-700 truncate" title="Rp {{ number_format($metrics['total_realisasi'] ?? 0, 0, ',', '.') }}">
                    Rp {{ number_format($metrics['total_realisasi'] ?? 0, 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Dana terverifikasi</p>
            </div>
        </div>

        <!-- Section: Kausa Memerlukan LPJ -->
        @if(isset($kausaList) && $kausaList->count() > 0)
            <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 flex-wrap gap-2">
                    <div class="flex items-center gap-2.5">
                        <span class="p-2 rounded-xl bg-emerald-50 text-[#087F5B]">
                            <i data-lucide="layers" class="w-5 h-5"></i>
                        </span>
                        <div>
                            <h2 class="font-heading font-bold text-base text-[#123B32]">Kausa Siap / Memerlukan Laporan LPJ</h2>
                            <p class="text-xs text-[#52615C]">Kausa sosial yang telah berjalan dan siap dilaporkan realisasi penyalurannya.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($kausaList as $k)
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 hover:bg-slate-50 transition-colors flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-1.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        {{ ucfirst($k->status) }}
                                    </span>
                                    <span class="text-[11px] text-slate-500">
                                        {{ $k->laporan_dana_count }} LPJ Diajukan
                                    </span>
                                </div>
                                <h3 class="font-heading font-bold text-sm text-[#17211E] line-clamp-2" title="{{ $k->judul }}">
                                    {{ $k->judul }}
                                </h3>
                                <div class="mt-2 text-xs text-slate-600 space-y-1">
                                    <div class="flex justify-between">
                                        <span>Dana Terkumpul:</span>
                                        <strong class="text-emerald-700">Rp {{ number_format($k->total_terkumpul, 0, ',', '.') }}</strong>
                                    </div>
                                    <div class="flex justify-between text-[11px] text-slate-500">
                                        <span>Target:</span>
                                        <span>Rp {{ number_format($k->target_dana, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('instansi.laporan.create', ['kausa_id' => $k->id]) }}"
                               class="inline-flex items-center justify-center gap-1.5 w-full py-2 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition-all">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>Buat LPJ Kausa Ini</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Section: Riwayat Laporan Penggunaan Dana -->
        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 sm:p-6 shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <span class="p-2 rounded-xl bg-emerald-50 text-[#087F5B]">
                        <i data-lucide="receipt" class="w-5 h-5"></i>
                    </span>
                    <div>
                        <h2 class="font-heading font-bold text-base text-[#123B32]">Daftar Riwayat LPJ Penggunaan Dana</h2>
                        <p class="text-xs text-[#52615C]">Status verifikasi dan rekap belanja yang diajukan ke Pemerintah Kabupaten.</p>
                    </div>
                </div>

                <!-- Filter Status -->
                <form method="GET" action="{{ route('instansi.laporan') }}" class="flex items-center gap-2">
                    <select name="status" onchange="this.form.submit()" class="text-xs bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#087F5B]">
                        <option value="">Semua Status LPJ</option>
                        <option value="draf" {{ request('status') === 'draf' ? 'selected' : '' }}>Draf</option>
                        <option value="menunggu_verifikasi" {{ request('status') === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="perlu_revisi" {{ request('status') === 'perlu_revisi' ? 'selected' : '' }}>Perlu Revisi</option>
                    </select>
                </form>
            </div>

            <!-- Table Riwayat LPJ -->
            @if($laporan->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-[11px] border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-4">Laporan / Kausa</th>
                                <th class="py-3 px-4">Periode Realisasi</th>
                                <th class="py-3 px-4">Total Belanja</th>
                                <th class="py-3 px-4">Item Bukti</th>
                                <th class="py-3 px-4">Status Verifikasi</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($laporan as $row)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3.5 px-4 align-top">
                                        <p class="font-bold text-slate-800 text-xs">{{ $row->judul }}</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5">{{ $row->kausa->judul ?? '-' }}</p>
                                    </td>
                                    <td class="py-3.5 px-4 align-top text-slate-600 whitespace-nowrap">
                                        {{ $row->periode_mulai?->format('d/m/Y') }} s/d {{ $row->periode_selesai?->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3.5 px-4 align-top font-bold text-emerald-700 whitespace-nowrap">
                                        Rp {{ number_format($row->total_digunakan, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3.5 px-4 align-top text-slate-600 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-semibold text-[11px]">
                                            <i data-lucide="paperclip" class="w-3 h-3 text-slate-400"></i>
                                            {{ $row->rincian->count() }} Bukti Nota
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 align-top whitespace-nowrap">
                                        @if($row->status === 'disetujui' || $row->status === 'dipublikasikan')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                Disetujui
                                            </span>
                                        @elseif($row->status === 'menunggu_verifikasi')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                Menunggu Verifikasi
                                            </span>
                                        @elseif($row->status === 'perlu_revisi')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                                Perlu Revisi
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                                Draf
                                            </span>
                                        @endif

                                        @if($row->catatan_admin)
                                            <p class="text-[10px] text-amber-800 mt-1 max-w-xs truncate" title="{{ $row->catatan_admin }}">
                                                Catatan: {{ $row->catatan_admin }}
                                            </p>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 align-top text-right whitespace-nowrap">
                                        <a href="{{ route('instansi.laporan.show', $row->id) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-xs transition-all shadow-xs">
                                            <i data-lucide="eye" class="w-3.5 h-3.5 text-slate-500"></i>
                                            <span>Lihat LPJ</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pt-3 border-t border-slate-100">
                    {{ $laporan->links() }}
                </div>
            @else
                <div class="text-center py-12 px-4 rounded-xl border border-dashed border-slate-200 bg-slate-50/50 space-y-3">
                    <div class="inline-flex p-3 rounded-2xl bg-emerald-50 text-[#087F5B]">
                        <i data-lucide="file-question" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-heading font-bold text-sm text-slate-800">Belum Ada Laporan Penggunaan Dana</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto">
                        Anda belum pernah membuat laporan pertanggungjawaban dana. Klik tombol di bawah untuk membuat laporan baru.
                    </p>
                    <a href="{{ route('instansi.laporan.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#087F5B] hover:bg-[#066A4C] text-white text-xs font-semibold shadow-sm transition-all">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        <span>Buat Laporan Baru Sekarang</span>
                    </a>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
