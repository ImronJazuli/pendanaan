<!-- ==================== TOP OFFICIAL BAR ==================== -->
<div class="bg-[#123B32] text-white text-xs py-2 px-4 border-b border-emerald-950/40">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 bg-[#087F5B] text-white px-2 py-0.5 rounded-full font-semibold text-[11px] tracking-wide">
                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> PORTAL RESMI
            </span>
            <span class="text-emerald-100/90 hidden sm:inline">Pemerintah Kabupaten Tulungagung &bull; Terintegrasi PPID &amp; Dinsos</span>
        </div>
        <div class="flex items-center gap-4 text-emerald-200/80 text-[11px]">
            <span class="flex items-center gap-1.5 hover:text-white transition-colors">
                <i data-lucide="clock" class="w-3 h-3 text-emerald-400"></i> Akuntabilitas Terbuka &bull; Audit Publik Real-Time
            </span>
            <span class="hidden md:inline text-emerald-800">|</span>
            <a href="{{ route('transparansi.index') }}" class="hover:text-white underline underline-offset-2 transition-colors">Pusat Transparansi</a>
        </div>
    </div>
</div>

<!-- ==================== MAIN NAVBAR ==================== -->
<header x-data="{ mobileMenuOpen: false, categoryOpen: false, userDropdownOpen: false }" class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-[#D9E2DE] shadow-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20 gap-4">
            
            <!-- Logo Brand -->
            <a href="{{ route('landing') }}" class="flex items-center gap-3 shrink-0 group">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#087F5B] to-[#123B32] p-2 flex items-center justify-center shadow-sm text-white group-hover:scale-105 transition-transform">
                    <i data-lucide="landmark" class="w-6 h-6 text-white"></i>
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center gap-1.5">
                        <span class="font-heading font-extrabold text-lg tracking-tight text-[#123B32]">SI-PEDULI</span>
                        <span class="bg-emerald-100 text-[#087F5B] text-[10px] font-bold px-1.5 py-0.5 rounded tracking-wider">PEMKAB</span>
                    </div>
                    <span class="text-xs text-[#73817C] font-medium leading-none">Kabupaten Tulungagung</span>
                </div>
            </a>

            <!-- Search Input -->
            <div class="hidden lg:flex items-center flex-1 max-w-md mx-4">
                <form action="{{ route('kausa.index') }}" method="GET" class="w-full">
                    <div class="relative w-full">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="search" class="w-4 h-4 text-emerald-700"></i>
                        </span>
                        <input 
                            name="search"
                            type="text" 
                            value="{{ request('search') }}"
                            placeholder="Cari kausa, lokasi kecamatan, bencana..." 
                            class="w-full pl-10 pr-4 py-2 bg-[#F6F8F7] border border-[#D9E2DE] focus:border-[#087F5B] focus:bg-white rounded-lg text-xs md:text-sm text-[#17211E] placeholder:text-[#73817C] outline-none transition-all"
                        >
                    </div>
                </form>
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('kausa.index') }}" class="px-3 py-2 text-sm font-semibold {{ request()->routeIs('kausa.*') ? 'text-[#087F5B] bg-emerald-50/80 rounded-lg' : 'text-[#52615C] hover:text-[#087F5B]' }} transition-all flex items-center gap-1.5">
                    <i data-lucide="layout-grid" class="w-4 h-4"></i> Katalog
                </a>
                
                <a href="{{ route('transparansi.index') }}" class="px-3 py-2 text-sm font-semibold {{ request()->routeIs('transparansi.*') ? 'text-[#087F5B] bg-emerald-50/80 rounded-lg' : 'text-[#52615C] hover:text-[#087F5B]' }} transition-all flex items-center gap-1.5">
                    <i data-lucide="file-check-2" class="w-4 h-4"></i> Transparansi
                </a>

                @auth
                    @if (auth()->user()->peran === 'institution_user')
                        <a href="{{ route('kausa.create') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-white border border-[#087F5B] text-[#087F5B] hover:bg-emerald-50 font-semibold text-xs tracking-wide transition-all shadow-xs">
                            <i data-lucide="plus-circle" class="w-4 h-4"></i> Ajukan Program
                        </a>
                    @endif

                    @php
                        $dashboardRoute = match (auth()->user()->peran) {
                            'admin' => route('dashboard.admin'),
                            'institution_user' => route('dashboard.instansi'),
                            default => route('dashboard'),
                        };
                    @endphp

                    <!-- User Dropdown -->
                    <div class="relative" @click.outside="userDropdownOpen = false">
                        <button @click="userDropdownOpen = !userDropdownOpen" type="button" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg border border-[#D9E2DE] hover:border-[#087F5B] bg-white transition-all">
                            <div class="w-8 h-8 rounded-full bg-[#087F5B] text-white font-bold text-xs flex items-center justify-center">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <div class="text-left hidden lg:block">
                                <p class="text-xs font-semibold text-[#17211E] truncate max-w-[120px]">{{ auth()->user()->name }}</p>
                                <span class="text-[10px] text-emerald-700 font-medium capitalize">{{ str_replace('_', ' ', auth()->user()->peran) }}</span>
                            </div>
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400"></i>
                        </button>

                        <div x-show="userDropdownOpen" x-cloak class="absolute right-0 mt-2 w-56 bg-white border border-[#D9E2DE] rounded-xl shadow-lg py-2 z-50">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs text-slate-500">Login sebagai</p>
                                <p class="text-xs font-bold text-slate-800 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ $dashboardRoute }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-emerald-50 hover:text-[#087F5B]">
                                <i data-lucide="layout-dashboard" class="w-4 h-4 text-emerald-600"></i> Dasbor Utama
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-emerald-50 hover:text-[#087F5B]">
                                <i data-lucide="user-cog" class="w-4 h-4 text-emerald-600"></i> Profil Akun
                            </a>
                            <form method="POST" action="{{ route('donatur.logout') }}" class="border-t border-slate-100 mt-1">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-red-600 hover:bg-red-50">
                                    <i data-lucide="log-out" class="w-4 h-4 text-red-500"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#087F5B] hover:bg-[#066A4C] text-white font-semibold text-xs tracking-wide active:scale-95 transition-all shadow-xs">
                        <i data-lucide="log-in" class="w-4 h-4 text-white"></i>
                        <span>Masuk / SSO</span>
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Hamburger -->
            <div class="flex items-center gap-2 md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" aria-label="Buka Menu" class="p-2 rounded-lg text-slate-700 hover:bg-slate-100">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer Sidebar Menu -->
    <div x-show="mobileMenuOpen" x-cloak class="fixed inset-0 z-40 bg-black/50" @click="mobileMenuOpen = false"></div>
    <aside 
        x-show="mobileMenuOpen" 
        x-cloak 
        class="fixed inset-y-0 left-0 z-50 w-72 bg-white p-6 flex flex-col justify-between shadow-2xl transition-all"
    >
        <div>
            <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-lg bg-[#087F5B] flex items-center justify-center text-white">
                        <i data-lucide="landmark" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="font-heading font-bold text-sm text-[#123B32]">SI-PEDULI</p>
                        <p class="text-[10px] text-slate-500">Pemkab Tulungagung</p>
                    </div>
                </div>
                <button @click="mobileMenuOpen = false" type="button" aria-label="Tutup Menu" class="p-1 rounded text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Mobile Search -->
            <form action="{{ route('kausa.index') }}" method="GET" class="mt-4">
                <div class="relative">
                    <input name="search" type="text" placeholder="Cari kausa..." class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                    <i data-lucide="search" class="w-4 h-4 text-emerald-700 absolute left-3 top-2.5"></i>
                </div>
            </form>

            <nav class="mt-6 space-y-1">
                <a href="{{ route('landing') }}" class="flex items-center gap-3 p-2.5 rounded-lg text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                    <i data-lucide="home" class="w-4 h-4 text-[#087F5B]"></i> Beranda
                </a>
                <a href="{{ route('kausa.index') }}" class="flex items-center gap-3 p-2.5 rounded-lg text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                    <i data-lucide="layout-grid" class="w-4 h-4 text-[#087F5B]"></i> Katalog Kausa
                </a>
                <a href="{{ route('transparansi.index') }}" class="flex items-center gap-3 p-2.5 rounded-lg text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                    <i data-lucide="file-check-2" class="w-4 h-4 text-[#087F5B]"></i> Pusat Transparansi
                </a>
                <a href="{{ route('pages.tentang') }}" class="flex items-center gap-3 p-2.5 rounded-lg text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                    <i data-lucide="info" class="w-4 h-4 text-[#087F5B]"></i> Tentang Kami
                </a>
            </nav>
        </div>

        <div class="pt-6 border-t border-slate-200 space-y-2">
            @auth
                @php
                    $dashboardRoute = match (auth()->user()->peran) {
                        'admin' => route('dashboard.admin'),
                        'institution_user' => route('dashboard.instansi'),
                        default => route('dashboard'),
                    };
                @endphp
                <a href="{{ $dashboardRoute }}" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-lg bg-emerald-50 border border-emerald-600 text-emerald-700 font-semibold text-sm">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Masuk Dasbor
                </a>
                <form method="POST" action="{{ route('donatur.logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-lg bg-red-50 text-red-600 font-semibold text-sm">
                        <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-lg bg-[#087F5B] text-white font-semibold text-sm">
                    <i data-lucide="log-in" class="w-4 h-4"></i> Masuk Akun / SSO
                </a>
                <a href="{{ route('donatur.register') }}" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-lg border border-slate-200 text-slate-700 font-semibold text-sm">
                    Daftar Akun Baru
                </a>
            @endauth
        </div>
    </aside>
</header>
