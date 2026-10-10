@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F6F8F7] py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Breadcrumb & Header -->
        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-6 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-[#E6F4EF] text-[#087F5B] border border-[#087F5B]/20">
                            Pemberitahuan
                        </span>
                        <span class="text-xs text-[#73817C]">&bull;</span>
                        <span class="text-xs text-[#73817C]">{{ $unreadCount }} Belum Dibaca</span>
                    </div>
                    <h1 class="font-heading font-extrabold text-2xl text-[#17211E] tracking-tight">Kotak Masuk Notifikasi</h1>
                    <p class="text-xs sm:text-sm text-[#52615C] mt-1">Pantau perkembangan status kausa, verifikasi legalitas, LPJ, dan bukti donasi Anda.</p>
                </div>

                @if($unreadCount > 0)
                    <form method="POST" action="{{ route('notifikasi.readAll') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-[#D9E2DE] bg-[#F6F8F7] hover:bg-emerald-50 hover:border-[#087F5B] hover:text-[#087F5B] text-xs font-semibold text-[#52615C] transition-all shadow-2xs">
                            <i data-lucide="check-check" class="w-4 h-4 text-[#087F5B]"></i>
                            <span>Tandai Semua Dibaca</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Notification List -->
        <div class="space-y-3">
            @forelse($notifikasis as $item)
                @php
                    $isUnread = is_null($item->dibaca_pada);
                    $iconName = match ($item->jenis) {
                        'kausa_disetujui', 'legalitas_disetujui', 'lpj_disetujui', 'donasi_berhasil' => 'check-circle',
                        'kausa_ditolak', 'legalitas_ditolak', 'lpj_ditolak', 'donasi_ditolak' => 'alert-circle',
                        'kausa_perlu_diperbaiki', 'lpj_perlu_revisi' => 'alert-triangle',
                        'donasi_masuk' => 'wallet',
                        default => 'bell',
                    };
                    $iconBg = match ($item->jenis) {
                        'kausa_disetujui', 'legalitas_disetujui', 'lpj_disetujui', 'donasi_berhasil', 'donasi_masuk' => 'bg-emerald-100 text-emerald-800',
                        'kausa_ditolak', 'legalitas_ditolak', 'lpj_ditolak', 'donasi_ditolak' => 'bg-rose-100 text-rose-800',
                        'kausa_perlu_diperbaiki', 'lpj_perlu_revisi' => 'bg-amber-100 text-amber-800',
                        default => 'bg-[#E6F4EF] text-[#087F5B]',
                    };
                @endphp

                <div class="rounded-2xl border transition-all p-5 shadow-xs flex items-start gap-4 {{ $isUnread ? 'bg-white border-l-4 border-l-[#087F5B] border-[#D9E2DE]' : 'bg-white/80 border-[#E5EBE8] text-[#52615C]' }}">
                    <div class="w-10 h-10 rounded-xl {{ $iconBg }} flex items-center justify-center shrink-0 mt-0.5">
                        <i data-lucide="{{ $iconName }}" class="w-5 h-5"></i>
                    </div>

                    <div class="flex-1 min-w-0 space-y-1.5">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <h3 class="font-heading font-bold text-sm {{ $isUnread ? 'text-[#17211E]' : 'text-slate-700' }}">
                                    {{ $item->judul }}
                                </h3>
                                @if($isUnread)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-[#087F5B] border border-emerald-200">
                                        Baru
                                    </span>
                                @endif
                            </div>
                            <span class="text-[11px] text-[#73817C] tabular-nums">
                                {{ $item->created_at ? $item->created_at->diffForHumans() : '' }}
                            </span>
                        </div>

                        <p class="text-xs {{ $isUnread ? 'text-[#52615C]' : 'text-slate-500' }} leading-relaxed">
                            {{ $item->isi }}
                        </p>

                        <div class="pt-2 flex flex-wrap items-center gap-2">
                            @if($item->tautan)
                                <a href="{{ $item->tautan }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#087F5B] hover:text-[#066A4C] hover:underline">
                                    <span>Lihat Rincian</span>
                                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
                                </a>
                            @endif

                            @if($isUnread)
                                <form method="POST" action="{{ route('notifikasi.read', $item) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 text-[11px] font-medium text-slate-500 hover:text-slate-800 ml-2">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                        <span>Tandai sudah dibaca</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-[#D9E2DE] p-12 text-center shadow-xs">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-[#E6F4EF] text-[#087F5B] flex items-center justify-center mb-4">
                        <i data-lucide="bell-off" class="w-7 h-7"></i>
                    </div>
                    <h3 class="font-heading font-bold text-base text-[#17211E]">Belum Ada Notifikasi</h3>
                    <p class="text-xs text-[#73817C] max-w-sm mx-auto mt-1">
                        Saat ini tidak ada pemberitahuan baru. Semua aktivitas verifikasi kausa, donasi, dan laporan akan muncul di sini.
                    </p>
                </div>
            @endforelse
        </div>

        @if($notifikasis->hasPages())
            <div class="pt-4">
                {{ $notifikasis->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
