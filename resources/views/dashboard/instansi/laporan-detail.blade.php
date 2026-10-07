@extends('layouts.dashboard-instansi')

@section('title', 'Detail LPJ - ' . $laporan->judul)
@section('topbar-title', 'Detail Laporan Pertanggungjawaban (LPJ)')
@section('topbar-subtitle', 'Kabupaten Tulungagung • Sistem Monitoring Akuntabilitas Bantuan Kemasyarakatan')

@section('content')
<div class="bg-[#F6F8F7] py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Breadcrumb & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-[#D9E2DE]">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#73817C] mb-2" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard.instansi') }}" class="hover:text-[#087F5B] transition-colors">Dashboard Instansi</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                    <a href="{{ route('instansi.laporan') }}" class="hover:text-[#087F5B] transition-colors">Laporan Dana</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span class="text-[#17211E] font-medium">Detail LPJ</span>
                </nav>
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-[#17211E] tracking-tight">
                        {{ $laporan->judul }}
                    </h1>
                    @if($laporan->status === 'disetujui' || $laporan->status === 'dipublikasikan')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            Disetujui &bull; Terverifikasi
                        </span>
                    @elseif($laporan->status === 'menunggu_verifikasi')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                            Menunggu Verifikasi Admin
                        </span>
                    @elseif($laporan->status === 'perlu_revisi')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                            Perlu Revisi
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                            Draf LPJ
                        </span>
                    @endif
                </div>
                <p class="text-xs sm:text-sm text-[#52615C] mt-1">
                    Kausa: <a href="{{ route('dashboard.instansi.detail', $laporan->kausa_id) }}" class="font-semibold text-emerald-700 hover:underline">{{ $laporan->kausa->judul ?? '-' }}</a>
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('instansi.laporan') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition-all">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
        </div>

        @if(session('status'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs flex items-center gap-2 font-medium">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <!-- Alert Feedback Catatan Admin jika ada -->
        @if($laporan->catatan_admin)
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-300 shadow-xs space-y-1.5">
                <div class="flex items-center gap-2 text-xs font-bold text-amber-900">
                    <i data-lucide="message-square" class="w-4 h-4 text-amber-600"></i>
                    <span>Catatan Tim Verifikator Admin Pemkab:</span>
                </div>
                <p class="text-xs text-amber-950 font-mono pl-6 leading-relaxed">
                    {{ $laporan->catatan_admin }}
                </p>
            </div>
        @endif

        <!-- Grid Ringkasan Laporan -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 shadow-xs">
                <span class="text-xs text-slate-500 font-semibold">Total Realisasi Belanja</span>
                <div class="text-2xl font-heading font-extrabold text-emerald-700 mt-1">
                    Rp {{ number_format($laporan->total_digunakan, 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-slate-400 mt-1">{{ $laporan->rincian->count() }} item pos pengeluaran tercatat</p>
            </div>

            <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 shadow-xs">
                <span class="text-xs text-slate-500 font-semibold">Periode Realisasi</span>
                <div class="text-sm font-heading font-bold text-slate-800 mt-1">
                    {{ $laporan->periode_mulai?->format('d M Y') }} &mdash; {{ $laporan->periode_selesai?->format('d M Y') }}
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Rentang waktu penyaluran dana</p>
            </div>

            <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 shadow-xs">
                <span class="text-xs text-slate-500 font-semibold">Waktu Pengajuan</span>
                <div class="text-sm font-heading font-bold text-slate-800 mt-1">
                    {{ $laporan->dikirim_pada ? $laporan->dikirim_pada->format('d M Y, H:i') : 'Belum Dikirim (Draf)' }}
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Penyusun: {{ $laporan->user->name ?? 'Instansi' }}</p>
            </div>
        </div>

        @if($laporan->ringkasan)
            <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 sm:p-6 shadow-xs space-y-2">
                <h3 class="font-heading font-bold text-sm text-[#123B32]">Ringkasan Penyaluran Bantuan</h3>
                <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-line">{{ $laporan->ringkasan }}</p>
            </div>
        @endif

        <!-- Rincian Pos Pengeluaran Belanja -->
        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 sm:p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <span class="p-2 rounded-xl bg-emerald-50 text-[#087F5B]">
                        <i data-lucide="receipt" class="w-5 h-5"></i>
                    </span>
                    <div>
                        <h2 class="font-heading font-bold text-base text-[#123B32]">Rincian Item Belanja &amp; Bukti Kuitansi</h2>
                        <p class="text-xs text-[#52615C]">Seluruh bukti nota otentik yang dilampirkan oleh instansi.</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-[11px] border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-3 w-10 text-center">No</th>
                            <th class="py-3 px-4">Uraian Pengeluaran</th>
                            <th class="py-3 px-4">Tanggal Belanja</th>
                            <th class="py-3 px-4">Penerima Manfaat</th>
                            <th class="py-3 px-4 text-right">Nominal (Rp)</th>
                            <th class="py-3 px-4 text-center">Bukti Nota / BAST</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($laporan->rincian as $idx => $rincian)
                            <tr class="hover:bg-slate-50/60">
                                <td class="py-3 px-3 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                <td class="py-3 px-4">
                                    <p class="font-semibold text-slate-800 text-xs">{{ $rincian->uraian }}</p>
                                    @if($rincian->keterangan)
                                        <p class="text-[11px] text-slate-500 mt-0.5">{{ $rincian->keterangan }}</p>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-slate-600 whitespace-nowrap">
                                    {{ $rincian->tanggal_pengeluaran ? $rincian->tanggal_pengeluaran->format('d/m/Y') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    {{ $rincian->penerima_manfaat ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-right font-bold text-emerald-700 whitespace-nowrap">
                                    Rp {{ number_format($rincian->nominal, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                    @if($rincian->path_bukti)
                                        <a href="{{ asset('storage/' . $rincian->path_bukti) }}" target="_blank"
                                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-[#087F5B] hover:bg-emerald-100 font-semibold text-[11px] border border-emerald-200">
                                            <i data-lucide="file-check" class="w-3.5 h-3.5"></i>
                                            <span>Lihat Bukti</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">Tanpa lampiran</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-slate-400">Tidak ada rincian belanja tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-slate-50 font-bold border-t border-slate-200 text-xs">
                        <tr>
                            <td colspan="4" class="py-3.5 px-4 text-right text-slate-700">Total Akumulasi Realisasi Belanja:</td>
                            <td class="py-3.5 px-4 text-right text-emerald-700 text-sm font-extrabold">
                                Rp {{ number_format($laporan->total_digunakan, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
