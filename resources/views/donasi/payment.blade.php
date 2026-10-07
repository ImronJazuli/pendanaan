@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F6F8F7] py-8 sm:py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">

        <!-- Header Tag -->
        <div class="text-center space-y-2 mb-8">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-300">
                <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600"></i>
                Kas Daerah Resmi Pemkab Tulungagung
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-[#123B32]">
                Selesaikan Penyaluran Donasi
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 max-w-md mx-auto">
                Silakan lakukan transfer atau scan QRIS di bawah ini untuk menuntaskan donasi sosial Anda.
            </p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-3xl border border-[#D9E2DE] p-6 sm:p-8 shadow-sm space-y-6">

            <!-- Ringkasan Tagihan -->
            <div class="bg-[#F6F8F7] rounded-2xl p-4 sm:p-5 border border-[#D9E2DE] space-y-3">
                <div class="flex items-center justify-between text-xs text-slate-500 pb-2 border-b border-slate-200">
                    <span>Kode Referensi Transaksi:</span>
                    <span class="font-mono font-bold text-slate-800 text-sm">{{ $donasi->pesanan_pembayaran }}</span>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Tujuan Program Kausa:</span>
                    <h3 class="font-bold text-slate-900 text-sm mt-0.5 leading-snug">{{ $donasi->kausa->judul }}</h3>
                    <p class="text-xs text-emerald-800 font-semibold mt-0.5">Oleh: {{ $donasi->kausa->instansi->nama ?? 'Instansi Terverifikasi' }}</p>
                </div>
                <div class="flex items-baseline justify-between pt-2 border-t border-slate-200">
                    <span class="text-xs font-semibold text-slate-600">Total Donasi:</span>
                    <span class="font-extrabold text-2xl text-[#087F5B] font-heading">
                        Rp {{ number_format($donasi->nominal, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Bagian Pembayaran QRIS / VA -->
            <div class="space-y-4 text-center">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 inline-block mx-auto shadow-inner">
                    <!-- QRIS Image / Mock Canvas -->
                    <div class="w-56 h-56 bg-white p-3 rounded-xl border border-slate-200 mx-auto flex flex-col items-center justify-between shadow-2xs">
                        <div class="flex items-center justify-between w-full text-[10px] font-bold text-slate-400 border-b border-slate-100 pb-1">
                            <span>QRIS STANDAR BI</span>
                            <span class="text-emerald-700">PEMKAB TA</span>
                        </div>
                        <!-- SVG QR Code Dummy -->
                        <div class="p-1">
                            <svg class="w-36 h-36 mx-auto text-slate-900" viewBox="0 0 100 100" fill="currentColor">
                                <path d="M10,10 h30 v30 h-30 z M15,15 v20 h20 v-20 z M20,20 h10 v10 h-10 z" />
                                <path d="M60,10 h30 v30 h-30 z M65,15 v20 h20 v-20 z M70,20 h10 v10 h-10 z" />
                                <path d="M10,60 h30 v30 h-30 z M15,65 v20 h20 v-20 z M20,70 h10 v10 h-10 z" />
                                <rect x="45" y="15" width="8" height="8" />
                                <rect x="45" y="30" width="8" height="8" />
                                <rect x="15" y="45" width="8" height="8" />
                                <rect x="30" y="45" width="8" height="8" />
                                <rect x="60" y="45" width="10" height="10" />
                                <rect x="75" y="45" width="10" height="10" />
                                <rect x="45" y="60" width="12" height="12" />
                                <rect x="60" y="60" width="10" height="10" />
                                <rect x="75" y="60" width="15" height="8" />
                                <rect x="45" y="78" width="15" height="10" />
                                <rect x="65" y="75" width="8" height="15" />
                                <rect x="78" y="75" width="12" height="15" />
                            </svg>
                        </div>
                        <span class="text-[9px] font-mono text-slate-500 tracking-wider">NMID: ID1026042918204</span>
                    </div>
                </div>

                <div class="space-y-1 text-xs text-slate-500">
                    <p class="font-bold text-slate-700">Scan menggunakan GoPay, OVO, Dana, BCA, atau Mobile Banking apa saja</p>
                    <p class="text-[11px]">Batas Waktu Pembayaran: <strong class="text-amber-800">24 Jam</strong></p>
                </div>
            </div>

            <!-- Rekening Alternatif Kasda -->
            <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4 text-xs space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-700 flex items-center gap-1.5">
                        <i data-lucide="landmark" class="w-4 h-4 text-[#087F5B]"></i>
                        Rekening Penampungan Donasi Kasda (Bank Jatim)
                    </span>
                    <span class="font-mono text-slate-500 font-bold">0123-456-7890</span>
                </div>
                <p class="text-[11px] text-slate-500">
                    Atas Nama: <strong>PEMKAB TULUNGAGUNG - REKENING PENAMPUNGAN BANTUAN SOSIAL</strong>
                </p>
            </div>

            <!-- Simulation Gateway Action Button -->
            <div class="pt-2 border-t border-slate-100 space-y-3">
                <form method="POST" action="{{ route('donasi.simulate', $donasi->pesanan_pembayaran) }}">
                    @csrf
                    <button type="submit" class="w-full py-4 px-6 rounded-2xl bg-[#087F5B] hover:bg-[#066A4C] text-white font-extrabold text-sm shadow-md hover:shadow-lg transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        <span>Simulasikan Pembayaran Berhasil</span>
                    </button>
                </form>

                <div class="text-center">
                    <a href="{{ route('kausa.show', $donasi->kausa->slug) }}" class="text-xs text-slate-400 hover:text-slate-600 transition-colors">
                        Batalkan dan kembali ke halaman kausa
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
