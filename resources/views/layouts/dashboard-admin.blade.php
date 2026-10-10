<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Admin') - {{ config('app.name', 'SI-PEDULI Pemkab Tulungagung') }}</title>
    <meta name="description" content="@yield('meta-description', 'Dashboard Admin Verifikator Portal Pendanaan Sosial Pemkab Tulungagung')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { 
            background-color: #F6F8F7; 
            color: #17211E; 
            font-family: 'Inter', system-ui, -apple-system, sans-serif; 
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
        }
        h1, h2, h3, h4, .font-heading, .font-display { 
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; 
            letter-spacing: -0.02em;
        }
        ::selection {
            background-color: #E6F4EF;
            color: #066A4C;
        }
        :focus-visible {
            outline: 2px solid #087F5B;
            outline-offset: 2px;
        }
        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }
    </style>

    @stack('styles')
</head>
<body
    class="bg-[#F6F8F7] text-[#17211E] antialiased min-h-screen"
    x-data="{ sidebarOpen: false }"
    @resize.window="if (window.innerWidth >= 1024) sidebarOpen = false"
>

    {{-- Mobile Sidebar Overlay --}}
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/50 z-40 lg:hidden"
        style="display: none;"
    ></div>

    {{-- Mobile Sidebar Drawer --}}
    @php
        $adminUser = auth('admin')->user() ?? auth()->user();
    @endphp
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white p-5 transform transition-transform duration-300 ease-in-out flex flex-col justify-between overflow-y-auto lg:hidden shadow-2xl border-r border-[#D9E2DE]"
        style="display: none;"
        x-show="sidebarOpen"
    >
        <div>
            <div class="flex items-center justify-between pb-4 border-b border-[#D9E2DE]">
                <div class="flex items-center gap-2.5">
                    <i data-lucide="shield-check" class="w-6 h-6 text-[#087F5B]"></i>
                    <span class="font-heading font-bold text-sm text-[#123B32]">Admin Pemkab Tulungagung</span>
                </div>
                <button @click="sidebarOpen = false" type="button" class="p-1 rounded-md text-[#52615C] hover:bg-slate-100">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <nav class="mt-4 space-y-1">
                <p class="px-3 pt-2 pb-1.5 text-[11px] font-bold uppercase tracking-wider text-[#73817C]">Menu Kurasi &amp; Kontrol</p>
                @include('layouts.partials.admin-sidebar-menu')
            </nav>
        </div>
        <div class="pt-4 border-t border-[#D9E2DE]">
            <div class="bg-[#123B32] text-white rounded-xl p-3 border border-[#1A5144] shadow-xs flex items-center justify-between gap-2.5">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-emerald-800 flex items-center justify-center text-xs font-bold text-white ring-2 ring-emerald-400/40 shrink-0">
                        {{ strtoupper(substr($adminUser?->name ?? 'A', 0, 2)) }}
                    </div>
                    <div class="min-w-0 text-left">
                        <p class="text-sm font-semibold text-white leading-tight truncate">{{ $adminUser?->name ?? 'Admin Pemkab Tulungagung' }}</p>
                        <p class="text-[11px] text-emerald-200/90 leading-tight truncate">Admin Verifikator Pemkab {{ $adminUser?->nip ? '(NIP. '.$adminUser->nip.')' : 'Tulungagung' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}" class="shrink-0">
                    @csrf
                    <button type="submit" class="p-2 rounded-lg text-emerald-200 hover:text-red-300 hover:bg-[#1A5144] transition-colors" title="Keluar">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ===== DARK TOPBAR (canvas 008) ===== --}}
    <header class="bg-[#123B32] text-white sticky top-0 z-30 border-b border-[#1A5144] shadow-sm">
        <div class="w-full px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3.5">
                <button @click="sidebarOpen = true" type="button" aria-label="Toggle Sidebar" class="lg:hidden p-2 rounded-lg text-emerald-200 hover:text-white hover:bg-[#1A5144] transition-colors">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 p-1 flex items-center justify-center border border-white/20 shadow-inner">
                        <i data-lucide="shield-check" class="w-6 h-6 text-emerald-300"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-heading font-bold text-base sm:text-lg tracking-tight text-white leading-tight">PPID Kabupaten Tulungagung</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">Official Admin</span>
                        </div>
                        <p class="text-xs text-emerald-200/80 font-normal">Portal Pendanaan Sosial &amp; Transparansi Publik</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-4">
                {{-- Live gateway indicator --}}
                @php
                    $isMidtransProd = config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION'));
                @endphp
                <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-full bg-black/20 border border-emerald-500/30 text-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    @if($isMidtransProd !== null)
                        <span class="text-emerald-200 font-medium">Gateway Midtrans: <strong class="text-white">{{ $isMidtransProd ? 'Active (Production)' : 'Active (Sandbox)' }}</strong></span>
                    @else
                        <span class="text-emerald-200 font-medium">Gateway Pembayaran: <strong class="text-white">Aktif (Simulasi)</strong></span>
                    @endif
                </div>

                @yield('topbar-actions')

                {{-- Notification Bell --}}
                <div class="relative">
                    <button type="button" class="relative p-2 rounded-lg text-emerald-200 hover:text-white hover:bg-[#1A5144] transition-colors" aria-label="Pemberitahuan">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        @isset($unreadNotificationsCount)
                            @if($unreadNotificationsCount > 0)
                                <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-amber-400 rounded-full ring-2 ring-[#123B32]"></span>
                            @endif
                        @endisset
                    </button>
                </div>
            </div>
        </div>
    </header>

    {{-- ===== MAIN LAYOUT: Sidebar + Content ===== --}}
    <div class="w-full px-4 sm:px-6 lg:px-8 py-6 flex gap-6 min-h-[calc(100vh-4rem)]">

        {{-- Desktop White Sidebar (canvas 008, fixed 288px / lg:w-72, sticky) --}}
        <aside class="w-64 lg:w-72 shrink-0 hidden lg:flex flex-col justify-between sticky top-20 h-[calc(100vh-6rem)]">

            {{-- Scrollable Sidebar Content --}}
            <div class="flex flex-col gap-5 overflow-y-auto pr-1 flex-1">
                {{-- Role Verification Pill Card --}}
                <div class="bg-white rounded-xl p-4 border border-[#D9E2DE] shadow-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-[#E6F4EF] flex items-center justify-center text-[#087F5B]">
                            <i data-lucide="award" class="w-5 h-5"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-[#123B32]">
                                {{ $adminUser?->instansi->nama ?? 'Biro Kesejahteraan Rakyat' }}
                            </h4>
                            <p class="text-[11px] text-[#52615C] truncate">Setda Kab. Tulungagung</p>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-[#D9E2DE]/70 flex items-center justify-between text-[11px] text-[#52615C]">
                        <span>Role Hak Akses</span>
                        <span class="font-semibold text-[#087F5B] bg-[#E6F4EF] px-2 py-0.5 rounded-full">Admin Kurator</span>
                    </div>
                </div>

                {{-- Nav Menu --}}
                <nav class="bg-white rounded-xl p-3 border border-[#D9E2DE] shadow-xs space-y-1">
                    <p class="px-3 pt-2 pb-1.5 text-[11px] font-bold uppercase tracking-wider text-[#73817C]">Menu Kurasi &amp; Kontrol</p>
                    @include('layouts.partials.admin-sidebar-menu')
                </nav>

                {{-- Integrity Box (Mockup 008) --}}
                <div class="bg-[#123B32] text-white rounded-xl p-4 border border-[#1A5144] shadow-xs relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 text-emerald-300 text-xs font-bold uppercase mb-1">
                            <i data-lucide="alert-octagon" class="w-3.5 h-3.5"></i>
                            <span>Pakta Integritas Admin</span>
                        </div>
                        <p class="text-[11px] text-emerald-100/80 leading-relaxed mb-3">
                            Sesuai Perbup Tulungagung, setiap penolakan kausa sosial wajib melampirkan alasan objektif dan dasar hukum verifikasi.
                        </p>
                        <div class="text-[10px] font-semibold text-emerald-300 flex items-center gap-1">
                            <i data-lucide="check" class="w-3.5 h-3.5"></i>
                            <span>Terikat Audit Inspektorat</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Fixed Bottom Admin Profile & Logout (Pojok Kiri Bawah Sidebar) --}}
            <div class="pt-3 border-t border-[#D9E2DE] shrink-0 mt-3">
                <div class="bg-[#123B32] text-white rounded-xl p-3 border border-[#1A5144] shadow-xs flex items-center justify-between gap-2.5">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-9 h-9 rounded-full bg-emerald-800 flex items-center justify-center text-xs font-bold text-white ring-2 ring-emerald-400/40 shrink-0">
                            {{ strtoupper(substr($adminUser?->name ?? 'A', 0, 2)) }}
                        </div>
                        <div class="min-w-0 text-left">
                            <p class="text-sm font-semibold text-white leading-tight truncate">{{ $adminUser?->name ?? 'Admin Pemkab Tulungagung' }}</p>
                            <p class="text-[11px] text-emerald-200/90 leading-tight truncate">Admin Verifikator Pemkab {{ $adminUser?->nip ? '(NIP. '.$adminUser->nip.')' : 'Tulungagung' }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}" class="shrink-0">
                        @csrf
                        <button type="submit" class="p-2 rounded-lg text-emerald-200 hover:text-red-300 hover:bg-[#1A5144] transition-colors" title="Keluar">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main Content Area --}}
        <main class="flex-1 min-w-0 flex flex-col gap-6">
            {{-- Flash: success --}}
            @if(session('status') || session('success'))
                <div class="bg-emerald-50 border border-emerald-300 text-emerald-900 px-4 py-3 rounded-xl flex items-center gap-3 shadow-xs">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                    <span class="text-xs sm:text-sm font-medium">{{ session('status') ?? session('success') }}</span>
                </div>
            @endif
            {{-- Flash: error --}}
            @if(session('error'))
                <div class="bg-red-50 border border-red-300 text-red-900 px-4 py-3 rounded-xl flex items-center gap-3 shadow-xs">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 shrink-0"></i>
                    <span class="text-xs sm:text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif
            @yield('content')
        </main>
    </div>

    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') { lucide.createIcons(); }
        });
    </script>
</body>
</html>
