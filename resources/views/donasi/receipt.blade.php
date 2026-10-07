<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuitansi Donasi - {{ $donasi->pesanan_pembayaran }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F6F8F7; color: #17211E; }
        @media print {
            .no-print { display: none !important; }
            body { background-color: #fff !important; }
            .print-shadow-none { box-shadow: none !important; border-color: #ccc !important; }
        }
    </style>
</head>
<body class="py-6 sm:py-12">

    <div class="max-w-2xl mx-auto px-4">
        
        <!-- Action Toolbar -->
        <div class="no-print mb-6 flex items-center justify-between">
            <a href="{{ route('donasi.success', $donasi->pesanan_pembayaran) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
            </a>
            <button type="button" onclick="window.print()" class="px-4 py-2 bg-[#087F5B] hover:bg-[#066A4C] text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center gap-1.5">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak / Simpan PDF
            </button>
        </div>

        <!-- Receipt Card Container -->
        <div class="bg-white rounded-3xl border-2 border-[#D9E2DE] p-6 sm:p-10 shadow-lg print-shadow-none relative overflow-hidden">
            
            <!-- Watermark Cap Pemkab -->
            <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full border-8 border-emerald-500/10 flex items-center justify-center pointer-events-none">
                <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-900/10 rotate-[-30deg] text-center">
                    LUNAS - KAS DAERAH<br>PEMKAB TULUNGAGUNG
                </span>
            </div>

            <!-- Kop Bukti Donasi Resmi -->
            <div class="flex items-start justify-between pb-6 border-b-2 border-slate-900 gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-[#087F5B] text-white flex items-center justify-center font-bold text-xl shrink-0">
                        <i data-lucide="shield-check" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <h2 class="text-xs font-extrabold text-slate-500 uppercase tracking-widest leading-none">Pemerintah Kabupaten Tulungagung</h2>
                        <h1 class="text-lg sm:text-xl font-extrabold font-heading text-[#123B32] mt-1">SI-PEDULI &bull; Kuitansi Donasi Digital</h1>
                        <p class="text-[11px] text-slate-400">Portal Pendanaan Sosial Resmi PPID Pemkab Tulungagung</p>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <span class="inline-block px-2.5 py-1 rounded bg-emerald-100 text-emerald-800 text-[10px] font-mono font-bold">
                        BUKTI SAH
                    </span>
                    <p class="text-[11px] font-mono text-slate-500 mt-1">No: {{ $donasi->pesanan_pembayaran }}</p>
                </div>
            </div>

            <!-- Isi Kuitansi Formal -->
            <div class="py-6 space-y-4 text-xs sm:text-sm leading-relaxed">
                <div class="grid grid-cols-12 gap-2 pb-2 border-b border-slate-100">
                    <span class="col-span-4 text-slate-500">Telah Diterima Dari</span>
                    <span class="col-span-8 font-bold text-slate-900">
                        : {{ $donasi->anonim ? 'Hamba Allah (Anonim)' : ($donasi->nama_donatur ?? 'Masyarakat Donatur') }}
                    </span>
                </div>

                <div class="grid grid-cols-12 gap-2 pb-2 border-b border-slate-100">
                    <span class="col-span-4 text-slate-500">Uang Sejumlah</span>
                    <span class="col-span-8 font-extrabold text-[#087F5B] text-base font-heading">
                        : Rp {{ number_format($donasi->nominal, 0, ',', '.') }}
                    </span>
                </div>

                <div class="grid grid-cols-12 gap-2 pb-2 border-b border-slate-100">
                    <span class="col-span-4 text-slate-500">Untuk Penyaluran</span>
                    <div class="col-span-8 space-y-1">
                        <p class="font-bold text-slate-900">: {{ $donasi->kausa->judul }}</p>
                        <p class="text-[11px] text-slate-500 pl-2">Pengelola Resmi: {{ $donasi->kausa->instansi->nama ?? 'Instansi' }}</p>
                        <p class="text-[11px] text-slate-500 pl-2">Lokasi: {{ $donasi->kausa->lokasi ?? 'Kab. Tulungagung' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-2 pb-2 border-b border-slate-100">
                    <span class="col-span-4 text-slate-500">Waktu Pembayaran</span>
                    <span class="col-span-8 text-slate-700">
                        : {{ $donasi->dibayar_pada ? $donasi->dibayar_pada->translatedFormat('d F Y, H:i') : now()->translatedFormat('d F Y, H:i') }} WIB
                    </span>
                </div>

                <div class="grid grid-cols-12 gap-2">
                    <span class="col-span-4 text-slate-500">Kanal Penyaluran</span>
                    <span class="col-span-8 font-mono text-slate-700 uppercase">
                        : {{ $donasi->metode_pembayaran ?? 'QRIS Kasda Pemkab' }} (Simulasi Online)
                    </span>
                </div>
            </div>

            <!-- Tanda Tangan & Cap Digital -->
            <div class="pt-6 border-t-2 border-slate-200 flex items-end justify-between text-xs">
                <div class="space-y-1 max-w-xs">
                    <div class="w-14 h-14 bg-slate-100 border border-slate-300 rounded-lg flex items-center justify-center p-1">
                        <!-- Mini QR Code verification -->
                        <svg class="w-full h-full text-slate-800" viewBox="0 0 24 24" fill="currentColor">
                            <rect x="2" y="2" width="8" height="8" rx="1"/>
                            <rect x="14" y="2" width="8" height="8" rx="1"/>
                            <rect x="2" y="14" width="8" height="8" rx="1"/>
                            <circle cx="18" cy="18" r="3"/>
                        </svg>
                    </div>
                    <p class="text-[10px] text-slate-400">Verifikasi elektronik sah melalui Database Supabase Kasda Pemkab Tulungagung.</p>
                </div>

                <div class="text-center space-y-1">
                    <p class="text-[11px] text-slate-500">Tulungagung, {{ now()->translatedFormat('d F Y') }}</p>
                    <p class="text-xs font-bold text-slate-800">PPID Pemkab Tulungagung</p>
                    <div class="h-12 flex items-center justify-center">
                        <span class="px-2 py-0.5 rounded border border-emerald-600/40 bg-emerald-50 text-[10px] text-emerald-800 font-extrabold tracking-wider">
                            TERVALIDASI SISTEM
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-600 font-semibold underline">Dinas Sosial &amp; BPKAD Kab. Tulungagung</p>
                </div>
            </div>

        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
