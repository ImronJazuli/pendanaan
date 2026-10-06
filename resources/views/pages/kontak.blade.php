@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background">
    <!-- Header -->
    <div class="bg-surface border-b border-border">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
            <div class="max-w-3xl">
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-primary-subtle text-primary border border-primary/20">
                        Layanan Masyarakat
                    </span>
                    <span class="text-xs text-text-muted">&bull;</span>
                    <span class="text-xs text-text-muted">PPID Dinas Sosial Kab. Tulungagung</span>
                </div>
                <h1 class="font-display font-bold text-3xl sm:text-4xl text-text-primary tracking-tight">
                    Hubungi Layanan Terpadu
                </h1>
                <p class="text-sm sm:text-base text-text-secondary mt-2">
                    Punya pertanyaan seputar verifikasi kausa, bantuan sosial, atau kendala transaksi? Tim kami siap melayani Anda.
                </p>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid gap-8 lg:grid-cols-3">
            
            <!-- Contact Info Cards -->
            <div class="lg:col-span-1 space-y-4">
                
                <!-- Alamat Card -->
                <div class="bg-surface rounded-2xl border border-border p-6 shadow-card hover:border-border-hover transition-all">
                    <div class="flex items-start gap-3.5">
                        <div class="p-2.5 rounded-xl bg-primary-subtle text-primary shrink-0">
                            <i data-lucide="map-pin" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="font-display font-bold text-sm text-text-primary mb-1">Kantor Dinas Sosial</h3>
                            <p class="text-xs sm:text-sm text-text-secondary leading-relaxed">
                                Gedung Layanan Terpadu Pemkab Tulungagung<br>
                                Jl. Pangeran Diponegoro No. 120<br>
                                Kabupaten Tulungagung, Jawa Timur 66212
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Kontak Card -->
                <div class="bg-surface rounded-2xl border border-border p-6 shadow-card hover:border-border-hover transition-all">
                    <div class="flex items-start gap-3.5">
                        <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-800 shrink-0">
                            <i data-lucide="phone-call" class="w-5 h-5 text-primary"></i>
                        </div>
                        <div>
                            <h3 class="font-display font-bold text-sm text-text-primary mb-1">Telepon & WhatsApp</h3>
                            <p class="text-xs sm:text-sm text-text-secondary">
                                <a href="tel:+62355321000" class="hover:text-primary transition-colors font-medium block">
                                    (0355) 321-000 (Sentral)
                                </a>
                                <span class="text-xs text-text-muted mt-0.5 block">Hotline PPID: 0812-3456-7890</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Email Card -->
                <div class="bg-surface rounded-2xl border border-border p-6 shadow-card hover:border-border-hover transition-all">
                    <div class="flex items-start gap-3.5">
                        <div class="p-2.5 rounded-xl bg-surface-muted text-secondary shrink-0">
                            <i data-lucide="mail" class="w-5 h-5 text-primary"></i>
                        </div>
                        <div>
                            <h3 class="font-display font-bold text-sm text-text-primary mb-1">Email Resmi</h3>
                            <p class="text-xs sm:text-sm text-text-secondary">
                                <a href="mailto:dinsos@tulungagung.go.id" class="hover:text-primary transition-colors font-mono">
                                    dinsos@tulungagung.go.id
                                </a>
                                <span class="text-xs text-text-muted mt-0.5 block">helpdesk.sipeduli@tulungagung.go.id</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Jam Operasional Card -->
                <div class="bg-surface rounded-2xl border border-border p-6 shadow-card">
                    <div class="flex items-start gap-3.5">
                        <div class="p-2.5 rounded-xl bg-amber-50 text-amber-800 shrink-0">
                            <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-display font-bold text-sm text-text-primary mb-1">Jam Pelayanan Kedinasan</h3>
                            <p class="text-xs text-text-secondary">Senin - Kamis: 07.30 - 15.30 WIB</p>
                            <p class="text-xs text-text-secondary">Jumat: 07.30 - 15.00 WIB</p>
                            <p class="text-[11px] text-text-muted">Sabtu, Minggu & Hari Libur Nasional: Tutup</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Form Pengaduan / Pertanyaan -->
            <div class="lg:col-span-2">
                <div class="bg-surface rounded-2xl border border-border p-6 sm:p-8 shadow-card space-y-6">
                    <div>
                        <span class="text-xs font-semibold text-primary uppercase tracking-wider">Formulir Pesan Cepat</span>
                        <h2 class="font-display font-bold text-xl sm:text-2xl text-text-primary mt-1">Kirimkan Pertanyaan atau Saran Anda</h2>
                        <p class="text-xs sm:text-sm text-text-secondary mt-1">
                            Pesan Anda akan diteruskan kepada tim helpdesk Dinsos Pemkab Tulungagung untuk ditindaklanjuti.
                        </p>
                    </div>

                    <form class="space-y-4" onsubmit="event.preventDefault(); alert('Terima kasih. Pesan Anda telah kami terima dan akan segera diproses oleh Tim PPID Dinsos Kabupaten Tulungagung.'); this.reset();">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Nama -->
                            <div>
                                <label class="block text-xs font-semibold text-text-secondary uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                                <input type="text" placeholder="Nama Anda" required class="w-full px-4 py-2.5 text-sm rounded-xl border border-border bg-white text-text-primary focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-xs font-semibold text-text-secondary uppercase tracking-wider mb-1.5">Alamat Email</label>
                                <input type="email" placeholder="nama@email.com" required class="w-full px-4 py-2.5 text-sm rounded-xl border border-border bg-white text-text-primary focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Nomor Kontak -->
                            <div>
                                <label class="block text-xs font-semibold text-text-secondary uppercase tracking-wider mb-1.5">Nomor WhatsApp / HP</label>
                                <input type="tel" placeholder="08xxxxxxxxxx" class="w-full px-4 py-2.5 text-sm rounded-xl border border-border bg-white text-text-primary focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            </div>

                            <!-- Kategori Pertanyaan -->
                            <div>
                                <label class="block text-xs font-semibold text-text-secondary uppercase tracking-wider mb-1.5">Perihal / Kategori</label>
                                <select required class="w-full px-4 py-2.5 text-sm rounded-xl border border-border bg-white text-text-primary focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                    <option value="">Pilih Perihal</option>
                                    <option value="konfirmasi_donasi">Konfirmasi Status Donasi</option>
                                    <option value="pendaftaran_lembaga">Pendaftaran Lembaga / Akreditasi</option>
                                    <option value="revisi_kausa">Kendala Verifikasi Kausa</option>
                                    <option value="laporan_masalah">Pengaduan Kausa / Dugaan Pungli</option>
                                    <option value="lainnya">Pertanyaan Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <!-- Pesan -->
                        <div>
                            <label class="block text-xs font-semibold text-text-secondary uppercase tracking-wider mb-1.5">Isi Pesan / Keterangan Lengkap</label>
                            <textarea rows="5" placeholder="Tuliskan pesan atau pertanyaan Anda secara rinci di sini..." required class="w-full px-4 py-3 text-sm rounded-xl border border-border bg-white text-text-primary focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-y"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3 rounded-xl bg-primary hover:bg-primary-hover active:bg-primary-active text-white font-semibold text-xs transition-all shadow-sm active:scale-98">
                                <i data-lucide="send" class="w-4 h-4"></i>
                                Kirim Pesan Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <!-- Google Maps / Office Location Banner -->
        <div class="mt-10 bg-surface rounded-2xl border border-border overflow-hidden shadow-card">
            <div class="p-6 bg-gradient-to-r from-emerald-50 via-white to-primary-subtle border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-primary text-white shrink-0">
                        <i data-lucide="navigation" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-display font-bold text-sm sm:text-base text-text-primary">Lokasi Kantor Dinas Sosial Kabupaten Tulungagung</h4>
                        <p class="text-xs text-text-muted mt-0.5">Pusat Layanan PPID Bantuan Kesejahteraan Sosial</p>
                    </div>
                </div>
                <a href="https://maps.google.com/?q=Dinas+Sosial+Tulungagung" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-border hover:border-primary text-xs font-semibold text-text-secondary hover:text-primary shadow-xs transition-all">
                    <span>Buka Google Maps</span>
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                </a>
            </div>
            <div class="h-64 sm:h-80 bg-surface-muted flex flex-col items-center justify-center text-center p-6">
                <div class="w-12 h-12 rounded-2xl bg-white border border-border flex items-center justify-center text-primary shadow-xs mb-3">
                    <i data-lucide="map" class="w-6 h-6"></i>
                </div>
                <h5 class="font-display font-bold text-sm text-text-primary">Peta Lokasi Kantor Terpadu</h5>
                <p class="text-xs text-text-muted mt-1 max-w-sm">
                    Kawasan Pusat Pemerintahan Kabupaten Tulungagung, Jawa Timur. Akses mudah dari Alun-Alun Tulungagung & Stasiun Kereta Api.
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
