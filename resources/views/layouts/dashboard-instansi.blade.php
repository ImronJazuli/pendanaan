<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Instansi') - {{ config('app.name', 'SI-PEDULI Pemkab Tulungagung') }}</title>
    <meta name="description" content="@yield('meta-description', 'Dashboard Instansi Portal Pendanaan Sosial Pemkab Tulungagung')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { background-color: #F6F8F7; color: #17211E; font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, .font-heading, .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>

    @stack('styles')
</head>
<body
    class="bg-[#F6F8F7] text-[#17211E] antialiased min-h-screen selection:bg-emerald-100 selection:text-emerald-900"
    x-data="{ sidebarOpen: false }"
>

    {{-- Mobile Overlay --}}
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-[#123B32]/60 backdrop-blur-xs z-40 lg:hidden"
        style="display: none;"
    ></div>

    <div class="flex min-h-screen">

        {{-- ===== SIDEBAR: Dark, Fixed Left (canvas 007) ===== --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-72 lg:w-80 bg-[#123B32] text-white flex flex-col shrink-0 lg:translate-x-0 transition-transform duration-300 ease-in-out border-r border-[#1A5144]/40 shadow-xl lg:shadow-none"
        >
            {{-- Brand Header --}}
            <div class="p-6 border-b border-[#1A5144]/50 flex items-center justify-between">
                <a href="{{ route('landing') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#087F5B] flex items-center justify-center text-white shadow-sm ring-2 ring-white/20 shrink-0">
                        <i data-lucide="shield-check" class="w-5 h-5 text-white"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="text-xs uppercase tracking-wider font-semibold text-emerald-300 block">Portal Donasi Resmi</span>
                        <h1 class="font-heading font-bold text-base tracking-tight leading-tight text-white truncate">Pemkab Tulungagung</h1>
                    </div>
                </a>
                <button @click="sidebarOpen = false" type="button" class="lg:hidden p-1.5 rounded-lg text-emerald-200 hover:text-white hover:bg-[#1A5144]" aria-label="Tutup Menu">
                    <i data-lucide="x" class="w-5 h-5 text-emerald-200"></i>
                </button>
            </div>

            {{-- Instansi Mini Card --}}
            @if(auth()->user()->instansi ?? false)
            <div class="mx-4 mt-5 p-3.5 rounded-xl bg-[#1A5144]/60 border border-white/10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center text-white shrink-0">
                    <i data-lucide="building-2" class="w-5 h-5 text-emerald-300"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->instansi->nama ?? 'Instansi' }}</p>
                    <p class="text-[11px] text-emerald-200/80 truncate">OPD / Instansi Terverifikasi</p>
                    <span class="inline-flex items-center gap-1 mt-1 text-[10px] text-emerald-300 font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        {{ auth()->user()->email }}
                    </span>
                </div>
            </div>
            @endif

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto px-4 py-5 space-y-1" aria-label="Menu Instansi">
                <p class="px-3 text-[11px] font-semibold uppercase tracking-wider text-emerald-300/70 mb-2">Menu Kelola Lembaga</p>

                {{-- Ringkasan & Kausa --}}
                <a href="{{ route('dashboard.instansi') }}"
                    class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl text-left {{ request()->routeIs('dashboard.instansi') && !request()->routeIs('dashboard.instansi.detail') ? 'bg-[#087F5B] text-white font-semibold shadow-sm' : 'text-emerald-100 hover:text-white hover:bg-[#1A5144]/50 font-medium' }} text-xs transition-colors">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0 {{ request()->routeIs('dashboard.instansi') && !request()->routeIs('dashboard.instansi.detail') ? 'text-white' : 'text-emerald-300' }}"></i>
                        <span class="truncate">Ringkasan &amp; Kausa</span>
                    </div>
                    @if(request()->routeIs('dashboard.instansi') && !request()->routeIs('dashboard.instansi.detail'))
                        <span class="shrink-0 ml-auto px-2 py-0.5 text-[10px] font-bold rounded-full bg-white/20 text-white">Aktif</span>
                    @endif
                </a>

                {{-- Daftar Program --}}
                <a href="{{ route('dashboard.instansi') }}?status=disetujui"
                    class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl text-left text-emerald-100 hover:text-white hover:bg-[#1A5144]/50 font-medium text-xs transition-colors">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <i data-lucide="layers" class="w-4 h-4 shrink-0 text-emerald-300"></i>
                        <span class="truncate">Daftar Program Terdaftar</span>
                    </div>
                    @isset($statusCounts)
                        <span class="shrink-0 ml-auto px-2 py-0.5 text-[10px] font-semibold rounded-full bg-white/10 text-emerald-200">{{ $statusCounts['total'] ?? 0 }}</span>
                    @endisset
                </a>

                {{-- Laporan Dana --}}
                <a href="{{ route('instansi.laporan') }}"
                    class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl text-left {{ request()->routeIs('instansi.laporan*') ? 'bg-[#087F5B] text-white font-semibold shadow-sm' : 'text-emerald-100 hover:text-white hover:bg-[#1A5144]/50 font-medium' }} text-xs transition-colors">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <i data-lucide="receipt" class="w-4 h-4 shrink-0 {{ request()->routeIs('instansi.laporan*') ? 'text-white' : 'text-emerald-300' }}"></i>
                        <span class="truncate">Laporan Penggunaan Dana</span>
                    </div>
                    @isset($laporanRevisiCount)
                        @if($laporanRevisiCount > 0)
                            <span class="shrink-0 ml-auto px-2 py-0.5 text-[10px] font-bold rounded-full bg-amber-400 text-amber-950">{{ $laporanRevisiCount }} Revisi</span>
                        @endif
                    @endisset
                </a>

                {{-- Ajukan Kausa Baru --}}
                <a href="{{ route('kausa.create') }}"
                    class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl text-left {{ request()->routeIs('kausa.create') ? 'bg-[#087F5B] text-white font-semibold shadow-sm' : 'text-emerald-100 hover:text-white hover:bg-[#1A5144]/50 font-medium' }} text-xs transition-colors">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <i data-lucide="plus-circle" class="w-4 h-4 shrink-0 {{ request()->routeIs('kausa.create') ? 'text-white' : 'text-emerald-300' }}"></i>
                        <span class="truncate">Ajukan Kausa Baru</span>
                    </div>
                </a>

                <p class="px-3 pt-5 text-[11px] font-semibold uppercase tracking-wider text-emerald-300/70 mb-2">Akun &amp; Kepatuhan</p>

                {{-- Profil & Legalitas --}}
                <a href="{{ route('instansi.profil') }}"
                    class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl text-left {{ request()->routeIs('instansi.profil*') ? 'bg-[#087F5B] text-white font-semibold shadow-sm' : 'text-emerald-100 hover:text-white hover:bg-[#1A5144]/50 font-medium' }} text-xs transition-colors">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <i data-lucide="badge-check" class="w-4 h-4 shrink-0 {{ request()->routeIs('instansi.profil*') ? 'text-white' : 'text-emerald-300' }}"></i>
                        <span class="truncate">Profil &amp; Legalitas PPID</span>
                    </div>
                    @php $statusVerif = auth()->user()->instansi?->status_verifikasi ?? null; @endphp
                    @if($statusVerif === 'terverifikasi')
                        <span class="shrink-0 ml-auto px-1.5 py-0.5 text-[9px] uppercase tracking-wide font-bold rounded bg-emerald-500/20 text-emerald-300">Valid</span>
                    @elseif($statusVerif === 'menunggu')
                        <span class="shrink-0 ml-auto px-1.5 py-0.5 text-[9px] uppercase tracking-wide font-bold rounded bg-amber-500/20 text-amber-300">Review</span>
                    @endif
                </a>

                {{-- Panduan SPJ --}}
                <a href="{{ route('instansi.panduan') }}"
                    class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl text-left {{ request()->routeIs('instansi.panduan*') ? 'bg-[#087F5B] text-white font-semibold shadow-sm' : 'text-emerald-100 hover:text-white hover:bg-[#1A5144]/50 font-medium' }} text-xs transition-colors">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <i data-lucide="help-circle" class="w-4 h-4 shrink-0 {{ request()->routeIs('instansi.panduan*') ? 'text-white' : 'text-emerald-300' }}"></i>
                        <span class="truncate">Panduan SPJ &amp; Kuitansi</span>
                    </div>
                </a>
            </nav>

            {{-- Sidebar Footer --}}
            <div class="p-4 border-t border-[#1A5144]/50 bg-[#123B32]/80">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-full bg-emerald-800 flex items-center justify-center text-xs font-bold text-white shrink-0">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-medium text-white truncate">{{ auth()->user()?->name ?? 'Instansi' }}</p>
                            <p class="text-[10px] text-emerald-300/70 truncate">{{ auth()->user()?->email ?? '' }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 rounded-lg text-emerald-200 hover:text-red-300 hover:bg-white/10 transition-colors" title="Keluar Akun">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ===== CONTENT WRAPPER ===== --}}
        <div class="flex-1 min-w-0 lg:pl-80 flex flex-col">

            {{-- Sticky Topbar --}}
            <header class="sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-[#D9E2DE] px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between gap-4 shadow-xs">
                <div class="flex items-center gap-3 min-w-0">
                    <button @click="sidebarOpen = true" type="button" class="lg:hidden p-2 rounded-lg border border-[#D9E2DE] text-[#52615C] hover:bg-[#EEF3F1] transition-colors active:scale-95" aria-label="Buka Navigasi">
                        <i data-lucide="menu" class="w-5 h-5 text-[#087F5B]"></i>
                    </button>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="hidden sm:inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-[#E6F4EF] text-[#087F5B] border border-[#087F5B]/20">Instansi / OPD Resmi</span>
                            <h2 class="font-heading font-bold text-base sm:text-lg text-[#17211E] tracking-tight truncate">@yield('topbar-title', 'Dashboard Instansi')</h2>
                        </div>
                        <p class="text-xs text-[#73817C] hidden md:block">@yield('topbar-subtitle', 'Kabupaten Tulungagung &bull; Sistem Monitoring Akuntabilitas Bantuan Kemasyarakatan')</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                    {{-- Notification Bell --}}
                    <div class="relative">
                        <button type="button" class="relative p-2 rounded-lg border border-[#D9E2DE] bg-white text-[#52615C] hover:bg-[#EEF3F1] transition-colors active:scale-95" aria-label="Pemberitahuan">
                            <i data-lucide="bell" class="w-4 h-4"></i>
                            @isset($unreadNotificationsCount)
                                @if($unreadNotificationsCount > 0)
                                    <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-orange-500 ring-2 ring-white"></span>
                                @endif
                            @endisset
                        </button>
                    </div>
                    @yield('topbar-actions')
                    {{-- CTA Button --}}
                    @if(!request()->routeIs('kausa.create'))
                    <a href="{{ route('kausa.create') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-[#087F5B] hover:bg-[#066A4C] text-white text-xs font-semibold shadow-sm transition-all active:scale-95">
                        <i data-lucide="plus" class="w-4 h-4 text-white"></i>
                        <span class="hidden sm:inline">Ajukan Kausa Baru</span>
                        <span class="sm:hidden">Ajukan</span>
                    </a>
                    @endif
                </div>
            </header>

            {{-- Main Content --}}
            <main class="flex-1 overflow-y-auto">
                {{-- Flash: success --}}
                @if(session('status') || session('success'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                        <div class="bg-emerald-50 border border-emerald-300 text-emerald-900 px-4 py-3 rounded-xl flex items-center gap-3 shadow-xs">
                            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                            <span class="text-xs sm:text-sm font-medium">{{ session('status') ?? session('success') }}</span>
                        </div>
                    </div>
                @endif
                {{-- Flash: error --}}
                @if(session('error'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                        <div class="bg-red-50 border border-red-300 text-red-900 px-4 py-3 rounded-xl flex items-center gap-3 shadow-xs">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 shrink-0"></i>
                            <span class="text-xs sm:text-sm font-medium">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') { lucide.createIcons(); }
        });
    </script>
</body>
</html>