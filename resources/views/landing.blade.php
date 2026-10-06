@extends('layouts.app')

@section('content')
<div x-data="{
    donationModalOpen: false,
    selectedKausaTitle: '',
    selectedKausaId: null,
    selectedKausaSlug: '',
    selectedAmount: 50000,
    activeTab: 'all',
    openDonation(title, id, slug) {
        this.selectedKausaTitle = title;
        this.selectedKausaId = id;
        this.selectedKausaSlug = slug;
        this.donationModalOpen = true;
    }
}">

    <!-- ==================== HERO SECTION & IMPACT COUNTER ==================== -->
    <section class="relative bg-gradient-to-b from-[#123B32] via-[#0D2D26] to-[#0A241E] text-white overflow-hidden py-14 md:py-20">
        <!-- Ambient Backdrop Effects -->
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#34d399_1px,transparent_1px)] [background-size:24px_24px] pointer-events-none"></div>
        <div class="absolute -right-32 -top-32 w-96 h-96 rounded-full bg-emerald-500/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-32 -bottom-32 w-96 h-96 rounded-full bg-teal-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Left: Welcoming & Real-time Search Box -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-800/60 border border-emerald-500/40 text-emerald-300 text-xs font-semibold backdrop-blur-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        Amanah, Transparan, dan Diaudit Resmi oleh Tim Verifikator Pemkab Tulungagung
                    </div>

                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-white tracking-tight leading-tight font-heading">
                        Gotong Royong Nyata untuk Kesejahteraan Warga <span class="text-emerald-400">Tulungagung</span>
                    </h1>

                    <p class="text-emerald-100/90 text-sm sm:text-base leading-relaxed max-w-xl">
                        Satu pintu donasi kemanusiaan yang terverifikasi legalitasnya. Setiap rupiah disalurkan secara akuntabel dengan laporan nota belanja fisik terbuka untuk publik.
                    </p>

                    <!-- Search Filter Bar -->
                    <form action="{{ route('kausa.index') }}" method="GET" class="bg-white/10 backdrop-blur-md p-2 rounded-2xl border border-white/20 shadow-xl max-w-xl">
                        <div class="flex flex-col sm:flex-row gap-2">
                            <div class="relative flex-1">
                                <i data-lucide="search" class="w-4 h-4 text-emerald-300 absolute left-3.5 top-3.5"></i>
                                <input 
                                    name="search"
                                    type="text" 
                                    placeholder="Cari kausa, bencana, panti..." 
                                    class="w-full pl-10 pr-3 py-2.5 bg-white/10 text-white placeholder-emerald-200/60 text-xs sm:text-sm rounded-xl border border-transparent focus:border-emerald-400 focus:bg-white/20 outline-none"
                                >
                            </div>
                            <button type="submit" class="px-5 py-2.5 bg-[#087F5B] hover:bg-[#066A4C] text-white font-bold text-xs sm:text-sm rounded-xl transition-all shadow-md active:scale-95 flex items-center justify-center gap-2">
                                <i data-lucide="filter" class="w-4 h-4"></i>
                                <span>Temukan Kausa</span>
                            </button>
                        </div>
                    </form>

                    <!-- Quick Tags -->
                    <div class="flex flex-wrap items-center gap-2 text-xs text-emerald-200/80">
                        <span class="font-medium text-emerald-300">Pencarian Cepat:</span>
                        <a href="{{ route('kausa.index', ['search' => 'Banjir']) }}" class="px-2.5 py-1 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 text-emerald-100 transition-colors">Banjir Besuki</a>
                        <a href="{{ route('kausa.index', ['search' => 'Dapur Umum']) }}" class="px-2.5 py-1 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 text-emerald-100 transition-colors">Dapur Umum Campurdarat</a>
                        <a href="{{ route('kausa.index', ['search' => 'Panti']) }}" class="px-2.5 py-1 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 text-emerald-100 transition-colors">Panti Asuhan</a>
                        <a href="{{ route('kausa.index', ['search' => 'Lansia']) }}" class="px-2.5 py-1 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 text-emerald-100 transition-colors">Lansia Dhuafa</a>
                    </div>
                </div>

                <!-- Right: Verified Impact Metrics Box -->
                <div class="lg:col-span-5">
                    <div class="bg-gradient-to-br from-white/10 to-white/5 border border-white/20 rounded-3xl p-6 sm:p-8 backdrop-blur-md shadow-2xl relative">
                        <div class="flex items-center justify-between pb-6 border-b border-white/10">
                            <div>
                                <span class="text-xs uppercase tracking-wider text-emerald-300 font-semibold block">Dampak Sosial Terbuka</span>
                                <h2 class="text-lg font-bold text-white font-heading">Statistik Verifikasi Pemkab</h2>
                            </div>
                            <span class="p-2 rounded-xl bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                                <i data-lucide="shield-check" class="w-6 h-6"></i>
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 py-6">
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                                <span class="text-xs text-emerald-200/80 block mb-1">Total Dana Tersalur</span>
                                <p class="text-xl sm:text-2xl font-black text-emerald-300 font-heading">
                                    Rp {{ number_format($totalDanaTerkumpul ?? 482500000, 0, ',', '.') }}
                                </p>
                                <span class="text-[10px] text-emerald-400 mt-1 inline-flex items-center gap-1">
                                    <i data-lucide="trending-up" class="w-3 h-3"></i> 100% Diaudit PPID
                                </span>
                            </div>

                            <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                                <span class="text-xs text-emerald-200/80 block mb-1">Kausa Terverifikasi</span>
                                <p class="text-xl sm:text-2xl font-black text-white font-heading">
                                    {{ $totalKausa ?? 18 }} <span class="text-xs font-normal text-emerald-200">Program</span>
                                </p>
                                <span class="text-[10px] text-emerald-300 mt-1 inline-flex items-center gap-1">
                                    <i data-lucide="check-check" class="w-3 h-3"></i> Lolos Uji Legalitas
                                </span>
                            </div>

                            <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                                <span class="text-xs text-emerald-200/80 block mb-1">Donatur Terlibat</span>
                                <p class="text-xl sm:text-2xl font-black text-white font-heading">
                                    {{ number_format($totalDonatur ?? 1420, 0, ',', '.') }}
                                </p>
                                <span class="text-[10px] text-emerald-300 mt-1 inline-flex items-center gap-1">
                                    <i data-lucide="users" class="w-3 h-3"></i> Masyarakat Peduli
                                </span>
                            </div>

                            <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                                <span class="text-xs text-emerald-200/80 block mb-1">Transparansi Nota</span>
                                <p class="text-xl sm:text-2xl font-black text-emerald-400 font-heading">
                                    100%
                                </p>
                                <span class="text-[10px] text-emerald-300 mt-1 inline-flex items-center gap-1">
                                    <i data-lucide="file-text" class="w-3 h-3"></i> Bukti Fisik Terbuka
                                </span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-white/10 flex items-center justify-between text-xs text-emerald-200/80">
                            <span>SOP Perbup Tulungagung No. 42/2026</span>
                            <a href="{{ route('transparansi.index') }}" class="text-emerald-300 font-semibold hover:underline flex items-center gap-1">
                                Buka Pusat Transparansi &rarr;
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ==================== URGENT / SPOTLIGHT CAUSE ==================== -->
    @php
        $urgentKausa = $kausaTerbaru->first();
    @endphp
    @if ($urgentKausa)
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
        <div class="bg-white rounded-2xl border-2 border-emerald-500/30 p-6 sm:p-8 shadow-xl">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-4 relative rounded-xl overflow-hidden aspect-video lg:aspect-square bg-slate-100">
                    <img 
                        src="{{ $urgentKausa->foto_url ?? 'https://images.unsplash.com/photo-1547496502-affa22d38842?w=800&auto=format&fit=crop&q=80' }}" 
                        alt="{{ $urgentKausa->judul }}" 
                        class="w-full h-full object-cover"
                    >
                    <span class="absolute top-3 left-3 bg-red-600 text-white font-bold text-xs px-3 py-1 rounded-full uppercase tracking-wider flex items-center gap-1.5 shadow-md">
                        <span class="w-2 h-2 rounded-full bg-white animate-ping"></span> Kebutuhan Mendesak
                    </span>
                    <span class="absolute bottom-3 left-3 bg-black/60 backdrop-blur-xs text-white text-[11px] px-2.5 py-1 rounded-lg">
                        <i data-lucide="map-pin" class="w-3 h-3 inline mr-1 text-emerald-400"></i>{{ $urgentKausa->lokasi ?? 'Besuki, Kab. Tulungagung' }}
                    </span>
                </div>

                <div class="lg:col-span-8 space-y-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                            {{ $urgentKausa->kategori->nama ?? 'Bencana Alam' }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-800 border border-blue-200 flex items-center gap-1">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5 text-blue-600"></i> Terverifikasi Tim Pemkab
                        </span>
                        <span class="text-xs text-slate-400 ml-auto">
                            ID: {{ $urgentKausa->kode_registrasi ?? 'KSA-TA-'.date('Y').'-'.str_pad($urgentKausa->id, 3, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>

                    <h2 class="text-xl sm:text-2xl font-extrabold text-[#17211E] font-heading leading-tight hover:text-[#087F5B] transition-colors">
                        <a href="{{ route('kausa.show', $urgentKausa->slug) }}">{{ $urgentKausa->judul }}</a>
                    </h2>

                    <p class="text-xs sm:text-sm text-[#52615C] leading-relaxed line-clamp-2">
                        {{ $urgentKausa->ringkasan ?? $urgentKausa->deskripsi }}
                    </p>

                    <!-- Progress Bar -->
                    @php
                        $terkumpul = $urgentKausa->donasi ? $urgentKausa->donasi->where('status', 'berhasil')->sum('nominal') : 0;
                        $target = $urgentKausa->target_dana > 0 ? $urgentKausa->target_dana : 1;
                        $persen = min(100, round(($terkumpul / $target) * 100));
                    @endphp
                    <div class="space-y-2 bg-[#F6F8F7] p-4 rounded-xl border border-[#D9E2DE]">
                        <div class="flex justify-between items-baseline text-xs sm:text-sm">
                            <div>
                                <span class="text-[#73817C]">Terkumpul</span>
                                <p class="text-base sm:text-lg font-bold text-[#087F5B]">Rp {{ number_format($terkumpul, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-[#73817C]">Target Donasi</span>
                                <p class="text-sm font-semibold text-slate-800">Rp {{ number_format($urgentKausa->target_dana, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                            <div class="bg-gradient-to-r from-[#087F5B] to-emerald-400 h-3 rounded-full transition-all duration-500" style="width: {{ $persen }}%"></div>
                        </div>

                        <div class="flex justify-between items-center text-[11px] text-[#73817C] pt-1">
                            <span class="font-bold text-emerald-800">{{ $persen }}% Tercapai</span>
                            <span>{{ $urgentKausa->donasi ? $urgentKausa->donasi->where('status', 'berhasil')->count() : 0 }} Donatur Terlibat</span>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <button 
                            @click="openDonation('{{ addslashes($urgentKausa->judul) }}', {{ $urgentKausa->id }}, '{{ $urgentKausa->slug }}')"
                            type="button" 
                            class="flex-1 py-3 px-6 rounded-xl bg-[#087F5B] hover:bg-[#066A4C] text-white font-bold text-sm tracking-wide shadow-md hover:shadow-lg transition-all active:scale-95 flex items-center justify-center gap-2"
                        >
                            <i data-lucide="heart" class="w-4 h-4 fill-white"></i>
                            <span>Salurkan Donasi Cepat</span>
                        </button>
                        <a 
                            href="{{ route('kausa.show', $urgentKausa->slug) }}" 
                            class="py-3 px-6 rounded-xl bg-white border border-[#D9E2DE] hover:border-[#087F5B] text-slate-800 hover:text-[#087F5B] font-semibold text-sm transition-all flex items-center justify-center gap-2"
                        >
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            <span>Lihat Rincian &amp; Bukti Fisik</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- ==================== MAIN CATALOG SECTION ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <span class="text-xs uppercase tracking-wider text-[#087F5B] font-bold block mb-1">Katalog Terpadu</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#17211E] font-heading">Program Kausa Sosial Aktif</h2>
                <p class="text-xs sm:text-sm text-[#73817C] mt-1">Diverifikasi oleh PPID Sekretariat Daerah dan Dinas Sosial Kab. Tulungagung</p>
            </div>
            <a href="{{ route('kausa.index') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-[#087F5B] hover:text-[#066A4C] hover:underline">
                Lihat Seluruh Kausa ({{ $totalKausa ?? 0 }}) &rarr;
            </a>
        </div>

        <!-- Filter Category Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-3 custom-scrollbar mb-8">
            <button 
                @click="activeTab = 'all'" 
                :class="activeTab === 'all' ? 'bg-[#087F5B] text-white shadow-xs' : 'bg-white text-[#52615C] hover:bg-emerald-50 border border-[#D9E2DE]'"
                class="px-4 py-2 rounded-xl font-bold text-xs whitespace-nowrap transition-all flex items-center gap-2"
            >
                <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i> Semua Kausa
            </button>
            <button 
                @click="activeTab = 'bencana'" 
                :class="activeTab === 'bencana' ? 'bg-[#087F5B] text-white shadow-xs' : 'bg-white text-[#52615C] hover:bg-emerald-50 border border-[#D9E2DE]'"
                class="px-4 py-2 rounded-xl font-bold text-xs whitespace-nowrap transition-all flex items-center gap-2"
            >
                <i data-lucide="flame" class="w-3.5 h-3.5 text-red-500"></i> Bencana Alam
            </button>
            <button 
                @click="activeTab = 'panti'" 
                :class="activeTab === 'panti' ? 'bg-[#087F5B] text-white shadow-xs' : 'bg-white text-[#52615C] hover:bg-emerald-50 border border-[#D9E2DE]'"
                class="px-4 py-2 rounded-xl font-bold text-xs whitespace-nowrap transition-all flex items-center gap-2"
            >
                <i data-lucide="heart-handshake" class="w-3.5 h-3.5 text-amber-500"></i> Panti Asuhan
            </button>
            <button 
                @click="activeTab = 'ibadah'" 
                :class="activeTab === 'ibadah' ? 'bg-[#087F5B] text-white shadow-xs' : 'bg-white text-[#52615C] hover:bg-emerald-50 border border-[#D9E2DE]'"
                class="px-4 py-2 rounded-xl font-bold text-xs whitespace-nowrap transition-all flex items-center gap-2"
            >
                <i data-lucide="building" class="w-3.5 h-3.5 text-blue-500"></i> Tempat Ibadah
            </button>
            <button 
                @click="activeTab = 'lansia'" 
                :class="activeTab === 'lansia' ? 'bg-[#087F5B] text-white shadow-xs' : 'bg-white text-[#52615C] hover:bg-emerald-50 border border-[#D9E2DE]'"
                class="px-4 py-2 rounded-xl font-bold text-xs whitespace-nowrap transition-all flex items-center gap-2"
            >
                <i data-lucide="user-check" class="w-3.5 h-3.5 text-purple-500"></i> Lansia &amp; Dhuafa
            </button>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($kausaTerbaru as $kausa)
                @php
                    $terkumpul = $kausa->donasi ? $kausa->donasi->where('status', 'berhasil')->sum('nominal') : 0;
                    $target = $kausa->target_dana > 0 ? $kausa->target_dana : 1;
                    $persen = min(100, round(($terkumpul / $target) * 100));
                    $kategoriSlug = Str::slug($kausa->kategori->nama ?? 'umum');
                @endphp
                <article 
                    x-show="activeTab === 'all' || '{{ $kategoriSlug }}'.includes(activeTab)"
                    class="bg-white rounded-2xl border border-[#D9E2DE] overflow-hidden hover:shadow-xl transition-all flex flex-col group"
                >
                    <!-- Card Media Header -->
                    <div class="relative h-48 w-full bg-slate-100 overflow-hidden">
                        <img 
                            src="{{ $kausa->foto_url ?? 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=600&auto=format&fit=crop&q=80' }}" 
                            alt="{{ $kausa->judul }}" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[11px] font-bold bg-white/90 text-slate-800 backdrop-blur-xs shadow-xs">
                            {{ $kausa->kategori->nama ?? 'Sosial' }}
                        </span>
                        <span class="absolute bottom-3 left-3 text-white text-[11px] font-medium flex items-center gap-1">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-emerald-400"></i> {{ $kausa->lokasi ?? 'Kab. Tulungagung' }}
                        </span>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <div class="flex items-center gap-1 text-[11px] text-emerald-700 font-semibold">
                                <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i> Legalitas Resmi Terverifikasi
                            </div>
                            <h3 class="font-heading font-bold text-base text-[#17211E] leading-snug group-hover:text-[#087F5B] transition-colors line-clamp-2">
                                <a href="{{ route('kausa.show', $kausa->slug) }}">{{ $kausa->judul }}</a>
                            </h3>
                            <p class="text-xs text-[#73817C] line-clamp-2 leading-relaxed">
                                {{ $kausa->ringkasan ?? $kausa->deskripsi }}
                            </p>
                        </div>

                        <!-- Progress Bar & Target -->
                        <div class="space-y-2 pt-2 border-t border-slate-100">
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-[#087F5B] h-2 rounded-full" style="width: {{ $persen }}%"></div>
                            </div>
                            <div class="flex justify-between items-baseline text-xs">
                                <div>
                                    <span class="text-[10px] text-slate-400 block">Terkumpul</span>
                                    <span class="font-bold text-[#087F5B]">Rp {{ number_format($terkumpul, 0, ',', '.') }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] text-slate-400 block">Target</span>
                                    <span class="font-semibold text-slate-700">Rp {{ number_format($kausa->target_dana, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="grid grid-cols-2 gap-2 pt-2">
                            <a 
                                href="{{ route('kausa.show', $kausa->slug) }}" 
                                class="py-2 px-3 text-center rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold text-xs border border-slate-200 transition-colors"
                            >
                                Detail &amp; Nota
                            </a>
                            <button 
                                @click="openDonation('{{ addslashes($kausa->judul) }}', {{ $kausa->id }}, '{{ $kausa->slug }}')"
                                type="button" 
                                class="py-2 px-3 text-center rounded-xl bg-[#087F5B] hover:bg-[#066A4C] text-white font-bold text-xs tracking-wide shadow-xs transition-all active:scale-95 flex items-center justify-center gap-1.5"
                            >
                                <i data-lucide="heart" class="w-3.5 h-3.5"></i> Donasi
                            </button>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center py-12 bg-white rounded-2xl border border-dashed border-slate-300 p-8">
                    <i data-lucide="folder-search" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i>
                    <h3 class="font-heading font-bold text-slate-700 text-base">Belum Ada Kausa yang Ditampilkan</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Pengajuan program baru sedang dalam tahap verifikasi oleh Tim Verifikator Pemkab.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- ==================== TRANSPARENCY EXPLAINER CARD ==================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="bg-gradient-to-r from-emerald-950 via-secondary to-[#0D2D26] rounded-3xl p-8 sm:p-10 text-white shadow-xl border border-emerald-800/60 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8">
                <div class="space-y-3 max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-800/80 text-emerald-300 text-xs font-semibold">
                        <i data-lucide="file-check-2" class="w-3.5 h-3.5"></i> Standar Keterbukaan Informasi Publik PPID
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold font-heading text-white">Setiap Penyaluran Dilengkapi Foto Nota &amp; BAST Fisik</h2>
                    <p class="text-xs sm:text-sm text-emerald-100/90 leading-relaxed">
                        Pemerintah Kabupaten Tulungagung menerapkan verifikasi berlapis. Dana tidak langsung dicairkan tanpa Berita Acara Serah Terima (BAST) dan nota kuitansi bermeterai yang dipublikasikan langsung ke halaman publik.
                    </p>
                    <div class="flex flex-wrap items-center gap-4 pt-2 text-xs text-emerald-300">
                        <span class="flex items-center gap-1.5"><i data-lucide="check" class="w-4 h-4 text-emerald-400"></i> Tanpa Potongan Tersembunyi</span>
                        <span class="flex items-center gap-1.5"><i data-lucide="check" class="w-4 h-4 text-emerald-400"></i> Audit Terbuka 24 Jam</span>
                        <span class="flex items-center gap-1.5"><i data-lucide="check" class="w-4 h-4 text-emerald-400"></i> Bebas Kausa Fiktif</span>
                    </div>
                </div>

                <div class="shrink-0 flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <a href="{{ route('transparansi.index') }}" class="px-6 py-3.5 rounded-xl bg-white text-[#123B32] hover:bg-emerald-50 text-xs sm:text-sm font-bold tracking-wide transition-all shadow-md active:scale-95 text-center flex items-center justify-center gap-2">
                        <i data-lucide="search-check" class="w-4 h-4 text-[#087F5B]"></i>
                        <span>Buka Pusat Transparansi Penyaluran</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== ALUR 3 LANGKAH ==================== -->
    <section class="bg-white border-y border-[#D9E2DE] py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-12">
                <span class="text-xs uppercase tracking-wider text-[#087F5B] font-bold block mb-1">Mekanisme Kerja</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#17211E] font-heading">Alur Verifikasi Bantuan Resmi</h2>
                <p class="text-xs sm:text-sm text-[#73817C] mt-2">Mekanisme resmi menjamin setiap bantuan benar-benar sampai kepada yang berhak</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                <!-- Step 1 -->
                <div class="bg-[#F6F8F7] p-6 rounded-2xl border border-[#D9E2DE] relative">
                    <div class="w-12 h-12 rounded-xl bg-[#087F5B] text-white flex items-center justify-center font-bold text-lg font-heading mb-4 shadow-sm">
                        1
                    </div>
                    <h3 class="font-heading font-bold text-base text-[#17211E] mb-2">Pengajuan Berkas Resmi</h3>
                    <p class="text-xs text-[#52615C] leading-relaxed">
                        Instansi kedinasan atau yayasan sosial berbadan hukum mengajukan program bantuan dengan melampirkan Rincian Anggaran Biaya (RAB) dan SK Legalitas.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="bg-[#F6F8F7] p-6 rounded-2xl border border-[#D9E2DE] relative">
                    <div class="w-12 h-12 rounded-xl bg-secondary text-white flex items-center justify-center font-bold text-lg font-heading mb-4 shadow-sm">
                        2
                    </div>
                    <h3 class="font-heading font-bold text-base text-[#17211E] mb-2">Verifikasi Lapangan Pemkab</h3>
                    <p class="text-xs text-[#52615C] leading-relaxed">
                        Tim kurasi gabungan Dinsos dan Bagian Hukum memverifikasi dokumen fisik, kesesuaian penerima manfaat, dan memastikan tidak ada duplikasi anggaran.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="bg-[#F6F8F7] p-6 rounded-2xl border border-[#D9E2DE] relative">
                    <div class="w-12 h-12 rounded-xl bg-[#087F5B] text-white flex items-center justify-center font-bold text-lg font-heading mb-4 shadow-sm">
                        3
                    </div>
                    <h3 class="font-heading font-bold text-base text-[#17211E] mb-2">Penyaluran &amp; Publikasi Nota</h3>
                    <p class="text-xs text-[#52615C] leading-relaxed">
                        Setelah dana dicairkan bertahap, pengaju wajib mengunggah foto nota belanja fisik, kuitansi bermeterai, dan dokumentasi penyerahan barang bantuan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== MODAL QUICK DONATION SIMULATOR ==================== -->
    <div 
        x-show="donationModalOpen" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
        @keydown.escape.window="donationModalOpen = false"
    >
        <div 
            @click.outside="donationModalOpen = false"
            class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200 relative"
        >
            <button 
                @click="donationModalOpen = false" 
                type="button" 
                class="absolute top-4 right-4 p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
            >
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <div class="space-y-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center">
                        <i data-lucide="heart" class="w-5 h-5 fill-emerald-700 text-emerald-700"></i>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-base text-[#17211E]">Salurkan Bantuan Kausa</h3>
                        <p class="text-xs text-[#73817C]">Portal Donasi Resmi Pemkab Tulungagung</p>
                    </div>
                </div>

                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs space-y-1.5">
                    <span class="text-slate-500 text-[11px] block">Kausa Pilihan:</span>
                    <p class="font-bold text-slate-900 text-sm leading-snug" x-text="selectedKausaTitle"></p>
                    <div class="flex items-center gap-1 text-[11px] text-emerald-700 pt-1 font-semibold">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-600"></i> Terverifikasi Bebas Pungli
                    </div>
                </div>

                <!-- Nominal Presets -->
                <div class="space-y-2">
                    <label class="block text-xs font-semibold text-slate-700">Pilih Nominal Donasi:</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button 
                            type="button" 
                            @click="selectedAmount = 25000" 
                            :class="selectedAmount === 25000 ? 'bg-emerald-50 border-[#087F5B] text-[#087F5B]' : 'bg-white border-slate-200 text-slate-700'"
                            class="py-2.5 text-xs font-bold rounded-xl border transition-all text-center"
                        >
                            Rp 25.000
                        </button>
                        <button 
                            type="button" 
                            @click="selectedAmount = 50000" 
                            :class="selectedAmount === 50000 ? 'bg-emerald-50 border-[#087F5B] text-[#087F5B]' : 'bg-white border-slate-200 text-slate-700'"
                            class="py-2.5 text-xs font-bold rounded-xl border transition-all text-center"
                        >
                            Rp 50.000
                        </button>
                        <button 
                            type="button" 
                            @click="selectedAmount = 100000" 
                            :class="selectedAmount === 100000 ? 'bg-emerald-50 border-[#087F5B] text-[#087F5B]' : 'bg-white border-slate-200 text-slate-700'"
                            class="py-2.5 text-xs font-bold rounded-xl border transition-all text-center"
                        >
                            Rp 100.000
                        </button>
                    </div>
                    <div class="pt-1">
                        <input 
                            type="number" 
                            x-model.number="selectedAmount" 
                            placeholder="Nominal lainnya (Rp)" 
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:border-[#087F5B] focus:bg-white rounded-xl text-xs outline-none"
                        >
                    </div>
                </div>

                <!-- Info simulation note -->
                <div class="p-3 bg-emerald-50/80 rounded-xl text-xs text-emerald-900 flex items-start gap-2">
                    <i data-lucide="info" class="w-4 h-4 text-emerald-700 shrink-0 mt-0.5"></i>
                    <p class="text-[11px] leading-relaxed">
                        Klik tombol di bawah untuk membuka halaman detail lengkap kausa beserta bukti nota belanja fisik dan opsi pembayaran QRIS / Virtual Account.
                    </p>
                </div>

                <a 
                    :href="'/kausa/' + selectedKausaSlug" 
                    class="w-full py-3 bg-[#087F5B] hover:bg-[#066A4C] text-white font-bold text-xs rounded-xl tracking-wide shadow-md transition-all active:scale-95 flex items-center justify-center gap-2 text-center"
                >
                    <span>Lanjutkan Pembayaran Donasi</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
