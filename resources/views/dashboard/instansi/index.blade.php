@extends('layouts.dashboard-instansi')

@section('title', 'Dashboard Instansi')
@section('topbar-title', 'Pusat Kendali Pengajuan & Transparansi Realisasi Dana')
@section('topbar-subtitle', 'Kabupaten Tulungagung • Sistem Monitoring Akuntabilitas Bantuan Kemasyarakatan')

@section('content')
@php
    $instansi = auth()->user()->instansi;
@endphp
<div class="bg-[#F6F8F7] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Metric Summary Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Kausa</span>
                    <i data-lucide="layers" class="w-4 h-4 text-[#087F5B]"></i>
                </div>
                <p class="text-2xl sm:text-3xl font-extrabold text-[#17211E] font-heading">{{ $statusCounts['total'] ?? 0 }}</p>
                <span class="text-[11px] text-slate-500 mt-1 block">Program diajukan</span>
            </div>

            <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Menunggu Uji</span>
                    <i data-lucide="timer" class="w-4 h-4 text-amber-600"></i>
                </div>
                <p class="text-2xl sm:text-3xl font-extrabold text-amber-900 font-heading">{{ $statusCounts['menunggu_verifikasi'] ?? 0 }}</p>
                <span class="text-[11px] text-amber-700 mt-1 block">Antrean verifikasi</span>
            </div>

            <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Disetujui / Tayang</span>
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i>
                </div>
                <p class="text-2xl sm:text-3xl font-extrabold text-emerald-800 font-heading">{{ $statusCounts['disetujui'] ?? 0 }}</p>
                <span class="text-[11px] text-emerald-700 mt-1 block">Menerima donasi publik</span>
            </div>

            <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Draf / Revisi</span>
                    <i data-lucide="edit-3" class="w-4 h-4 text-slate-400"></i>
                </div>
                <p class="text-2xl sm:text-3xl font-extrabold text-slate-700 font-heading">{{ ($statusCounts['draf'] ?? 0) + ($statusCounts['ditolak'] ?? 0) }}</p>
                <span class="text-[11px] text-slate-500 mt-1 block">Perlu tindakan</span>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 shadow-xs">
            <form method="GET" action="{{ route('dashboard.instansi') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                <div class="sm:col-span-6">
                    <label class="block text-xs font-bold text-[#17211E] mb-1.5 uppercase tracking-wider">Cari Judul Kausa</label>
                    <div class="relative">
                        <i data-lucide="search" class="w-4 h-4 text-emerald-700 absolute left-3.5 top-3"></i>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Ketik judul program..." 
                            class="w-full pl-10 pr-4 py-2.5 bg-[#F6F8F7] border border-[#D9E2DE] focus:border-[#087F5B] focus:bg-white rounded-xl text-xs sm:text-sm outline-none transition-all"
                        >
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label class="block text-xs font-bold text-[#17211E] mb-1.5 uppercase tracking-wider">Status Kurasi</label>
                    <select name="status" class="w-full px-3.5 py-2.5 bg-[#F6F8F7] border border-[#D9E2DE] focus:border-[#087F5B] focus:bg-white rounded-xl text-xs sm:text-sm outline-none transition-all">
                        <option value="">Semua Status</option>
                        <option value="draf" @selected(request('status') === 'draf')>Draf</option>
                        <option value="menunggu_verifikasi" @selected(request('status') === 'menunggu_verifikasi')>Menunggu Verifikasi</option>
                        <option value="perlu_diperbaiki" @selected(request('status') === 'perlu_diperbaiki')>Perlu Diperbaiki</option>
                        <option value="disetujui" @selected(request('status') === 'disetujui')>Disetujui</option>
                        <option value="ditolak" @selected(request('status') === 'ditolak')>Ditolak</option>
                    </select>
                </div>

                <div class="sm:col-span-3 flex items-end gap-2">
                    <button type="submit" class="flex-1 py-2.5 bg-[#087F5B] hover:bg-[#066A4C] text-white font-bold text-xs rounded-xl transition-all shadow-xs active:scale-95 flex items-center justify-center gap-1.5">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                        <span>Filter</span>
                    </button>
                    @if (request()->hasAny(['search', 'status']))
                        <a href="{{ route('dashboard.instansi') }}" class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table of Programs -->
        <div class="bg-white rounded-3xl border border-[#D9E2DE] overflow-hidden shadow-xs">
            <div class="p-6 border-b border-[#D9E2DE] flex items-center justify-between">
                <div>
                    <h2 class="font-heading font-bold text-base text-[#17211E]">Daftar Program Pengajuan</h2>
                    <p class="text-xs text-[#73817C] mt-0.5">Kelola data program dan unggah nota pertanggungjawaban fisik.</p>
                </div>
                <span class="text-xs font-semibold text-slate-500">Total: {{ $kausa->total() }}</span>
            </div>

            @if ($kausa->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-[#F6F8F7] text-slate-700 font-bold border-b border-slate-200 uppercase tracking-wider text-[11px]">
                                <th class="py-3.5 px-6">Program Kausa</th>
                                <th class="py-3.5 px-4">Kategori</th>
                                <th class="py-3.5 px-4">Target Dana</th>
                                <th class="py-3.5 px-4">Status Kurasi</th>
                                <th class="py-3.5 px-6 text-right">Aksi Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($kausa as $item)
                                @php
                                    $terkumpul = $item->donasi ? $item->donasi->where('status', 'berhasil')->sum('nominal') : 0;
                                    $target = $item->target_dana > 0 ? $item->target_dana : 1;
                                    $persen = min(100, round(($terkumpul / $target) * 100));
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-4 px-6 max-w-xs">
                                        <p class="font-bold text-slate-900 text-sm leading-snug line-clamp-1">{{ $item->judul }}</p>
                                        <div class="flex items-center gap-2 text-[11px] text-slate-400 mt-1">
                                            <span>{{ $item->lokasi ?? 'Kab. Tulungagung' }}</span>
                                            <span>&bull;</span>
                                            <span>{{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            {{ $item->kategori->nama ?? 'Sosial' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <p class="font-bold text-slate-800 text-xs">Rp {{ number_format($item->target_dana, 0, ',', '.') }}</p>
                                        <span class="text-[10px] text-emerald-700 font-semibold">{{ $persen }}% (Rp {{ number_format($terkumpul, 0, ',', '.') }})</span>
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        @if ($item->status === 'disetujui')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                <i data-lucide="check-circle" class="w-3 h-3 text-emerald-600"></i> Disetujui
                                            </span>
                                        @elseif ($item->status === 'menunggu_verifikasi')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                                <i data-lucide="timer" class="w-3 h-3 text-amber-700"></i> Menunggu Uji
                                            </span>
                                        @elseif ($item->status === 'perlu_diperbaiki')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-orange-100 text-orange-900 border border-orange-300">
                                                <i data-lucide="alert-circle" class="w-3 h-3 text-orange-700"></i> Perlu Revisi
                                            </span>
                                        @elseif ($item->status === 'ditolak')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-100 text-red-900 border border-red-300">
                                                <i data-lucide="x-circle" class="w-3 h-3 text-red-700"></i> Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-300">
                                                <i data-lucide="edit-3" class="w-3 h-3 text-slate-500"></i> Draf
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('dashboard.instansi.detail', $item->id) }}" class="px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-semibold text-xs transition-colors flex items-center gap-1">
                                                <i data-lucide="file-text" class="w-3.5 h-3.5 text-emerald-700"></i>
                                                <span>Kelola</span>
                                            </a>
                                            @if ($item->status === 'disetujui')
                                                <a href="{{ route('kausa.show', $item->slug) }}" class="px-3 py-1.5 rounded-lg bg-white border border-[#D9E2DE] hover:bg-slate-50 text-slate-700 font-semibold text-xs transition-colors" title="Lihat Publik">
                                                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100">
                    {{ $kausa->links() }}
                </div>
            @else
                <div class="text-center py-16 p-6">
                    <i data-lucide="folder-plus" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i>
                    <h3 class="font-heading font-bold text-slate-700 text-base">Belum Ada Program Kausa</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                        Mulai galang bantuan sosial atau logistik darurat dengan membuat pengajuan baru.
                    </p>
                    <div class="mt-5">
                        <a href="{{ route('kausa.create') }}" class="px-5 py-2.5 bg-[#087F5B] text-white font-bold text-xs rounded-xl shadow-xs hover:bg-[#066A4C] transition-all inline-flex items-center gap-2">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            <span>Buat Kausa Pertama</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
