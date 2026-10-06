@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background">
    <!-- Header -->
    <div class="bg-surface border-b border-border">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
            <div class="max-w-3xl">
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-primary-subtle text-primary border border-primary/20">
                        Bantuan & Panduan
                    </span>
                    <span class="text-xs text-text-muted">&bull;</span>
                    <span class="text-xs text-text-muted">SI-PEDULI Pemkab Tulungagung</span>
                </div>
                <h1 class="font-display font-bold text-3xl sm:text-4xl text-text-primary tracking-tight">
                    Pertanyaan yang Sering Diajukan (FAQ)
                </h1>
                <p class="text-sm sm:text-base text-text-secondary mt-2">
                    Temukan jawaban lengkap seputar cara berdonasi, pengajuan program sosial, keamanan dana, dan transparansi laporan SPJ.
                </p>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-10 space-y-6">
        
        <!-- Accordion Item 1 -->
        <details class="bg-surface border border-border rounded-2xl p-5 sm:p-6 shadow-card group transition-all open:ring-2 open:ring-primary/20">
            <summary class="font-display font-bold text-base sm:text-lg text-text-primary flex items-center justify-between cursor-pointer list-none">
                <span class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-primary-subtle text-primary flex items-center justify-center text-xs font-bold shrink-0">1</span>
                    Bagaimana cara berdonasi di SI-PEDULI Tulungagung?
                </span>
                <span class="p-1 rounded-lg text-text-muted group-hover:text-primary transition-transform group-open:rotate-180">
                    <i data-lucide="chevron-down" class="w-5 h-5"></i>
                </span>
            </summary>
            <div class="mt-4 pt-4 border-t border-border text-sm text-text-secondary leading-relaxed space-y-2">
                <p>
                    Anda dapat memilih salah satu kausa sosial yang ada di <strong>Katalog Kausa</strong>, lalu klik tombol <strong>"Donasi Sekarang"</strong>. Tentukan nominal bantuan, isi nama dan email donatur, lalu pilih metode pembayaran yang Anda kehendaki (QRIS, Virtual Account Bank Jatim / Bank Nasional, atau E-Wallet).
                </p>
                <p>
                    Setelah pembayaran berhasil diverifikasi secara otomatis oleh sistem, Anda akan mendapatkan bukti kuitansi digital resmi berstempel Pemkab Tulungagung.
                </p>
            </div>
        </details>

        <!-- Accordion Item 2 -->
        <details class="bg-surface border border-border rounded-2xl p-5 sm:p-6 shadow-card group transition-all open:ring-2 open:ring-primary/20">
            <summary class="font-display font-bold text-base sm:text-lg text-text-primary flex items-center justify-between cursor-pointer list-none">
                <span class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-primary-subtle text-primary flex items-center justify-center text-xs font-bold shrink-0">2</span>
                    Siapa saja yang berhak mengajukan program / kausa bantuan?
                </span>
                <span class="p-1 rounded-lg text-text-muted group-hover:text-primary transition-transform group-open:rotate-180">
                    <i data-lucide="chevron-down" class="w-5 h-5"></i>
                </span>
            </summary>
            <div class="mt-4 pt-4 border-t border-border text-sm text-text-secondary leading-relaxed space-y-2">
                <p>
                    Demi mencegah penyalahgunaan dana dan program fiktif, pengajuan kausa hanya dapat dilakukan oleh:
                </p>
                <ul class="list-disc list-inside space-y-1 ml-2">
                    <li>Organisasi Perangkat Daerah (OPD) teknis di lingkungan Pemkab Tulungagung (Dinsos, Dinkes, BPBD, dll).</li>
                    <li>Lembaga Kesejahteraan Sosial (LKS) atau Yayasan Resmi yang telah terdaftar dan terakreditasi di Dinas Sosial Kabupaten Tulungagung.</li>
                </ul>
                <p>
                    Masyarakat umum yang menemukan warga membutuhkan bantuan dapat melaporkannya melalui kanal pengaduan Dinsos untuk diverifikasi oleh tim penjangkau lapangan.
                </p>
            </div>
        </details>

        <!-- Accordion Item 3 -->
        <details class="bg-surface border border-border rounded-2xl p-5 sm:p-6 shadow-card group transition-all open:ring-2 open:ring-primary/20">
            <summary class="font-display font-bold text-base sm:text-lg text-text-primary flex items-center justify-between cursor-pointer list-none">
                <span class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-primary-subtle text-primary flex items-center justify-center text-xs font-bold shrink-0">3</span>
                    Kemana dana donasi disetorkan? Apakah ada potongan komisi?
                </span>
                <span class="p-1 rounded-lg text-text-muted group-hover:text-primary transition-transform group-open:rotate-180">
                    <i data-lucide="chevron-down" class="w-5 h-5"></i>
                </span>
            </summary>
            <div class="mt-4 pt-4 border-t border-border text-sm text-text-secondary leading-relaxed space-y-2">
                <p>
                    Seluruh dana donasi masyarakat masuk langsung ke <strong>Rekening Penampung Kas Daerah Pemerintah Kabupaten Tulungagung</strong> di Bank Jatim Cabang Tulungagung.
                </p>
                <p>
                    Pemkab Tulungagung tidak memungut komisi atau potongan sepeserpun dari dana bantuan sosial. 100% dari nominal bersih yang disumbangkan disalurkan langsung kepada penerima manfaat dan program yang bersangkutan sesuai Rincian Anggaran Biaya (RAB) yang disetujui.
                </p>
            </div>
        </details>

        <!-- Accordion Item 4 -->
        <details class="bg-surface border border-border rounded-2xl p-5 sm:p-6 shadow-card group transition-all open:ring-2 open:ring-primary/20">
            <summary class="font-display font-bold text-base sm:text-lg text-text-primary flex items-center justify-between cursor-pointer list-none">
                <span class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-primary-subtle text-primary flex items-center justify-center text-xs font-bold shrink-0">4</span>
                    Bagaimana transparansi penyaluran dan audit bukti nota belanja?
                </span>
                <span class="p-1 rounded-lg text-text-muted group-hover:text-primary transition-transform group-open:rotate-180">
                    <i data-lucide="chevron-down" class="w-5 h-5"></i>
                </span>
            </summary>
            <div class="mt-4 pt-4 border-t border-border text-sm text-text-secondary leading-relaxed space-y-2">
                <p>
                    Sesuai Peraturan Bupati Tulungagung, setiap instansi pengelola kausa diwajibkan mengunggah <strong>Laporan Pertanggungjawaban (SPJ)</strong> berupa:
                </p>
                <ul class="list-disc list-inside space-y-1 ml-2">
                    <li>Kuitansi toko dan faktur pembelian berstempel basah.</li>
                    <li>Berita Acara Serah Terima (BAST) bantuan.</li>
                    <li>Foto dokumentasi penyerahan bantuan secara nyata.</li>
                </ul>
                <p>
                    Seluruh berkas ini diverifikasi oleh Auditor Inspektorat Daerah dan dipublikasikan secara terbuka di <strong>Portal Transparansi</strong> agar dapat diawasi oleh seluruh warga.
                </p>
            </div>
        </details>

        <!-- Accordion Item 5 -->
        <details class="bg-surface border border-border rounded-2xl p-5 sm:p-6 shadow-card group transition-all open:ring-2 open:ring-primary/20">
            <summary class="font-display font-bold text-base sm:text-lg text-text-primary flex items-center justify-between cursor-pointer list-none">
                <span class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-primary-subtle text-primary flex items-center justify-center text-xs font-bold shrink-0">5</span>
                    Apakah donatur dapat berdonasi secara anonim (Hamba Allah)?
                </span>
                <span class="p-1 rounded-lg text-text-muted group-hover:text-primary transition-transform group-open:rotate-180">
                    <i data-lucide="chevron-down" class="w-5 h-5"></i>
                </span>
            </summary>
            <div class="mt-4 pt-4 border-t border-border text-sm text-text-secondary leading-relaxed">
                <p>
                    Tentu saja. Anda cukup mencentang opsi <em>"Sembunyikan nama saya dari daftar publik (Donatur Anonim / Hamba Allah)"</em> saat melakukan transaksi donasi. Nama Anda tidak akan ditampilkan di papan donatur publik, namun bukti kuitansi tetap akan dikirimkan ke email pribadi Anda sebagai arsip.
                </p>
            </div>
        </details>

        <!-- Contact Box -->
        <div class="mt-10 bg-gradient-to-r from-secondary to-secondary-hover rounded-2xl p-8 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl">
            <div class="space-y-1.5 text-center md:text-left">
                <span class="text-xs uppercase tracking-wider font-semibold text-emerald-300">Belum Menemukan Jawaban?</span>
                <h3 class="font-display font-bold text-xl sm:text-2xl text-white">Hubungi Tim Layanan Dinsos Tulungagung</h3>
                <p class="text-xs sm:text-sm text-emerald-100/90 max-w-lg">
                    Petugas PPID Dinas Sosial siap membantu Anda pada hari dan jam kerja kedinasan (Senin - Jumat, 08.00 - 15.30 WIB).
                </p>
            </div>
            <a href="{{ route('pages.kontak') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-primary hover:bg-primary-hover text-white font-semibold text-xs transition-all shadow-md shrink-0 active:scale-98">
                <span>Kirim Pesan</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

    </div>
</div>
@endsection
