@extends('layouts.app')

@section('content')
@php
    $terkumpul = $kausa->total_terkumpul;
    $target = $kausa->target_dana > 0 ? $kausa->target_dana : 1;
    $persen = $kausa->persentase_progress;
    $sisaHari = $kausa->tanggal_berakhir ? max(0, now()->diffInDays($kausa->tanggal_berakhir, false)) : 30;
    $kausaIdCode = 'KSA-TA-'.($kausa->created_at ? $kausa->created_at->format('Y') : date('Y')).'-'.str_pad($kausa->id, 3, '0', STR_PAD_LEFT);
@endphp

<div x-data="{
    activeTab: 'deskripsi',
    selectedNominal: 50000,
    customNominal: '',
    isAnonim: false,
    donorName: '{{ auth()->check() ? auth()->user()->name : '' }}',
    doaDukungan: '',
    paymentMethod: 'qris',
    previewReceiptOpen: false,
    previewReceiptTitle: '',
    previewReceiptSrc: '',
    donationSuccessModal: false,
    checkoutSimulate() {
        this.donationSuccessModal = true;
    },
    openReceipt(title, src) {
        this.previewReceiptTitle = title;
        this.previewReceiptSrc = src;
        this.previewReceiptOpen = true;
    }
}" class="min-h-screen bg-[#F6F8F7] pb-20">

    <!-- ================= BREADCRUMB & REGISTRY BAR ================= -->
    <div class="bg-white border-b border-[#D9E2DE]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex flex-wrap items-center justify-between gap-3 text-xs">
                <nav class="flex items-center gap-2 text-[#73817C] flex-wrap">
                    <a href="{{ route('landing') }}" class="hover:text-[#087F5B] flex items-center gap-1 font-medium">
                        <i data-lucide="home" class="w-3.5 h-3.5"></i> Beranda
                    </a>
                    <i data-lucide="chevron-right" class="w-3 h-3 text-slate-300"></i>
                    <a href="{{ route('kausa.index') }}" class="hover:text-[#087F5B]">Katalog</a>
                    <i data-lucide="chevron-right" class="w-3 h-3 text-slate-300"></i>
                    <span class="text-[#087F5B] font-medium">{{ $kausa->kategori->nama ?? 'Sosial' }}</span>
                    <i data-lucide="chevron-right" class="w-3 h-3 text-slate-300"></i>
                    <span class="text-[#17211E] font-semibold truncate max-w-xs sm:max-w-md">{{ $kausa->judul }}</span>
                </nav>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-300 flex items-center gap-1">
                        <i data-lucide="check-circle-2" class="w-3 h-3 text-emerald-600"></i> Kausa Aktif Terverifikasi
                    </span>
                    <span class="text-[#73817C] text-[11px] font-mono">ID: {{ $kausaIdCode }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= MAIN CONTAINER ================= -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
        
        <!-- Header Info Block -->
        <div class="mb-6 space-y-3">
            <div class="flex flex-wrap items-center gap-2 text-xs">
                <span class="px-3 py-1 rounded-full font-bold bg-[#E6F4EF] text-[#087F5B]">
                    {{ $kausa->kategori->nama ?? 'Sosial' }}
                </span>
                <span class="text-[#52615C] flex items-center gap-1">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-emerald-700"></i> {{ $kausa->lokasi ?? 'Kabupaten Tulungagung' }}
                </span>
                <span class="text-slate-300">&bull;</span>
                <span class="text-[#73817C] flex items-center gap-1">
                    <i data-lucide="building-2" class="w-3.5 h-3.5 text-slate-400"></i>
                    Diajukan oleh: <strong class="text-slate-800">{{ $kausa->instansi->nama ?? 'Instansi Terverifikasi' }}</strong>
                </span>
            </div>

            <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#17211E] font-heading leading-tight tracking-tight">
                {{ $kausa->judul }}
            </h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Main Column (Gallery, Tabs, Detail Content) -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Hero Media / Image Gallery -->
                <div class="relative rounded-3xl overflow-hidden bg-slate-900 border border-[#D9E2DE] shadow-sm">
                    <div class="aspect-[16/9] w-full bg-slate-100 overflow-hidden">
                        <img 
                            src="{{ $kausa->foto_url ?? 'https://images.unsplash.com/photo-1547496502-affa22d38842?w=1000&auto=format&fit=crop&q=80' }}" 
                            alt="{{ $kausa->judul }}" 
                            class="w-full h-full object-cover"
                        >
                    </div>
                    <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-4 sm:p-6 flex flex-wrap items-center justify-between gap-3 text-white">
                        <div class="flex items-center gap-2 text-xs">
                            <span class="bg-emerald-600/90 text-white font-bold px-2.5 py-1 rounded-lg flex items-center gap-1">
                                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> Audit Bebas Manipulasi
                            </span>
                            <span class="text-emerald-100 hidden sm:inline">&bull; Dipublikasikan secara resmi oleh Pemkab</span>
                        </div>
                        <span class="text-xs text-slate-300">
                            Terakhir diupdate: {{ $kausa->updated_at ? $kausa->updated_at->diffForHumans() : 'Baru saja' }}
                        </span>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <div class="border-b border-[#D9E2DE] bg-white rounded-2xl p-1.5 shadow-xs flex items-center gap-1 overflow-x-auto custom-scrollbar">
                    <button 
                        @click="activeTab = 'deskripsi'" 
                        :class="activeTab === 'deskripsi' ? 'bg-[#087F5B] text-white shadow-xs' : 'text-[#52615C] hover:bg-slate-50'"
                        class="px-4 py-2.5 rounded-xl font-bold text-xs whitespace-nowrap transition-all flex items-center gap-2"
                    >
                        <i data-lucide="align-left" class="w-4 h-4"></i>
                        <span>Deskripsi &amp; Urgensi</span>
                    </button>
                    <button 
                        @click="activeTab = 'rab'" 
                        :class="activeTab === 'rab' ? 'bg-[#087F5B] text-white shadow-xs' : 'text-[#52615C] hover:bg-slate-50'"
                        class="px-4 py-2.5 rounded-xl font-bold text-xs whitespace-nowrap transition-all flex items-center gap-2"
                    >
                        <i data-lucide="calculator" class="w-4 h-4"></i>
                        <span>RAB &amp; Legalitas</span>
                    </button>
                    <button 
                        @click="activeTab = 'transparansi'" 
                        :class="activeTab === 'transparansi' ? 'bg-[#087F5B] text-white shadow-xs' : 'text-[#52615C] hover:bg-slate-50'"
                        class="px-4 py-2.5 rounded-xl font-bold text-xs whitespace-nowrap transition-all flex items-center gap-2"
                    >
                        <i data-lucide="file-check-2" class="w-4 h-4 text-emerald-300"></i>
                        <span>Pusat Transparansi Nota</span>
                        <span class="px-1.5 py-0.2 rounded-full bg-amber-400 text-amber-950 font-bold text-[10px]">Real-Time</span>
                    </button>
                    <button 
                        @click="activeTab = 'donatur'" 
                        :class="activeTab === 'donatur' ? 'bg-[#087F5B] text-white shadow-xs' : 'text-[#52615C] hover:bg-slate-50'"
                        class="px-4 py-2.5 rounded-xl font-bold text-xs whitespace-nowrap transition-all flex items-center gap-2"
                    >
                        <i data-lucide="users" class="w-4 h-4"></i>
                        <span>Donatur ({{ $jumlahDonatur ?? 0 }})</span>
                    </button>
                </div>

                <!-- TAB 1: DESKRIPSI & URGENSI -->
                <div x-show="activeTab === 'deskripsi'" class="bg-white rounded-3xl border border-[#D9E2DE] p-6 sm:p-8 space-y-6 shadow-xs">
                    <div>
                        <h2 class="font-heading font-bold text-lg sm:text-xl text-[#17211E] mb-3">Latar Belakang &amp; Kondisi Lapangan</h2>
                        <div class="prose prose-slate max-w-none text-xs sm:text-sm text-[#52615C] leading-relaxed space-y-4">
                            <p class="font-medium text-slate-800 bg-[#F6F8F7] p-4 rounded-xl border border-[#D9E2DE]">
                                {{ $kausa->ringkasan }}
                            </p>
                            <div class="whitespace-pre-line">
                                {{ $kausa->deskripsi }}
                            </div>
                        </div>
                    </div>

                    <!-- Lembaga Pengaju Card -->
                    <div class="pt-6 border-t border-slate-100">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Informasi Lembaga Pengaju</h3>
                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-[#F6F8F7] border border-[#D9E2DE]">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-lg shrink-0">
                                <i data-lucide="building-2" class="w-6 h-6 text-emerald-700"></i>
                            </div>
                            <div class="space-y-1 text-xs">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-slate-900 text-sm">{{ $kausa->instansi->nama ?? 'Instansi Penanggung Jawab' }}</h4>
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Terverifikasi</span>
                                </div>
                                <p class="text-slate-600">{{ $kausa->instansi->alamat ?? 'Wilayah Kabupaten Tulungagung' }}</p>
                                <p class="text-[11px] text-slate-500">Penanggung Jawab: <span class="font-semibold text-slate-700">{{ $kausa->instansi->nama_pj ?? 'Pimpinan Lembaga Terdaftar' }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: RAB & LEGALITAS -->
                <div x-show="activeTab === 'rab'" x-cloak class="bg-white rounded-3xl border border-[#D9E2DE] p-6 sm:p-8 space-y-6 shadow-xs">
                    <div>
                        <h2 class="font-heading font-bold text-lg sm:text-xl text-[#17211E] mb-1">Rincian Anggaran Biaya (RAB)</h2>
                        <p class="text-xs text-slate-500 mb-4">Estimasi rencana alokasi penggunaan dana yang telah disetujui tim verifikasi.</p>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="bg-[#F6F8F7] text-slate-700 font-bold border-b border-slate-200">
                                        <th class="py-3 px-4 rounded-l-xl">Komponen Kebutuhan</th>
                                        <th class="py-3 px-4">Alokasi Sasaran</th>
                                        <th class="py-3 px-4 text-right rounded-r-xl">Estimasi Biaya</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr>
                                        <td class="py-3.5 px-4 font-semibold text-slate-800">Bantuan Logistik &amp; Pangan Langsung</td>
                                        <td class="py-3.5 px-4 text-slate-600">Penerima Manfaat Lapangan</td>
                                        <td class="py-3.5 px-4 font-bold text-right text-slate-900">Rp {{ number_format($kausa->target_dana * 0.65, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3.5 px-4 font-semibold text-slate-800">Peralatan, Sanitasi &amp; Pemulihan</td>
                                        <td class="py-3.5 px-4 text-slate-600">Fasilitas / Korban Terdampak</td>
                                        <td class="py-3.5 px-4 font-bold text-right text-slate-900">Rp {{ number_format($kausa->target_dana * 0.25, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3.5 px-4 font-semibold text-slate-800">Distribusi Operasional Lapangan</td>
                                        <td class="py-3.5 px-4 text-slate-600">Penyaluran Resmi Desa/Kecamatan</td>
                                        <td class="py-3.5 px-4 font-bold text-right text-slate-900">Rp {{ number_format($kausa->target_dana * 0.10, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="bg-emerald-50/50 font-bold">
                                        <td colspan="2" class="py-3.5 px-4 text-[#123B32]">Total Target Penggalangan</td>
                                        <td class="py-3.5 px-4 text-right text-[#087F5B] text-sm">Rp {{ number_format($kausa->target_dana, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Dokumen Pendukung Fisik -->
                    <div class="pt-6 border-t border-slate-100">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Dokumen Berkas Terverifikasi</h3>
                        @if ($kausa->dokumen && $kausa->dokumen->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach ($kausa->dokumen as $doc)
                                    <div class="flex items-center justify-between p-3.5 rounded-xl bg-[#F6F8F7] border border-[#D9E2DE]">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <i data-lucide="file-text" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-slate-800 truncate">{{ $doc->nama_file }}</p>
                                                <p class="text-[10px] text-slate-400">{{ number_format($doc->ukuran_file / 1024, 1) }} KB</p>
                                            </div>
                                        </div>
                                        <a href="{{ Storage::url($doc->path_file) }}" target="_blank" class="px-3 py-1 bg-white border border-slate-200 hover:border-emerald-600 text-emerald-700 text-xs font-bold rounded-lg transition-colors">
                                            Lihat
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-4 rounded-xl bg-slate-50 text-slate-500 text-xs flex items-center gap-2">
                                <i data-lucide="file-check" class="w-4 h-4 text-emerald-600"></i>
                                <span>Dokumen legalitas fisik dan surat keterangan desa telah diaudit internal oleh tim kurator Dinsos Tulungagung.</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- TAB 3: PUSAT TRANSPARANSI NOTA FISIK -->
                <div x-show="activeTab === 'transparansi'" x-cloak class="bg-white rounded-3xl border border-[#D9E2DE] p-6 sm:p-8 space-y-6 shadow-xs">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                        <div>
                            <h2 class="font-heading font-bold text-lg sm:text-xl text-[#17211E]">Keterbukaan Pengeluaran Dana &amp; Bukti Nota</h2>
                            <p class="text-xs text-slate-500 mt-1">Transparansi langsung: Setiap kuitansi diverifikasi petugas sebelum ditampilkan.</p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold flex items-center gap-1.5 shrink-0">
                            <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                            Audit Status: Bersih &amp; Sesuai SOP
                        </span>
                    </div>

                    <!-- Timeline Bukti Pengeluaran -->
                    <div class="space-y-4">
                        <div class="p-4 rounded-2xl bg-[#F6F8F7] border border-[#D9E2DE] space-y-3">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#087F5B] text-white">Tahap 1</span>
                                    <h4 class="font-bold text-slate-900 text-xs sm:text-sm">Pembelian Sembako &amp; Beras Dapur Umum</h4>
                                </div>
                                <span class="font-mono font-bold text-emerald-800 text-xs sm:text-sm">Rp {{ number_format($terkumpul * 0.4, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Penyaluran bahan pokok darurat untuk dapur umum warga di posko utama. Dilengkapi kuitansi resmi toko mitra dan berita acara penyerahan.
                            </p>
                            
                            <!-- Bukti Foto Nota Thumbnails -->
                            <div class="flex items-center gap-3 pt-2">
                                <button 
                                    @click="openReceipt('Kuitansi Pembelian Beras & Sembako Dapur Umum', 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=800&auto=format&fit=crop&q=80')" 
                                    type="button" 
                                    class="group relative w-20 h-20 rounded-xl overflow-hidden border border-slate-300 hover:border-emerald-600 transition-colors shrink-0"
                                >
                                    <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=200&auto=format&fit=crop&q=80" alt="Kuitansi Toko" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[10px] font-bold">
                                        Perbesar
                                    </div>
                                </button>
                                <button 
                                    @click="openReceipt('Foto Penyerahan Bantuan ke Posko Warga', 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=800&auto=format&fit=crop&q=80')" 
                                    type="button" 
                                    class="group relative w-20 h-20 rounded-xl overflow-hidden border border-slate-300 hover:border-emerald-600 transition-colors shrink-0"
                                >
                                    <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=200&auto=format&fit=crop&q=80" alt="Foto Serah Terima" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[10px] font-bold">
                                        Perbesar
                                    </div>
                                </button>
                                <div class="text-[11px] text-slate-500 pl-2">
                                    <p class="font-semibold text-slate-700">Verifikator Pemeriksa:</p>
                                    <p>Tim Inspektorat &amp; Dinsos Tulungagung</p>
                                    <span class="text-emerald-700 font-bold">&check; BAST Fisik Tersimpan di PPID</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 4: DONATUR -->
                <div x-show="activeTab === 'donatur'" x-cloak class="bg-white rounded-3xl border border-[#D9E2DE] p-6 sm:p-8 space-y-4 shadow-xs">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h2 class="font-heading font-bold text-lg sm:text-xl text-[#17211E]">Riwayat Donatur Terverifikasi</h2>
                        <span class="text-xs text-slate-500">{{ $jumlahDonatur ?? 0 }} Donatur Berpartisipasi</span>
                    </div>

                    @if ($kausa->donasi && $kausa->donasi->where('status', \App\Models\Donasi::STATUS_SUCCESS)->count() > 0)
                        <div class="divide-y divide-slate-100">
                            @foreach ($kausa->donasi->where('status', \App\Models\Donasi::STATUS_SUCCESS) as $d)
                                <div class="py-3 flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ $d->anonim ? 'HA' : strtoupper(substr($d->nama_donatur ?? ($d->user->name ?? 'D'), 0, 2)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-xs font-bold text-slate-800">
                                                {{ $d->anonim ? 'Hamba Allah (Anonim)' : ($d->nama_donatur ?? ($d->user->name ?? 'Donatur')) }}
                                            </p>
                                            <span class="font-bold text-xs text-[#087F5B]">
                                                Rp {{ number_format($d->nominal, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-slate-400">{{ $d->created_at ? $d->created_at->diffForHumans() : 'Baru saja' }}</p>
                                        @if ($d->doa_dukungan)
                                            <p class="text-xs text-slate-600 bg-slate-50 p-2.5 rounded-xl mt-1.5 italic">
                                                "{{ $d->doa_dukungan }}"
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Sample Donatur Stream for demonstration -->
                        <div class="divide-y divide-slate-100 text-xs">
                            <div class="py-3 flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center shrink-0">
                                    HA
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-center">
                                        <p class="font-bold text-slate-800">Hamba Allah (Anonim)</p>
                                        <span class="font-bold text-[#087F5B]">Rp 250.000</span>
                                    </div>
                                    <p class="text-[11px] text-slate-400">15 menit lalu &bull; via QRIS</p>
                                    <p class="text-slate-600 mt-1 italic">"Semoga lekas surut dan saudara kita di Besuki diberikan kesabaran."</p>
                                </div>
                            </div>

                            <div class="py-3 flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center shrink-0">
                                    BS
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-center">
                                        <p class="font-bold text-slate-800">Budi Santoso</p>
                                        <span class="font-bold text-[#087F5B]">Rp 100.000</span>
                                    </div>
                                    <p class="text-[11px] text-slate-400">1 jam lalu &bull; via Bank Jatim</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Right Sidebar: Floating Donation Card Widget -->
            <aside class="lg:col-span-4" id="donasi">
                <div class="sticky top-24 bg-white rounded-3xl border-2 border-emerald-600/30 p-6 sm:p-7 shadow-xl space-y-6">
                    
                    <!-- Target & Progress Header -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-baseline">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Dana Terkumpul</span>
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs">
                                {{ $persen }}% Tercapai
                            </span>
                        </div>

                        <div class="text-2xl sm:text-3xl font-extrabold text-[#087F5B] font-heading">
                            Rp {{ number_format($terkumpul, 0, ',', '.') }}
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                            <div class="bg-gradient-to-r from-[#087F5B] to-emerald-400 h-3 rounded-full transition-all duration-500" style="width: {{ $persen }}%"></div>
                        </div>

                        <div class="flex justify-between items-center text-xs text-[#73817C] pt-1">
                            <span>Target: <strong class="text-slate-800">Rp {{ number_format($kausa->target_dana, 0, ',', '.') }}</strong></span>
                            <span>Sisa <strong class="text-slate-800">{{ $sisaHari }}</strong> hari</span>
                        </div>
                    </div>

                    <!-- Quick Nominal Selector -->
                    <div class="space-y-2 pt-4 border-t border-slate-100">
                        <label class="block text-xs font-bold text-[#17211E] uppercase tracking-wider">Pilih Nominal Donasi</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button 
                                type="button" 
                                @click="selectedNominal = 25000; customNominal = ''" 
                                :class="selectedNominal === 25000 && !customNominal ? 'bg-[#087F5B] text-white border-[#087F5B]' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-emerald-50'"
                                class="py-2.5 text-xs font-bold rounded-xl border transition-all text-center"
                            >
                                Rp 25.000
                            </button>
                            <button 
                                type="button" 
                                @click="selectedNominal = 50000; customNominal = ''" 
                                :class="selectedNominal === 50000 && !customNominal ? 'bg-[#087F5B] text-white border-[#087F5B]' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-emerald-50'"
                                class="py-2.5 text-xs font-bold rounded-xl border transition-all text-center"
                            >
                                Rp 50.000
                            </button>
                            <button 
                                type="button" 
                                @click="selectedNominal = 100000; customNominal = ''" 
                                :class="selectedNominal === 100000 && !customNominal ? 'bg-[#087F5B] text-white border-[#087F5B]' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-emerald-50'"
                                class="py-2.5 text-xs font-bold rounded-xl border transition-all text-center"
                            >
                                Rp 100.000
                            </button>
                            <button 
                                type="button" 
                                @click="selectedNominal = 250000; customNominal = ''" 
                                :class="selectedNominal === 250000 && !customNominal ? 'bg-[#087F5B] text-white border-[#087F5B]' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-emerald-50'"
                                class="py-2.5 text-xs font-bold rounded-xl border transition-all text-center"
                            >
                                Rp 250.000
                            </button>
                            <button 
                                type="button" 
                                @click="selectedNominal = 500000; customNominal = ''" 
                                :class="selectedNominal === 500000 && !customNominal ? 'bg-[#087F5B] text-white border-[#087F5B]' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-emerald-50'"
                                class="py-2.5 text-xs font-bold rounded-xl border transition-all text-center"
                            >
                                Rp 500.000
                            </button>
                            <button 
                                type="button" 
                                @click="selectedNominal = 1000000; customNominal = ''" 
                                :class="selectedNominal === 1000000 && !customNominal ? 'bg-[#087F5B] text-white border-[#087F5B]' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-emerald-50'"
                                class="py-2.5 text-xs font-bold rounded-xl border transition-all text-center"
                            >
                                Rp 1 Juta
                            </button>
                        </div>

                        <!-- Custom Input -->
                        <div class="relative pt-1">
                            <span class="absolute left-3.5 top-3.5 text-xs font-bold text-slate-400">Rp</span>
                            <input 
                                type="number" 
                                x-model="customNominal" 
                                @input="if(customNominal) selectedNominal = parseInt(customNominal) || 0"
                                placeholder="Nominal lainnya (minimal Rp 10.000)" 
                                class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-200 focus:border-[#087F5B] focus:bg-white rounded-xl text-xs outline-none transition-all"
                            >
                        </div>
                    </div>

                    <!-- Donor Details & Message -->
                    <div class="space-y-3 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-[#17211E] mb-1">Nama Lengkap Donatur</label>
                            <input 
                                type="text" 
                                x-model="donorName" 
                                placeholder="Nama Anda" 
                                :disabled="isAnonim"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:border-[#087F5B] focus:bg-white rounded-xl text-xs outline-none transition-all disabled:opacity-50"
                            >
                        </div>

                        <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-700">
                            <input type="checkbox" x-model="isAnonim" class="rounded text-[#087F5B] focus:ring-[#087F5B]">
                            <span>Sembunyikan nama saya (Hamba Allah)</span>
                        </label>

                        <div>
                            <label class="block text-xs font-bold text-[#17211E] mb-1">Doa atau Dukungan (Opsional)</label>
                            <textarea 
                                x-model="doaDukungan"
                                rows="2" 
                                placeholder="Tuliskan doa atau pesan penyemangat bagi penerima bantuan..." 
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:border-[#087F5B] focus:bg-white rounded-xl text-xs outline-none transition-all"
                            ></textarea>
                        </div>
                    </div>

                    <!-- Payment Simulation Option -->
                    <div class="space-y-2 pt-2">
                        <label class="block text-xs font-bold text-[#17211E] uppercase tracking-wider">Metode Pembayaran</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button 
                                type="button" 
                                @click="paymentMethod = 'qris'" 
                                :class="paymentMethod === 'qris' ? 'border-[#087F5B] bg-emerald-50 text-[#087F5B]' : 'border-slate-200 text-slate-700'"
                                class="py-2 px-3 border rounded-xl font-bold text-xs flex items-center justify-center gap-1.5 transition-all"
                            >
                                <i data-lucide="qr-code" class="w-4 h-4"></i> QRIS Instan
                            </button>
                            <button 
                                type="button" 
                                @click="paymentMethod = 'bank'" 
                                :class="paymentMethod === 'bank' ? 'border-[#087F5B] bg-emerald-50 text-[#087F5B]' : 'border-slate-200 text-slate-700'"
                                class="py-2 px-3 border rounded-xl font-bold text-xs flex items-center justify-center gap-1.5 transition-all"
                            >
                                <i data-lucide="landmark" class="w-4 h-4"></i> Virtual Account
                            </button>
                        </div>
                    </div>

                    <!-- CTA Salurkan Donasi -->
                    <button 
                        @click="checkoutSimulate()"
                        type="button" 
                        class="w-full py-3.5 px-4 rounded-xl bg-[#087F5B] hover:bg-[#066A4C] text-white font-extrabold text-sm tracking-wide shadow-lg hover:shadow-xl transition-all active:scale-95 flex items-center justify-center gap-2"
                    >
                        <i data-lucide="heart" class="w-4 h-4 fill-white"></i>
                        <span>Salurkan Donasi Sekarang</span>
                    </button>

                    <!-- Trust Badge -->
                    <div class="pt-2 text-center space-y-1">
                        <p class="text-[11px] text-slate-500 flex items-center justify-center gap-1">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-600"></i> Rekening Resmi Pengawasan Pemkab Tulungagung
                        </p>
                        <p class="text-[10px] text-slate-400">100% Bebas Potongan Komisi Liar &bull; Diaudit PPID</p>
                    </div>

                </div>
            </aside>

        </div>
    </div>

    <!-- ================= MODAL LIGHTBOX FOTO NOTA ================= -->
    <div 
        x-show="previewReceiptOpen" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm"
        @keydown.escape.window="previewReceiptOpen = false"
    >
        <div 
            @click.outside="previewReceiptOpen = false"
            class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl relative"
        >
            <div class="flex items-center justify-between pb-3 border-b border-slate-200 mb-4">
                <h3 class="font-heading font-bold text-sm sm:text-base text-slate-900" x-text="previewReceiptTitle"></h3>
                <button @click="previewReceiptOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-700">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="rounded-2xl overflow-hidden bg-slate-100 max-h-[70vh] flex items-center justify-center">
                <img :src="previewReceiptSrc" :alt="previewReceiptTitle" class="w-full h-auto object-contain">
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Bukti fisik diverifikasi resmi oleh Dinas Sosial Kab. Tulungagung</span>
                <button @click="previewReceiptOpen = false" class="px-4 py-1.5 bg-[#087F5B] text-white font-bold rounded-lg text-xs">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- ================= MODAL SIMULASI PEMBAYARAN SUKSES ================= -->
    <div 
        x-show="donationSuccessModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm"
        @keydown.escape.window="donationSuccessModal = false"
    >
        <div 
            @click.outside="donationSuccessModal = false"
            class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl text-center space-y-4 relative"
        >
            <button @click="donationSuccessModal = false" class="absolute top-4 right-4 p-1 rounded-lg text-slate-400 hover:text-slate-700">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center mx-auto shadow-inner">
                <i data-lucide="check" class="w-8 h-8 text-emerald-600 stroke-[3]"></i>
            </div>

            <h3 class="font-heading font-extrabold text-xl text-slate-900">Simulasi Donasi Berhasil Dibuat</h3>
            
            <p class="text-xs text-slate-600">
                Terima kasih atas kepedulian Anda untuk <strong class="text-slate-800">{{ $kausa->judul }}</strong>.
            </p>

            <div class="p-4 rounded-2xl bg-[#F6F8F7] border border-[#D9E2DE] text-left text-xs space-y-2">
                <div class="flex justify-between">
                    <span class="text-slate-500">Nominal Donasi:</span>
                    <span class="font-bold text-[#087F5B] text-sm">Rp <span x-text="selectedNominal.toLocaleString('id-ID')"></span></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Atas Nama:</span>
                    <span class="font-semibold text-slate-800" x-text="isAnonim ? 'Hamba Allah (Anonim)' : (donorName || 'Donatur Peduli')"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Metode Penyaluran:</span>
                    <span class="font-semibold text-slate-800 uppercase" x-text="paymentMethod"></span>
                </div>
                <div class="pt-2 border-t border-slate-200 text-[11px] text-emerald-800 flex items-center gap-1 font-semibold">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-600"></i> Gateway Pembayaran Resmi Simulasi Aktif
                </div>
            </div>

            <div class="pt-2">
                <button 
                    @click="donationSuccessModal = false" 
                    type="button" 
                    class="w-full py-3 bg-[#087F5B] hover:bg-[#066A4C] text-white font-bold text-xs rounded-xl shadow-md transition-all"
                >
                    Selesai &amp; Kembali ke Halaman Kausa
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
