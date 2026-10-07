@extends('layouts.dashboard-instansi')

@section('title', 'Pedoman SPJ & Standar Kuitansi Resmi')
@section('topbar-title', 'Panduan Standar Pertanggungjawaban (SPJ)')
@section('topbar-subtitle', 'Kabupaten Tulungagung • Sistem Monitoring Akuntabilitas Bantuan Kemasyarakatan')

@section('content')
<div class="bg-[#F6F8F7] py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Breadcrumb & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-[#D9E2DE]">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#73817C] mb-2" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard.instansi') }}" class="hover:text-[#087F5B] transition-colors">Dashboard Instansi</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span class="text-[#17211E] font-medium">Panduan SPJ &amp; Kuitansi</span>
                </nav>
                <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-[#17211E] tracking-tight">
                    Pedoman Penyusunan SPJ &amp; Kuitansi Sah
                </h1>
                <p class="text-xs sm:text-sm text-[#52615C] mt-1 max-w-3xl leading-relaxed">
                    Petunjuk teknis resmi bagi Instansi, OPD, Yayasan, dan Lembaga Kesejahteraan Sosial dalam menyusun Laporan Pertanggungjawaban (LPJ) dana sosial kemasyarakatan di Kabupaten Tulungagung.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('instansi.laporan') }}" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition-all">
                    <i data-lucide="file-text" class="w-4 h-4 text-emerald-600"></i>
                    <span>Kembali ke Laporan Dana</span>
                </a>
            </div>
        </div>

        <!-- Dasar Hukum & Prinsip Akuntabilitas -->
        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 sm:p-7 shadow-xs space-y-4">
            <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                <span class="p-2 rounded-xl bg-emerald-50 text-[#087F5B]">
                    <i data-lucide="scale" class="w-5 h-5"></i>
                </span>
                <div>
                    <h2 class="font-heading font-bold text-base text-[#123B32]">1. Dasar Hukum &amp; Prinsip Transparansi</h2>
                    <p class="text-xs text-[#52615C]">Landasan regulasi pengelolaan dana publik Pemkab Tulungagung.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-1.5">
                    <div class="font-bold text-slate-800 flex items-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4 text-emerald-600"></i>
                        <span>UU No. 10 Tahun 2020</span>
                    </div>
                    <p class="text-slate-600 leading-relaxed">
                        Penggunaan Bea Meterai Rp 10.000 wajib dilekatkan pada kuitansi pembayaran dengan nilai transaksi di atas Rp 5.000.000.
                    </p>
                </div>

                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-1.5">
                    <div class="font-bold text-slate-800 flex items-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4 text-emerald-600"></i>
                        <span>Permendagri No. 77/2020</span>
                    </div>
                    <p class="text-slate-600 leading-relaxed">
                        Pedoman teknis pengelolaan keuangan daerah dan akuntabilitas belanja bansos/hibah sosial kemasyarakatan.
                    </p>
                </div>

                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-1.5">
                    <div class="font-bold text-slate-800 flex items-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4 text-emerald-600"></i>
                        <span>Perbup Tulungagung</span>
                    </div>
                    <p class="text-slate-600 leading-relaxed">
                        Tata cara penyaluran, pemantauan, dan audit publik terhadap dana sosial yang dihimpun melalui portal resmi pemerintah.
                    </p>
                </div>
            </div>
        </div>

        <!-- Standar Kuitansi & Nota Pembelian -->
        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 sm:p-7 shadow-xs space-y-4">
            <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                <span class="p-2 rounded-xl bg-emerald-50 text-[#087F5B]">
                    <i data-lucide="receipt" class="w-5 h-5"></i>
                </span>
                <div>
                    <h2 class="font-heading font-bold text-base text-[#123B32]">2. Standar Kuitansi &amp; Nota Pembelian Sah</h2>
                    <p class="text-xs text-[#52615C]">Syarat formal bukti pengeluaran yang diakui tim verifikator.</p>
                </div>
            </div>

            <div class="space-y-3 text-xs text-slate-700 leading-relaxed">
                <div class="p-4 rounded-xl border border-emerald-200 bg-emerald-50/40 space-y-2">
                    <h3 class="font-bold text-emerald-950 flex items-center gap-2">
                        <i data-lucide="info" class="w-4 h-4 text-emerald-600"></i>
                        <span>Ketentuan Meterai &amp; Stempel Toko / Penyedia Barang:</span>
                    </h3>
                    <ul class="list-disc list-inside space-y-1 pl-2 text-emerald-900">
                        <li><strong>Transaksi s/d Rp 5.000.000:</strong> Nota kontan toko bermeterai nol (tanpa meterai), wajib mencantumkan nama toko, tanggal, cap/stempel basah toko, dan rincian kuantum barang.</li>
                        <li><strong>Transaksi > Rp 5.000.000:</strong> Kuitansi dinas wajib dibubuhi <strong>Meterai Rp 10.000</strong> dengan tanda tangan mengenai sebagian meterai serta stempel basah penyedia barang/jasa.</li>
                        <li><strong>Dilarang:</strong> Nota tulis tangan tanpa identitas toko yang jelas, nota fiktif, atau kuitansi yang nominalnya dipecah-pecah (<em>split invoice</em>) untuk menghindari meterai.</li>
                    </ul>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-1.5">
                        <h4 class="font-bold text-slate-800">Unsur Wajib Kuitansi Dinas:</h4>
                        <ul class="list-disc list-inside space-y-1 text-slate-600 text-[11px]">
                            <li>Nomor kuitansi & tanggal pembayaran</li>
                            <li>Pemberi dana: <em>Nama Lembaga / Kausa Tulungagung</em></li>
                            <li>Penerima pembayaran: <em>Nama & Tanda Tangan Toko/Penyedia</em></li>
                            <li>Jumlah nominal dalam angka dan huruf (terbilang)</li>
                            <li>Uraian peruntukan barang/jasa secara rinci</li>
                        </ul>
                    </div>

                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-1.5">
                        <h4 class="font-bold text-slate-800">Unsur Wajib Nota Kontan:</h4>
                        <ul class="list-disc list-inside space-y-1 text-slate-600 text-[11px]">
                            <li>Kop/Nama dan alamat toko/bengkel/supplier</li>
                            <li>Nama barang, kuantitas satuan, harga satuan, total</li>
                            <li>Tanda lunas toko beserta cap stempel basah</li>
                            <li>Dokumentasi foto serah terima barang di lokasi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ketentuan Berita Acara Serah Terima (BAST) & Batasan Operasional -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- BAST -->
            <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 sm:p-7 shadow-xs space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                    <span class="p-2 rounded-xl bg-emerald-50 text-[#087F5B]">
                        <i data-lucide="file-check" class="w-5 h-5"></i>
                    </span>
                    <div>
                        <h2 class="font-heading font-bold text-base text-[#123B32]">3. Berita Acara Serah Terima (BAST)</h2>
                        <p class="text-xs text-[#52615C]">Wajib untuk penyerahan barang / santunan tunai.</p>
                    </div>
                </div>

                <div class="space-y-2 text-xs text-slate-700 leading-relaxed">
                    <p>Setiap penyaluran bantuan langsung ke masyarakat (paket sembako, alat bantu disabilitas, santunan anak yatim, bahan bangunan) <strong>wajib menyertakan BAST</strong> dengan ketentuan:</p>
                    <ul class="list-disc list-inside space-y-1 pl-1 text-slate-600 text-[11px]">
                        <li>Identitas jelas penerima manfaat (Nama, NIK, Alamat Desa/Kelurahan).</li>
                        <li>Daftar kuantitas bantuan yang diterima secara fisik.</li>
                        <li>Tanda tangan penerima dan perwakilan lembaga penyalur.</li>
                        <li>Mengetahui Ketua RT/RW atau perangkat desa setempat.</li>
                        <li>Lampiran foto dokumentasi penyerahan barang di lapangan.</li>
                    </ul>
                </div>
            </div>

            <!-- Batasan Operasional -->
            <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 sm:p-7 shadow-xs space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                    <span class="p-2 rounded-xl bg-emerald-50 text-[#087F5B]">
                        <i data-lucide="shield-alert" class="w-5 h-5"></i>
                    </span>
                    <div>
                        <h2 class="font-heading font-bold text-base text-[#123B32]">4. Batasan Biaya Operasional Penyaluran</h2>
                        <p class="text-xs text-[#52615C]">Maksimal 10% dari dana terkumpul.</p>
                    </div>
                </div>

                <div class="space-y-2 text-xs text-slate-700 leading-relaxed">
                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-900 text-[11px]">
                        <strong>Ketentuan Maksimal 10%:</strong> Alokasi biaya operasional penunjang (transportasi distribusi logistik, sewa armada truk, bahan bakar, dokumentasi lapangan) tidak boleh melebihi 10% dari total dana terkumpul.
                    </div>
                    <h4 class="font-bold text-slate-800 text-xs mt-2">Pengeluaran Yang Dilarang (Diskualifikasi):</h4>
                    <ul class="list-disc list-inside space-y-1 pl-1 text-rose-700 text-[11px]">
                        <li>Gaji/honor tetap bagi pengurus inti yayasan/lembaga.</li>
                        <li>Pembelian aset tetap pribadi (laptop pribadi, smartphone, dll).</li>
                        <li>Biaya konsumsi mewah/tidak berkaitan langsung dengan penyaluran.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Checklist Kelengkapan Administrasi SPJ -->
        <div class="bg-white rounded-2xl border border-[#D9E2DE] p-5 sm:p-7 shadow-xs space-y-4">
            <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                <span class="p-2 rounded-xl bg-emerald-50 text-[#087F5B]">
                    <i data-lucide="check-square" class="w-5 h-5"></i>
                </span>
                <div>
                    <h2 class="font-heading font-bold text-base text-[#123B32]">5. Checklist Kelengkapan Dokumen Sebelum Mengirim LPJ</h2>
                    <p class="text-xs text-[#52615C]">Pastikan kelengkapan berkas sebelum mengklik tombol kirim verifikasi.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                <label class="flex items-start gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50 cursor-pointer">
                    <input type="checkbox" checked disabled class="rounded text-emerald-600 mt-0.5">
                    <div>
                        <span class="font-bold text-slate-800">Surat Pengantar LPJ Lembaga</span>
                        <p class="text-[11px] text-slate-500">Ditandatangani oleh pimpinan/ketua lembaga bertanda stempel.</p>
                    </div>
                </label>

                <label class="flex items-start gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50 cursor-pointer">
                    <input type="checkbox" checked disabled class="rounded text-emerald-600 mt-0.5">
                    <div>
                        <span class="font-bold text-slate-800">Tabel Rekapitulasi Rincian Belanja</span>
                        <p class="text-[11px] text-slate-500">Mencakup seluruh pos pengeluaran beserta nomor urut bukti.</p>
                    </div>
                </label>

                <label class="flex items-start gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50 cursor-pointer">
                    <input type="checkbox" checked disabled class="rounded text-emerald-600 mt-0.5">
                    <div>
                        <span class="font-bold text-slate-800">Scan Kuitansi &amp; Nota Kontan Toko Asli</span>
                        <p class="text-[11px] text-slate-500">Bermeterai Rp 10.000 jika nilai belanja di atas Rp 5 Juta.</p>
                    </div>
                </label>

                <label class="flex items-start gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50 cursor-pointer">
                    <input type="checkbox" checked disabled class="rounded text-emerald-600 mt-0.5">
                    <div>
                        <span class="font-bold text-slate-800">BAST Penerima Manfaat &amp; Foto Penyaluran</span>
                        <p class="text-[11px] text-slate-500">Dilengkapi tanda tangan penerima dan dokumentasi visual.</p>
                    </div>
                </label>
            </div>
        </div>

    </div>
</div>
@endsection
