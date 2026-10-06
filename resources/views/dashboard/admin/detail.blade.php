@extends('layouts.dashboard-admin')

@section('content')
<div class="min-h-screen bg-[#F6F8F7] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Breadcrumb & Back button -->
        <div class="flex items-center justify-between">
            <a href="{{ route('dashboard.admin') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white border border-[#D9E2DE] hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition-all">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali ke Antrean Verifikator</span>
            </a>

            <span class="text-xs text-slate-400 font-mono">
                ID: KSA-TA-{{ $kausa->created_at ? $kausa->created_at->format('Y') : date('Y') }}-{{ str_pad($kausa->id, 3, '0', STR_PAD_LEFT) }}
            </span>
        </div>

        <!-- Header Title Banner -->
        <div class="bg-white rounded-3xl border border-[#D9E2DE] p-6 sm:p-8 shadow-xs">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-[#E6F4EF] text-[#087F5B]">
                            {{ $kausa->kategori->nama ?? 'Sosial' }}
                        </span>
                        <span class="text-xs text-slate-400">&bull;</span>
                        <span class="text-xs text-slate-600 flex items-center gap-1">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-emerald-700"></i> {{ $kausa->lokasi ?? 'Kab. Tulungagung' }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-[#17211E] font-heading">{{ $kausa->judul }}</h1>
                    <p class="text-xs text-slate-500">
                        Diajukan oleh: <strong class="text-slate-800">{{ $kausa->instansi->nama ?? 'Instansi Pengaju' }}</strong> (PJ: {{ $kausa->instansi->nama_pj ?? '-' }}) &bull; Diajukan pada {{ $kausa->created_at ? $kausa->created_at->format('d F Y H:i') : '-' }}
                    </p>
                </div>

                <div>
                    @if ($kausa->status === 'disetujui')
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i> Disetujui &amp; Tayang Publik
                        </span>
                    @elseif ($kausa->status === 'menunggu_verifikasi')
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                            <i data-lucide="timer" class="w-4 h-4 text-amber-700"></i> Menunggu Keputusan Admin
                        </span>
                    @elseif ($kausa->status === 'ditolak')
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-red-100 text-red-900 border border-red-300">
                            <i data-lucide="x-circle" class="w-4 h-4 text-red-700"></i> Ditolak
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 border border-slate-300">
                            <i data-lucide="edit-3" class="w-4 h-4 text-slate-500"></i> Status: {{ ucfirst(str_replace('_', ' ', $kausa->status)) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left: Kausa Details & Files -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Detail Info Card -->
                <div class="bg-white rounded-3xl border border-[#D9E2DE] p-6 sm:p-8 space-y-6 shadow-xs text-xs sm:text-sm">
                    <div>
                        <h2 class="font-heading font-bold text-base text-[#17211E] mb-2">Ringkasan Program</h2>
                        <p class="text-slate-700 bg-[#F6F8F7] p-4 rounded-2xl border border-[#D9E2DE] leading-relaxed">
                            {{ $kausa->ringkasan }}
                        </p>
                    </div>

                    <div>
                        <h2 class="font-heading font-bold text-base text-[#17211E] mb-2">Kronologi &amp; Rincian Kebutuhan Lapangan</h2>
                        <div class="whitespace-pre-line text-[#52615C] leading-relaxed">
                            {{ $kausa->deskripsi }}
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div>
                            <span class="text-slate-400 block text-xs">Target Dana</span>
                            <p class="font-extrabold text-base text-[#087F5B]">Rp {{ number_format($kausa->target_dana, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-xs">Batas Waktu</span>
                            <p class="font-bold text-slate-800">{{ $kausa->tanggal_berakhir ? $kausa->tanggal_berakhir->format('d M Y') : 'Tidak ditentukan' }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-xs">Wilayah Pelaksanaan</span>
                            <p class="font-bold text-slate-800">{{ $kausa->lokasi ?? 'Kab. Tulungagung' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Dokumen Pendukung & RAB -->
                <div class="bg-white rounded-3xl border border-[#D9E2DE] p-6 sm:p-8 shadow-xs space-y-4">
                    <h2 class="font-heading font-bold text-base text-[#17211E]">Dokumen Berkas &amp; RAB Fisik</h2>
                    @if ($kausa->dokumen && $kausa->dokumen->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($kausa->dokumen as $doc)
                                <div class="flex items-center justify-between p-3.5 rounded-xl bg-[#F6F8F7] border border-[#D9E2DE] text-xs">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <i data-lucide="file-text" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-800 truncate">{{ $doc->nama_file }}</p>
                                            <p class="text-[10px] text-slate-400">{{ number_format($doc->ukuran_file / 1024, 1) }} KB</p>
                                        </div>
                                    </div>
                                    <a href="{{ Storage::url($doc->path_file) }}" target="_blank" class="px-3 py-1 bg-white border border-slate-200 hover:border-emerald-600 text-emerald-700 font-bold rounded-lg transition-colors">
                                        Unduh
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-slate-400 italic">Tidak ada dokumen lampiran file khusus.</p>
                    @endif
                </div>

                <!-- Riwayat Audit Status -->
                <div class="bg-white rounded-3xl border border-[#D9E2DE] p-6 sm:p-8 shadow-xs space-y-4">
                    <h2 class="font-heading font-bold text-base text-[#17211E]">Riwayat Jejak Audit Verifikasi</h2>
                    <div class="space-y-4 pt-2">
                        @forelse ($kausa->riwayatStatus->reverse() as $riwayat)
                            <div class="flex items-start gap-3 text-xs">
                                <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-700"></i>
                                </div>
                                <div class="flex-1 bg-[#F6F8F7] p-3.5 rounded-2xl border border-[#D9E2DE]">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-slate-900 uppercase tracking-wider text-[11px]">
                                            Status Diubah: {{ str_replace('_', ' ', $riwayat->status_baru) }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">{{ $riwayat->created_at ? $riwayat->created_at->format('d M Y H:i') : '-' }}</span>
                                    </div>
                                    @if ($riwayat->catatan)
                                        <p class="text-slate-600 mt-1.5 italic bg-white p-2 rounded-xl border border-slate-200">
                                            "{{ $riwayat->catatan }}"
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400">Belum ada catatan riwayat status.</p>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Right: Decision Action Panel -->
            <aside class="lg:col-span-4 space-y-6">
                
                @if ($kausa->status !== 'disetujui' && $kausa->status !== 'ditolak')
                    <!-- Card Setujui -->
                    <div class="bg-white rounded-3xl border border-emerald-300 p-6 shadow-md space-y-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center shrink-0">
                                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-700"></i>
                            </div>
                            <div>
                                <h3 class="font-heading font-bold text-sm text-slate-900">Setujui &amp; Tayangkan Kausa</h3>
                                <p class="text-[11px] text-slate-500">Program akan langsung tayang di katalog publik.</p>
                            </div>
                        </div>

                        <form action="{{ route('dashboard.admin.verify', $kausa->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Verifikator (Opsional)</label>
                                <textarea name="catatan" rows="2" placeholder="Catatan persetujuan resmi..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:border-emerald-600 rounded-xl text-xs outline-none"></textarea>
                            </div>
                            <button type="submit" class="w-full py-2.5 bg-[#087F5B] hover:bg-[#066A4C] text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center justify-center gap-1.5 active:scale-95">
                                <i data-lucide="check" class="w-4 h-4"></i>
                                <span>Setujui Program</span>
                            </button>
                        </form>
                    </div>

                    <!-- Card Minta Revisi -->
                    <div class="bg-white rounded-3xl border border-amber-300 p-6 shadow-xs space-y-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
                                <i data-lucide="rotate-ccw" class="w-5 h-5 text-amber-700"></i>
                            </div>
                            <div>
                                <h3 class="font-heading font-bold text-sm text-slate-900">Minta Perbaikan / Revisi</h3>
                                <p class="text-[11px] text-slate-500">Instansi dapat memperbaiki dan mengajukan ulang kausa.</p>
                            </div>
                        </div>

                        <form action="{{ route('dashboard.admin.revise', $kausa->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin meminta perbaikan pada kausa ini?');" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Revisi <span class="text-amber-600">*</span></label>
                                <textarea name="catatan_revisi" required minlength="10" rows="3" placeholder="Sebutkan bagian atau dokumen yang perlu diperbaiki (min. 10 karakter)..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:border-amber-500 rounded-xl text-xs outline-none"></textarea>
                            </div>
                            <button type="submit" class="w-full py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center justify-center gap-1.5 active:scale-95">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                                <span>Kirim Permintaan Revisi</span>
                            </button>
                        </form>
                    </div>

                    <!-- Card Tolak Pengajuan -->
                    <div class="bg-white rounded-3xl border border-red-200 p-6 shadow-xs space-y-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-red-100 text-red-800 flex items-center justify-center shrink-0">
                                <i data-lucide="x-circle" class="w-5 h-5 text-red-700"></i>
                            </div>
                            <div>
                                <h3 class="font-heading font-bold text-sm text-slate-900">Tolak Pengajuan</h3>
                                <p class="text-[11px] text-slate-500">Wajib sertakan alasan penolakan yang jelas.</p>
                            </div>
                        </div>

                        <form action="{{ route('dashboard.admin.reject', $kausa->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak pengajuan kausa ini?');" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                                <textarea name="alasan_penolakan" required minlength="10" rows="3" placeholder="Sebutkan syarat yang belum terpenuhi (min. 10 karakter)..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:border-red-600 rounded-xl text-xs outline-none"></textarea>
                            </div>
                            <button type="submit" class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center justify-center gap-1.5 active:scale-95">
                                <i data-lucide="x" class="w-4 h-4"></i>
                                <span>Tolak Pengajuan</span>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="bg-white rounded-3xl border border-slate-200 p-6 text-center space-y-2">
                        <i data-lucide="lock" class="w-8 h-8 text-slate-400 mx-auto"></i>
                        <h3 class="font-bold text-slate-800 text-sm">Status Kausa Terkunci</h3>
                        <p class="text-xs text-slate-500">Keputusan verifikasi telah diambil ({{ ucfirst($kausa->status) }}).</p>
                        @if ($kausa->status === 'disetujui')
                            <a href="{{ route('kausa.show', $kausa->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#087F5B] hover:underline mt-2">
                                <span>Buka Halaman Publik</span>
                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                            </a>
                        @endif
                    </div>
                @endif

            </aside>

        </div>
    </div>
</div>
@endsection
