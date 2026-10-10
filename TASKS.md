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


---

# Sprint Lanjutan — Gap Completion (Berdasarkan Audit PRD Oktober 2026)

> Hasil audit menyeluruh terhadap PRD, routes, controllers, views, dan test suite.
> Urutan eksekusi: Sprint A → C → B → D → E

---

## SPRINT A — Notifikasi In-App (NOT-1 ~ NOT-4)

Model `Notifikasi` sudah ada. Yang kurang: routes, controller, service, dan integrasi ke setiap titik perubahan status.

- [x] **A1** — Buat `NotifikasiService` (`app/Services/NotifikasiService.php`)
  - [x] Method statis `kirim(User $user, string $jenis, string $judul, string $isi, ?string $tautan = null): void`
  - [x] Tulis ke tabel `notifikasi` via Eloquent
- [x] **A2** — Buat `NotifikasiController` (`app/Http/Controllers/NotifikasiController.php`)
  - [x] Method `index()` — list semua notifikasi milik user login, paginate 20
  - [x] Method `markRead(Notifikasi $notifikasi)` — tandai satu notifikasi dibaca
  - [x] Method `markAllRead()` — tandai semua notifikasi user sebagai dibaca
- [x] **A3** — Tambah routes di `routes/web.php`
  - [x] `GET  /notifikasi` → `NotifikasiController@index` (auth)
  - [x] `POST /notifikasi/{notifikasi}/read` → `NotifikasiController@markRead` (auth)
  - [x] `POST /notifikasi/read-all` → `NotifikasiController@markAllRead` (auth)
- [x] **A4** — Sisipkan `NotifikasiService::kirim()` di titik-titik perubahan status:
  - [x] `AdminDashboardController@verify` (kausa disetujui) → kirim ke instansi
  - [x] `AdminDashboardController@reject` (kausa ditolak) → kirim ke instansi
  - [x] `AdminDashboardController@revise` (kausa perlu diperbaiki) → kirim ke instansi
  - [x] `AdminDashboardController@verifyLaporan` (LPJ disetujui) → kirim ke instansi
  - [x] `AdminDashboardController@rejectLaporan` (LPJ ditolak) → kirim ke instansi
  - [x] `AdminDashboardController@reviseLaporan` (LPJ minta revisi) → kirim ke instansi
  - [x] `DonasiController@simulate` (donasi berhasil) → kirim ke donatur
- [x] **A5** — Buat view `resources/views/notifikasi/index.blade.php`
  - [x] List notifikasi: icon jenis, judul, isi, waktu relatif, badge "baru" jika belum dibaca
  - [x] Tombol "Tandai Semua Dibaca"
  - [x] Link ke halaman terkait (tautan dari kolom `tautan`)
  - [x] Empty state jika belum ada notifikasi
- [x] **A6** — Update bell icon di semua layout navbar agar menampilkan data nyata
  - [x] `layouts/dashboard-admin.blade.php` — sambungkan badge ke `Notifikasi` model (admin tidak punya notifikasi in-app, skip atau tampilkan 0)
  - [x] `layouts/dashboard-instansi.blade.php` — badge = count notifikasi belum dibaca milik instansi user
  - [x] `layouts/dashboard-donatur.blade.php` — badge = count notifikasi belum dibaca milik donatur
- [x] **A7** — Buat `tests/Feature/NotifikasiTest.php`
  - [x] Test notifikasi terkirim saat kausa disetujui/tolak/revisi
  - [x] Test notifikasi terkirim saat LPJ disetujui/revisi
  - [x] Test notifikasi donatur saat donasi berhasil
  - [x] Test markRead mengubah `dibaca_pada`
  - [x] Test akses notifikasi role lain ditolak (403)

---

## SPRINT C — Midtrans Webhook Listener (DON-4)

Saat ini donasi hanya bisa diproses via simulasi manual. Sprint ini membuat endpoint webhook nyata.

- [x] **C1** — Buat route `POST /webhook/midtrans` di `routes/web.php`
  - [x] Exempt dari CSRF middleware (tambah ke `except` di `VerifyCsrfToken` atau gunakan `withoutMiddleware`)
  - [x] Arahkan ke `MidtransWebhookController@handle`
- [x] **C2** — Buat `MidtransWebhookController` (`app/Http/Controllers/MidtransWebhookController.php`)
  - [x] Parse JSON payload dari request body
  - [x] Verifikasi signature: `SHA512(order_id + status_code + gross_amount + ServerKey)`
  - [x] Jika signature tidak valid → return HTTP 403
- [x] **C3** — Logic idempotency
  - [x] Cek apakah `donasi` dengan `kode_donasi = order_id` sudah berstatus `berhasil`
  - [x] Jika sudah → return HTTP 200 tanpa proses ulang (idempotent)
- [x] **C4** — Proses status `settlement` / `capture` (pembayaran berhasil)
  - [x] Update `donasi.status = berhasil`, isi `dibayar_pada`
  - [x] Increment `kausa.dana_terkumpul` dalam database transaction
  - [x] Panggil `NotifikasiService::kirim()` ke donatur
- [x] **C5** — Proses status `cancel` / `expire` / `deny`
  - [x] Update `donasi.status` sesuai (dibatalkan / kadaluarsa / ditolak)
- [x] **C6** — Buat `tests/Feature/WebhookMidtransTest.php`
  - [x] Test signature valid → status diperbarui
  - [x] Test signature tidak valid → 403, status tidak berubah
  - [x] Test webhook duplikat → 200, `dana_terkumpul` tidak bertambah dua kali
  - [x] Test status `expire` → donasi jadi kadaluarsa

---

## SPRINT B — Verifikasi Pembayaran Manual (PAY-1 ~ PAY-5)

Alternatif pembayaran: donatur upload bukti transfer, admin verifikasi sebelum dana dicatat.

- [x] **B1** — Migration: tambah kolom ke tabel `donasi`
  - [x] `path_bukti_manual` (nullable string) — path file bukti transfer/QRIS
  - [x] `catatan_verifikasi_manual` (nullable text) — catatan admin saat approve/reject
  - [x] Jalankan `php artisan make:migration add_bukti_manual_to_donasi_table`
- [x] **B2** — Update form donasi di `kausa/show.blade.php`
  - [x] Tambah opsi metode "Transfer Manual" di form checkout donasi
  - [x] Saat dipilih, tampilkan info rekening tujuan dan input upload bukti
- [x] **B3** — Buat endpoint `POST /donasi/{kode}/upload-bukti`
  - [x] Validasi file: MIME jpg/png/pdf, max 2MB
  - [x] Simpan file ke `storage/app/public/bukti-manual/`
  - [x] Update `donasi.status = menunggu_verifikasi_manual` dan simpan path
  - [x] Arahkan ke halaman tunggu dengan instruksi
- [x] **B4** — Update `AdminDashboardController@donasi`
  - [x] Tambah tab/section "Menunggu Verifikasi Manual" di view donasi admin
  - [x] Tampilkan: nama donatur, kausa, nominal, tanggal, preview bukti
- [x] **B5** — Buat action approve manual: `POST /dashboard/admin/donasi/{donasi}/approve-manual`
  - [x] Validasi idempotency: cek status bukan sudah `berhasil`
  - [x] Dalam database transaction: ubah status → `berhasil`, increment `kausa.dana_terkumpul`
  - [x] Kirim notifikasi ke donatur
- [x] **B6** — Buat action reject manual: `POST /dashboard/admin/donasi/{donasi}/reject-manual`
  - [x] Wajib isi `catatan_verifikasi_manual`
  - [x] Update status → `ditolak_manual`
  - [x] Kirim notifikasi ke donatur
- [x] **B7** — Buat `tests/Feature/PembayaranManualTest.php`
  - [x] Test upload bukti → status jadi `menunggu_verifikasi_manual`
  - [x] Test approve → dana terakumulasi, notifikasi terkirim
  - [x] Test approve duplikat → idempotent, dana tidak dobel
  - [x] Test reject → status `ditolak_manual`, notifikasi terkirim
  - [x] Test admin lain tidak bisa approve donasi yang bukan di sistem

---

## SPRINT D — Audit & Perbaikan Tab Kausa + Galeri Foto (DON-5~7, AJU-3, PUB-9)

Validasi bahwa data real sudah tampil di halaman publik, bukan placeholder.

- [x] **D1** — Audit `resources/views/kausa/show.blade.php`
  - [x] Pastikan Tab 1 "Laporan Transparansi" menampilkan data nyata dari `log_transparansi` (judul, nominal, tanggal, bukti)
  - [x] Jika masih dummy/placeholder → sambungkan ke data Eloquent dari controller
- [x] **D2** — Audit Tab 2 "Riwayat Donatur"
  - [x] Pastikan menampilkan data nyata dari tabel `donasi` (status `berhasil`)
  - [x] Nama donatur tampil sebagai "Hamba Allah" jika `anonim = true`
  - [x] Tampilkan nominal dan waktu donasi masuk
- [x] **D3** — Audit form create kausa (`resources/views/kausa/create.blade.php` atau `KausaController@create`)
  - [x] Pastikan ada input `<input type="file" name="foto_kausa[]" multiple>` untuk upload foto
  - [x] Pastikan `KausaController@store` menyimpan file ke storage dan mencatat ke tabel `dokumen_kausa`
- [x] **D4** — Audit tampilan galeri foto di halaman detail kausa
  - [x] Pastikan foto dari `dokumen_kausa` tampil di section galeri `kausa/show.blade.php`
  - [x] Fallback: tampilkan placeholder jika foto belum ada
- [x] **D5** — Tambah test
  - [x] Test tab transparansi hanya tampilkan `log_transparansi` yang terhubung dengan kausa yang benar
  - [x] Test donasi anonim tampil sebagai "Hamba Allah" di riwayat publik

---

## SPRINT E — Melengkapi Test Suite (docs/08-Test-Plan.md)

Menutup gap coverage test sesuai test plan yang disyaratkan dokumentasi.

- [x] **E1** — Test policy ownership kausa
  - [x] Instansi A tidak bisa edit/hapus kausa milik Instansi B (expect 403)
  - [x] Instansi A bisa edit kausa miliknya sendiri
- [x] **E2** — Test akses tiap role ke route masing-masing
  - [x] Donatur tidak bisa akses `/dashboard/admin/*` (expect 403)
  - [x] Instansi tidak bisa akses `/dashboard/admin/*` (expect 403)
  - [x] Admin tidak bisa akses `/dashboard/instansi/*` (expect 403/redirect)
  - [x] Guest tidak bisa akses halaman dashboard manapun (expect redirect ke login)
- [x] **E3** — Test integritas status workflow kausa
  - [x] Kausa `ditolak` tidak bisa langsung ke `disetujui` tanpa melalui revisi
  - [x] Hanya kausa `disetujui` yang tampil di Landing Page publik
- [x] **E4** — Test transparansi publik
  - [x] Hanya laporan yang sudah dipublikasikan admin yang tampil di `/transparansi`
  - [x] Laporan berstatus `draft` tidak bocor ke halaman publik
- [x] **E5** — Update `docs/09-Traceability-Matrix.md`
  - [x] Tambah baris untuk NOT-1~4, PAY-1~5, DON-4 (webhook)
  - [x] Tandai setiap baris dengan status implementasi setelah sprint selesai
