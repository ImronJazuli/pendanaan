{{-- Menu Navigasi Admin --}}
{{-- Antrean Kurasi --}}
<a href="{{ route('dashboard.admin') }}"
    class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-lg text-left {{ request()->routeIs('dashboard.admin') && !request()->routeIs('dashboard.admin.detail') ? 'bg-[#087F5B] text-white font-medium shadow-xs' : 'text-[#52615C] hover:bg-[#EEF3F1] hover:text-[#17211E] font-medium' }} text-xs sm:text-sm transition-all">
    <div class="flex items-center gap-2.5 min-w-0 flex-1">
        <i data-lucide="clipboard-check" class="w-4 h-4 shrink-0 {{ request()->routeIs('dashboard.admin') && !request()->routeIs('dashboard.admin.detail') ? 'text-white' : 'text-[#087F5B]' }}"></i>
        <span class="truncate">Antrean Kurasi Kausa</span>
    </div>
    <span class="shrink-0 ml-auto px-2 py-0.5 text-[10px] font-bold rounded-full {{ request()->routeIs('dashboard.admin') && !request()->routeIs('dashboard.admin.detail') ? 'bg-amber-400 text-amber-950' : 'bg-[#EEF3F1] text-[#52615C]' }}">{{ $statusCounts['menunggu_verifikasi'] ?? 0 }} Baru</span>
</a>

{{-- Daftar Kausa Aktif --}}
<a href="{{ route('admin.kausa.aktif') }}"
    class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-lg text-left {{ request()->routeIs('admin.kausa*') ? 'bg-[#087F5B] text-white font-medium shadow-xs' : 'text-[#52615C] hover:bg-[#EEF3F1] hover:text-[#17211E] font-medium' }} text-xs sm:text-sm transition-all">
    <div class="flex items-center gap-2.5 min-w-0 flex-1">
        <i data-lucide="layout-list" class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.kausa*') ? 'text-white' : 'text-[#087F5B]' }}"></i>
        <span class="truncate">Daftar Kausa Aktif</span>
    </div>
    @isset($statusCounts)
        <span class="shrink-0 ml-auto px-2 py-0.5 text-[10px] font-bold rounded-full bg-[#EEF3F1] text-[#52615C]">{{ $statusCounts['disetujui'] ?? 0 }} Aktif</span>
    @endisset
</a>

{{-- Penyaluran Dana --}}
<a href="{{ Route::has('admin.penyaluran') ? route('admin.penyaluran') : url('/dashboard/admin/penyaluran') }}"
    class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-lg text-left {{ request()->is('dashboard/admin/penyaluran*') ? 'bg-[#087F5B] text-white font-medium shadow-xs' : 'text-[#52615C] hover:bg-[#EEF3F1] hover:text-[#17211E] font-medium' }} text-xs sm:text-sm transition-all">
    <div class="flex items-center gap-2.5 min-w-0 flex-1">
        <i data-lucide="send" class="w-4 h-4 shrink-0 {{ request()->is('dashboard/admin/penyaluran*') ? 'text-white' : 'text-[#087F5B]' }}"></i>
        <span class="truncate">Penyaluran Dana</span>
    </div>
    <span class="shrink-0 ml-auto px-2 py-0.5 text-[10px] font-semibold rounded-full bg-slate-100 text-slate-600">Realisasi</span>
</a>

{{-- Donasi & Pembayaran --}}
<a href="{{ route('admin.donasi') }}"
    class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-lg text-left {{ request()->routeIs('admin.donasi*') ? 'bg-[#087F5B] text-white font-medium shadow-xs' : 'text-[#52615C] hover:bg-[#EEF3F1] hover:text-[#17211E] font-medium' }} text-xs sm:text-sm transition-all">
    <div class="flex items-center gap-2.5 min-w-0 flex-1">
        <i data-lucide="credit-card" class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.donasi*') ? 'text-white' : 'text-[#087F5B]' }}"></i>
        <span class="truncate">Donasi &amp; Pembayaran</span>
    </div>
    <span class="shrink-0 ml-auto px-2 py-0.5 text-[10px] font-semibold rounded-full bg-emerald-100 text-emerald-700">Live</span>
</a>

{{-- Laporan Transparansi --}}
<a href="{{ route('admin.laporan') }}"
    class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-lg text-left {{ request()->routeIs('admin.laporan*') ? 'bg-[#087F5B] text-white font-medium shadow-xs' : 'text-[#52615C] hover:bg-[#EEF3F1] hover:text-[#17211E] font-medium' }} text-xs sm:text-sm transition-all">
    <div class="flex items-center gap-2.5 min-w-0 flex-1">
        <i data-lucide="file-bar-chart-2" class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.laporan*') ? 'text-white' : 'text-[#087F5B]' }}"></i>
        <span class="truncate">Laporan Transparansi Dana</span>
    </div>
    <span class="shrink-0 ml-auto px-2 py-0.5 text-[10px] font-semibold rounded-full bg-blue-100 text-blue-700">PPID</span>
</a>

<div class="px-3 pt-4 pb-1">
    <p class="text-[11px] font-bold uppercase tracking-wider text-[#73817C]">Verifikasi Lembaga</p>
</div>

{{-- Legalitas OPD --}}
<a href="{{ route('admin.legalitas') }}"
    class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-lg text-left {{ request()->routeIs('admin.legalitas*') || request()->routeIs('admin.instansi*') ? 'bg-[#087F5B] text-white font-medium shadow-xs' : 'text-[#52615C] hover:bg-[#EEF3F1] hover:text-[#17211E] font-medium' }} text-xs sm:text-sm transition-all">
    <div class="flex items-center gap-2.5 min-w-0 flex-1">
        <i data-lucide="building-2" class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.legalitas*') || request()->routeIs('admin.instansi*') ? 'text-white' : 'text-[#087F5B]' }}"></i>
        <span class="truncate">Legalitas OPD &amp; Ormas</span>
    </div>
    @isset($pendingInstansiCount)
        @if($pendingInstansiCount > 0)
            <span class="shrink-0 ml-auto px-2 py-0.5 text-[10px] font-bold rounded-full bg-amber-400 text-amber-950">{{ $pendingInstansiCount }} Pending</span>
        @endif
    @endisset
</a>
