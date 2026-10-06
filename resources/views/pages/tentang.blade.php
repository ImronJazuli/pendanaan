@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background">
    <!-- Header -->
    <div class="bg-surface border-b border-border">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
            <div class="max-w-3xl">
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-primary-subtle text-primary border border-primary/20">
                        Profil & Landasan
                    </span>
                    <span class="text-xs text-text-muted">&bull;</span>
                    <span class="text-xs text-text-muted">Inisiatif Pemkab Tulungagung</span>
                </div>
                <h1 class="font-display font-bold text-3xl sm:text-4xl text-text-primary tracking-tight">
                    Tentang Portal SI-PEDULI Tulungagung
                </h1>
                <p class="text-sm sm:text-base text-text-secondary mt-2">
                    Sistem Informasi Penggalangan Dana Sosial Terpadu untuk Memastikan Akuntabilitas, Transparansi, dan Gotong Royong Warga Kabupaten Tulungagung.
                </p>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        
        <!-- Sejarah / Latar Belakang Card -->
        <div class="bg-surface rounded-2xl border border-border p-6 sm:p-8 shadow-card space-y-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 rounded-xl bg-primary-subtle text-primary">
                    <i data-lucide="landmark" class="w-6 h-6"></i>
                </div>
                <div>
                    <h2 class="font-display font-bold text-xl text-text-primary">Latar Belakang & Mandat Pemerintah Daerah</h2>
                    <p class="text-xs text-text-muted">Sesuai Perbup Tulungagung tentang Pengelolaan Sumbangan Sosial Masyarakat</p>
                </div>
            </div>
            
            <p class="text-sm text-text-secondary leading-relaxed pt-2">
                Portal Pendanaan Sosial Pemkab Tulungagung (SI-PEDULI) adalah inisiatif strategis Pemerintah Kabupaten Tulungagung melalui Dinas Sosial dan PPID Sekretariat Daerah. Platform ini dirancang untuk memodernisasi tata kelola donasi kemanusiaan agar terhindar dari pemungutan liar (pungli), sumbangan fiktif, serta ketidakjelasan penyaluran bantuan.
            </p>
            <p class="text-sm text-text-secondary leading-relaxed">
                Seluruh dana donasi masyarakat terhimpun langsung ke Rekening Kas Daerah Pemerintah Kabupaten Tulungagung (Bank Jatim Cabang Tulungagung) dan disalurkan secara transparan dengan audit nota fisik (SPJ) yang dapat diakses oleh publik secara terbuka.
            </p>
        </div>

        <!-- Visi & Misi Cards -->
        <div class="grid gap-6 md:grid-cols-2">
            <div class="bg-surface rounded-2xl border border-border p-6 sm:p-8 shadow-card flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center mb-4">
                        <i data-lucide="target" class="w-6 h-6 text-primary"></i>
                    </div>
                    <h3 class="font-display font-bold text-xl text-text-primary mb-3">Visi Kami</h3>
                    <p class="text-sm text-text-secondary leading-relaxed">
                        Terwujudnya tata kelola filantropi sosial masyarakat Kabupaten Tulungagung yang berkeadilan, transparan, berlandaskan teknologi digital, dan mampu mengentaskan kerentanan sosial secara gotong royong.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-border flex items-center gap-2 text-xs font-semibold text-primary">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    Birokrasi Melayani & Terpercaya
                </div>
            </div>

            <div class="bg-surface rounded-2xl border border-border p-6 sm:p-8 shadow-card flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-surface-muted text-secondary flex items-center justify-center mb-4">
                        <i data-lucide="list-checks" class="w-6 h-6 text-primary"></i>
                    </div>
                    <h3 class="font-display font-bold text-xl text-text-primary mb-3">Misi Utama</h3>
                    <ul class="text-sm text-text-secondary leading-relaxed space-y-2.5">
                        <li class="flex items-start gap-2.5">
                            <i data-lucide="check" class="w-4 h-4 text-primary shrink-0 mt-0.5"></i>
                            <span>Memverifikasi ketat seluruh lembaga pengaju kausa melalui verifikator resmi Pemkab.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i data-lucide="check" class="w-4 h-4 text-primary shrink-0 mt-0.5"></i>
                            <span>Menyediakan sistem pembayaran donasi online yang aman dan terintegrasi dengan Payment Gateway nasional.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i data-lucide="check" class="w-4 h-4 text-primary shrink-0 mt-0.5"></i>
                            <span>Mempublikasikan nota fisik, kuitansi, dan foto serah terima bantuan secara real-time.</span>
                        </li>
                    </ul>
                </div>
                <div class="mt-6 pt-4 border-t border-border flex items-center gap-2 text-xs font-semibold text-primary">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    Bebas Pungli & Kausa Fiktif
                </div>
            </div>
        </div>

        <!-- 4 Pilar Nilai Dasar -->
        <div class="bg-surface rounded-2xl border border-border p-6 sm:p-8 shadow-card space-y-6">
            <div class="text-center max-w-xl mx-auto">
                <span class="text-xs font-semibold text-text-muted uppercase tracking-wider">Komitmen Pelayanan</span>
                <h3 class="font-display font-bold text-2xl text-text-primary mt-1">4 Pilar Nilai Dasar SI-PEDULI</h3>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 pt-4">
                <div class="p-4 rounded-xl bg-surface-muted/60 border border-border space-y-2">
                    <div class="w-9 h-9 rounded-lg bg-primary-subtle text-primary flex items-center justify-center font-bold">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                    </div>
                    <h4 class="font-display font-bold text-sm text-text-primary">1. Transparansi Penuh</h4>
                    <p class="text-xs text-text-secondary leading-relaxed">Seluruh aliran rupiah dari donatur hingga penerima manfaat dipublikasikan terbuka.</p>
                </div>

                <div class="p-4 rounded-xl bg-surface-muted/60 border border-border space-y-2">
                    <div class="w-9 h-9 rounded-lg bg-primary-subtle text-primary flex items-center justify-center font-bold">
                        <i data-lucide="shield" class="w-4 h-4"></i>
                    </div>
                    <h4 class="font-display font-bold text-sm text-text-primary">2. Legalitas Teruji</h4>
                    <p class="text-xs text-text-secondary leading-relaxed">Hanya lembaga terdaftar di Dinsos Tulungagung yang berhak menggalang donasi publik.</p>
                </div>

                <div class="p-4 rounded-xl bg-surface-muted/60 border border-border space-y-2">
                    <div class="w-9 h-9 rounded-lg bg-primary-subtle text-primary flex items-center justify-center font-bold">
                        <i data-lucide="heart" class="w-4 h-4"></i>
                    </div>
                    <h4 class="font-display font-bold text-sm text-text-primary">3. Gotong Royong</h4>
                    <p class="text-xs text-text-secondary leading-relaxed">Mempertemukan kepedulian warga dengan saudara kita yang membutuhkan uluran tangan.</p>
                </div>

                <div class="p-4 rounded-xl bg-surface-muted/60 border border-border space-y-2">
                    <div class="w-9 h-9 rounded-lg bg-primary-subtle text-primary flex items-center justify-center font-bold">
                        <i data-lucide="award" class="w-4 h-4"></i>
                    </div>
                    <h4 class="font-display font-bold text-sm text-text-primary">4. Akuntabilitas SPJ</h4>
                    <p class="text-xs text-text-secondary leading-relaxed">Wajib unggah bukti nota belanja dan foto serah terima barang kepada penerima.</p>
                </div>
            </div>
        </div>

        <!-- Banner Kontak & Bantuan -->
        <div class="bg-gradient-to-r from-secondary to-secondary-hover rounded-2xl p-8 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl">
            <div class="space-y-1.5 text-center md:text-left">
                <span class="text-xs uppercase tracking-wider font-semibold text-emerald-300">Pusat Informasi & Pelayanan</span>
                <h3 class="font-display font-bold text-2xl text-white">Ingin Mengetahui Lebih Lanjut?</h3>
                <p class="text-xs sm:text-sm text-emerald-100/90 max-w-lg">
                    Hubungi Tim PPID Dinas Sosial Kabupaten Tulungagung atau kirimkan pertanyaan seputar legalitas program bantuan sosial.
                </p>
            </div>
            <a href="{{ route('pages.kontak') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-primary hover:bg-primary-hover text-white font-semibold text-xs transition-all shadow-md shrink-0 active:scale-98">
                <span>Hubungi Kami</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

    </div>
</div>
@endsection
