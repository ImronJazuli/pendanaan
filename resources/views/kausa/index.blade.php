@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F6F8F7] pb-16">
    <!-- Breadcrumb & Top Indicator -->
    <div class="bg-white border-b border-[#D9E2DE]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2 text-slate-500">
                    <a href="{{ route('landing') }}" class="hover:text-[#087F5B] flex items-center gap-1 font-medium">
                        <i data-lucide="home" class="w-3.5 h-3.5"></i> Beranda
                    </a>
                    <i data-lucide="chevron-right" class="w-3 h-3 text-slate-300"></i>
                    <span class="text-[#087F5B] font-bold">Katalog Kausa Terpadu</span>
                </div>
                <div class="hidden sm:flex items-center gap-1.5 text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200 text-[11px] font-semibold">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-600"></i> Seluruh Kausa Tervalidasi
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
        <!-- Header Title -->
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#17211E] font-heading tracking-tight leading-tight">
                Katalog Program Kausa Pemkab Tulungagung
            </h1>
            <p class="text-xs sm:text-sm text-[#52615C] mt-1.5 max-w-2xl leading-relaxed">
                Seluruh program bantuan sosial, kedaruratan bencana, panti asuhan, dan rehabilitasi sosial yang telah diverifikasi kelayakan dan legalitasnya oleh tim verifikator Pemkab Tulungagung.
            </p>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 mb-8 shadow-xs">
            <form method="GET" action="{{ route('kausa.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search Input -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-[#17211E] mb-1.5 uppercase tracking-wider">Cari Kata Kunci</label>
                        <div class="relative">
                            <i data-lucide="search" class="w-4 h-4 text-emerald-700 absolute left-3.5 top-3"></i>
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}" 
                                placeholder="Judul program, lokasi bencana, panti..." 
                                class="w-full pl-10 pr-4 py-2.5 bg-[#F6F8F7] border border-[#D9E2DE] focus:border-[#087F5B] focus:bg-white rounded-xl text-xs sm:text-sm text-[#17211E] outline-none transition-all"
                            >
                        </div>
                    </div>

                    <!-- Kategori Dropdown -->
                    <div>
                        <label class="block text-xs font-bold text-[#17211E] mb-1.5 uppercase tracking-wider">Kategori Program</label>
                        <select 
                            name="kategori" 
                            class="w-full px-3.5 py-2.5 bg-[#F6F8F7] border border-[#D9E2DE] focus:border-[#087F5B] focus:bg-white rounded-xl text-xs sm:text-sm text-[#17211E] outline-none transition-all"
                        >
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" @selected(request('kategori') == $kategori->id)>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sorting Dropdown -->
                    <div>
                        <label class="block text-xs font-bold text-[#17211E] mb-1.5 uppercase tracking-wider">Urutan Tampil</label>
                        <select 
                            name="sort" 
                            class="w-full px-3.5 py-2.5 bg-[#F6F8F7] border border-[#D9E2DE] focus:border-[#087F5B] focus:bg-white rounded-xl text-xs sm:text-sm text-[#17211E] outline-none transition-all"
                        >
                            <option value="terbaru" @selected(request('sort') == 'terbaru' || !request('sort'))>Terbaru Ditayangkan</option>
                            <option value="populer" @selected(request('sort') == 'populer')>Donasi Terbanyak</option>
                            <option value="target_tinggi" @selected(request('sort') == 'target_tinggi')>Target Tertinggi</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                    <span class="text-xs text-slate-500">
                        Menampilkan <strong class="text-slate-800">{{ $kausa->total() }}</strong> kausa aktif
                    </span>
                    <div class="flex items-center gap-2">
                        @if (request()->hasAny(['search', 'kategori', 'sort']))
                            <a href="{{ route('kausa.index') }}" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                                Reset Filter
                            </a>
                        @endif
                        <button type="submit" class="px-5 py-2 bg-[#087F5B] hover:bg-[#066A4C] text-white font-bold text-xs rounded-xl shadow-xs transition-all active:scale-95 flex items-center gap-1.5">
                            <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                            <span>Terapkan Filter</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Catalog Grid -->
        @if ($kausa->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                @foreach ($kausa as $item)
                    @php
                        $terkumpul = $item->total_terkumpul;
                        $target = $item->target_dana > 0 ? $item->target_dana : 1;
                        $persen = $item->persentase_progress;
                    @endphp
                    <article class="bg-white rounded-2xl border border-[#D9E2DE] overflow-hidden hover:shadow-xl transition-all flex flex-col group">
                        <!-- Media Header -->
                        <div class="relative h-48 w-full bg-slate-100 overflow-hidden">
                            <img 
                                src="{{ $item->foto_url ?? 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=600&auto=format&fit=crop&q=80' }}" 
                                alt="{{ $item->judul }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[11px] font-bold bg-white/95 text-slate-800 shadow-xs">
                                {{ $item->kategori->nama ?? 'Sosial' }}
                            </span>
                            <span class="absolute bottom-3 left-3 text-white text-[11px] font-medium flex items-center gap-1">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-emerald-400"></i> {{ $item->lokasi ?? 'Kab. Tulungagung' }}
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <div class="space-y-2">
                                <div class="flex items-center gap-1 text-[11px] text-emerald-700 font-semibold">
                                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-600"></i> Terverifikasi Bebas Pungli
                                </div>
                                <h3 class="font-heading font-bold text-base text-[#17211E] leading-snug group-hover:text-[#087F5B] transition-colors line-clamp-2">
                                    <a href="{{ route('kausa.show', $item->slug) }}">{{ $item->judul }}</a>
                                </h3>
                                <p class="text-xs text-[#73817C] line-clamp-2 leading-relaxed">
                                    {{ $item->ringkasan ?? $item->deskripsi }}
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
                                        <span class="font-semibold text-slate-700">Rp {{ number_format($item->target_dana, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="grid grid-cols-2 gap-2 pt-2">
                                <a 
                                    href="{{ route('kausa.show', $item->slug) }}" 
                                    class="py-2.5 px-3 text-center rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold text-xs border border-slate-200 transition-colors"
                                >
                                    Detail &amp; Nota
                                </a>
                                <a 
                                    href="{{ route('kausa.show', $item->slug) }}#donasi" 
                                    class="py-2.5 px-3 text-center rounded-xl bg-[#087F5B] hover:bg-[#066A4C] text-white font-bold text-xs tracking-wide shadow-xs transition-all active:scale-95 flex items-center justify-center gap-1.5"
                                >
                                    <i data-lucide="heart" class="w-3.5 h-3.5"></i> Donasi
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $kausa->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16 bg-white rounded-3xl border border-dashed border-slate-300 p-8 shadow-xs">
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-[#087F5B] flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="search-x" class="w-8 h-8"></i>
                </div>
                <h3 class="font-heading font-bold text-slate-800 text-lg">Tidak Ada Kausa yang Cocok</h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-md mx-auto">
                    Kriteria pencarian Anda tidak menemukan kausa aktif. Coba ubah kata kunci atau pilih semua kategori.
                </p>
                <div class="mt-6">
                    <a href="{{ route('kausa.index') }}" class="px-5 py-2.5 bg-[#087F5B] text-white font-bold text-xs rounded-xl shadow-xs hover:bg-[#066A4C] transition-all inline-flex items-center gap-2">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        <span>Tampilkan Semua Kausa</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
