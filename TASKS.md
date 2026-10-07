# Tasks

## Pondasi & Arsitektur
- [x] PRD dan dokumentasi vibecoding
- [x] Architecture, security, data, workflow
- [x] Final review schema
- [x] Migration users, instansi, dan tabel akses
- [x] Authentication/RBAC
- [x] Layout dashboard instansi & admin

## Sprint M5-M8 (Pengajuan & Verifikasi)
- [x] Workflow pengajuan kausa
- [x] Verifikasi admin (setujui, tolak, minta revisi)
- [x] Notifikasi perubahan status

## Sprint M9-M10 (Katalog, Donasi, Laporan)
- [x] Katalog publik & detail kausa (M9)
- [x] Donasi & pembayaran simulasi (M10)
- [x] Laporan dana & transparansi (M9-M10)
- [x] Feature tests lengkap



# Action Plan & Task List - Portal Pendanaan Sosial Pemkab Tulungagung

## TAHAP 1: Perbaikan Kritis & Bug Pembuka (Hari Ini / Segera)
- [x] Profil Instansi (Langkah 1)
  - [x] Buat View `resources/views/dashboard/instansi/profil.blade.php` (Status Legalitas, Form Info Lembaga, Kontak & PIC, List Dokumen Legalitas)
  - [x] Update Controller `app/Http/Controllers/Dashboard/InstansiDashboardController.php`:
    - [x] Implementasi method `profil()` (load relasi instansi & dokumenInstansi)
    - [x] Implementasi method `updateProfil()` (FormRequest validation, simpan data instansi, flash message)
- [x] Harmonisasi Penamaan Role & Middleware
  - [x] Audit `routes/web.php` untuk merapikan middleware `role:institution_user` vs `role:instansi`
  - [x] Pastikan middleware role seragam (`instansi`, `donatur`, `admin`) tanpa konflik

## TAHAP 2: Perbaikan Role Instansi / OPD (Pengaju Kausa)
- [x] Alur Revisi Kausa (Resubmit Revision)
  - [x] Buat tampilan edit kausa `/dashboard/instansi/{kausa}/edit` dengan kotak catatan revisi dari Admin Pemkab
  - [x] Logic resubmit di Controller (ubah status `perlu_diperbaiki` → `menunggu_verifikasi`)
- [x] Modul Laporan Penggunaan Dana (LPJ Instansi)
  - [x] Ganti placeholder `resources/views/dashboard/instansi/laporan.blade.php` dengan daftar kausa selesai & tombol buat laporan baru
  - [x] Buat form input laporan realisasi & tabel rincian pengeluaran dinamis (item belanja, nominal, upload foto/nota/BAST)
  - [x] Buat `LaporanDanaController` untuk simpan data ke `laporan_dana` dan `rincian_laporan_dana` (status awal `draf` / `menunggu_verifikasi`)
- [x] Halaman Panduan SPJ & Kuitansi
  - [x] Isi `resources/views/dashboard/instansi/panduan.blade.php` dengan pedoman SPJ, format kuitansi resmi Pemkab, dan checklist dokumen

## TAHAP 3: Perbaikan Role Donatur & Modul Donasi (Fitur Inti)
- [x] Database & Model Donasi
  - [x] Migration & Model `donations` (`id`, `kode_donasi`, `kausa_id`, `user_id`, `nominal`, `doa_dukungan`, `anonim`, `status`)
  - [x] Migration & Model `payment_transactions` (`id`, `donation_id`, `metode_pembayaran`, `nomor_referensi`, `waktu_bayar`)
- [x] Checkout & Form Donasi di Detail Kausa (`/kausa/{slug}`)
  - [x] Di `resources/views/kausa/show.blade.php`: Modal/card donasi dengan pilihan preset nominal, anonim, doa, dan metode pembayaran
  - [x] Route & Endpoint `POST /kausa/{slug}/donasi` → `DonasiController@store` (validasi nominal, generate kode `INV-YYYYMM-XXXX`, status `menunggu_pembayaran`)
- [x] Simulasi Pembayaran (Fake Payment Gateway)
  - [x] Route `GET /donasi/{kode}/bayar` → `DonasiController@payment` (halaman instruksi & QRIS dummy)
  - [x] Route `POST /donasi/{kode}/simulasi` → `DonasiController@simulate` (ubah status `menunggu_pembayaran` → `berhasil`, auto-increment `kausa.total_terkumpul`)
  - [x] Route `GET /donasi/{kode}/sukses` → `DonasiController@success` (halaman terima kasih)
  - [x] Sinkronkan donasi berhasil ke tabel riwayat di `/dashboard/donatur`
- [x] Kuitansi / Bukti Donasi Digital
  - [x] Tambahkan fitur unduh / modal cetak Bukti Donasi Digital berlogo Pemkab Tulungagung di `/dashboard/donatur`

## TAHAP 4: Perbaikan Role Admin Pemkab (Verifikasi & Pengawasan)
- [x] Halaman Legalitas Instansi (`/dashboard/admin/legalitas`)
  - [x] View & Controller untuk tabel pendaftaran instansi/yayasan baru
  - [x] Action preview dokumen SK/NPWP, tombol Verifikasi Instansi, dan tombol Tolak dengan catatan
- [x] Halaman Verifikasi LPJ (`/dashboard/admin/laporan`)
  - [x] View & Controller antrean LPJ instansi (`resources/views/dashboard/admin/laporan.blade.php`)
  - [x] Action approval: [Setujui & Publikasikan] atau [Minta Revisi SPJ]
- [x] Halaman Monitoring Kausa Aktif (`/dashboard/admin/kausa-aktif`)
  - [x] View & Controller monitoring seluruh kausa yang tayang (progress bar, sisa hari, tombol darurat nonaktifkan/tutup)
- [x] Halaman Rekap Donasi Masuk (`/dashboard/admin/donasi`)
  - [x] View & Controller rekapitulasi mutasi donasi per hari/minggu & filter per kausa

## TAHAP 5: Transparansi Publik & Finishing Tampilan
- [x] Portal Transparansi Publik (`/transparansi`)
  - [x] Connect data LPJ yang disetujui Admin ke portal publik `/transparansi` (tampilkan rincian nota & foto penyaluran)
- [x] Finishing Visual & UI Consistency
  - [x] Penyesuaian warna khas Pemkab Tulungagung (`#087F5B`), kartu metrik, status badge, dan animasi transisi seragam di semua dashboard