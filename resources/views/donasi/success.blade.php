@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F6F8F7] py-10 sm:py-16">
    <div class="max-w-xl mx-auto px-4 sm:px-6">

        <div class="bg-white rounded-3xl border border-[#D9E2DE] p-6 sm:p-8 text-center space-y-6 shadow-sm">

            <!-- Check Icon -->
            <div class="w-20 h-20 rounded-full bg-emerald-100 text-[#087F5B] flex items-center justify-center mx-auto shadow-inner">
                <i data-lucide="check" class="w-10 h-10 text-emerald-600 stroke-[3]"></i>
            </div>

            <!-- Title -->
            <div class="space-y-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-600"></i>
                    Transaksi Berhasil &bull; Tervalidasi Kasda
                </span>
                <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-[#123B32]">
                    Donasi Berhasil Diterima!
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 max-w-sm mx-auto leading-relaxed">
                    Terima kasih atas kebaikan Anda. Dana telah dialokasikan ke rekening resmi penampungan Pemkab Tulungagung.
                </p>
            </div>

            <!-- Detail Transaksi Box -->
            <div class="bg-[#F6F8F7] rounded-2xl p-5 border border-[#D9E2DE] text-left space-y-3 text-xs">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                    <span class="text-slate-500">Nomor Tanda Terima:</span>
                    <span class="font-mono font-bold text-slate-800">{{ $donasi->pesanan_pembayaran }}</span>
                </div>
                <div>
                    <span class="text-[11px] text-slate-400 block uppercase font-bold tracking-wider">Tujuan Program:</span>
                    <p class="font-bold text-slate-900 text-sm mt-0.5 leading-snug">{{ $donasi->kausa->judul }}</p>
                    <p class="text-[11px] text-emerald-800 font-semibold mt-0.5">Pengelola: {{ $donasi->kausa->instansi->nama ?? 'Instansi' }}</p>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-slate-200">
                    <span class="text-slate-500">Nama Donatur:</span>
                    <span class="font-bold text-slate-800">{{ $donasi->anonim ? 'Hamba Allah (Anonim)' : ($donasi->nama_donatur ?? 'Donatur') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500">Waktu Pembayaran:</span>
                    <span class="text-slate-700 font-medium">{{ $donasi->dibayar_pada ? $donasi->dibayar_pada->translatedFormat('d F Y, H:i') : now()->translatedFormat('d F Y, H:i') }} WIB</span>
                </div>
                <div class="flex items-baseline justify-between pt-2 border-t border-slate-200">
                    <span class="font-bold text-slate-700">Nominal Donasi:</span>
                    <span class="font-extrabold text-xl text-[#087F5B] font-heading">
                        Rp {{ number_format($donasi->nominal, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-3 pt-2">
                <a href="{{ route('donasi.receipt', $donasi->pesanan_pembayaran) }}" target="_blank" class="w-full py-3.5 px-4 rounded-2xl bg-[#087F5B] hover:bg-[#066A4C] text-white font-extrabold text-xs shadow-md transition-all flex items-center justify-center gap-2">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    <span>Buka / Cetak Kuitansi Digital Resmi</span>
                </a>

                <div class="flex items-center gap-3">
                    <a href="{{ route('kausa.show', $donasi->kausa->slug) }}" class="flex-1 py-3 px-4 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold text-xs transition-colors">
                        Kembali ke Kausa
                    </a>
                    @auth
                        <a href="{{ route('donatur.dashboard') }}" class="flex-1 py-3 px-4 rounded-xl bg-emerald-50 text-emerald-800 hover:bg-emerald-100 font-bold text-xs transition-colors">
                            Dashboard Donatur
                        </a>
                    @else
                        <a href="{{ route('landing') }}" class="flex-1 py-3 px-4 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 font-bold text-xs transition-colors">
                            Beranda Utama
                        </a>
                    @endauth
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
