# Implementation Plan - TAHAP 2: Perbaikan Role Instansi / OPD (Pengaju Kausa)

Implementasi lengkap TAHAP 2 dari TASKS.md pada Portal Pendanaan Sosial Pemkab Tulungagung: Alur Revisi Kausa (Resubmit Revision), Modul Laporan Penggunaan Dana (LPJ Instansi), Halaman Panduan SPJ & Kuitansi Resmi Pemkab Tulungagung, serta Feature Tests dan standarisasi kode.

## Rationale & Keputusan Arsitektur

1. **Alur Revisi Kausa (Resubmit Revision):**
   - Route `GET /dashboard/instansi/{kausa}/edit` dan `PUT/PATCH /dashboard/instansi/{kausa}` didaftarkan di dalam grup `peran:institution_user` dengan route constraint `whereNumber('kausa')` di atas route parameter generik untuk mencegah tabrakan rute.
   - Hanya kausa berstatus `draf` atau `perlu_diperbaiki` yang boleh diedit oleh instansi pemilik kausa (`kausa.instansi_id === auth()->user()->instansi->id`). Kausa dengan status `menunggu_verifikasi`, `disetujui`, atau `ditolak` dilindungi dengan `abort(403)`.
   - Pada halaman edit, kotak feedback resmi dari verifikator Pemkab ditampilkan secara menonjol memuat `kausa.catatan_admin` dan riwayat catatan terakhir dari `riwayat_status_kausa`.
   - Aksi "Ajukan Kembali (Resubmit)" memperbarui data kausa/dokumen, mengubah status menjadi `menunggu_verifikasi`, mencatat log audit riwayat di tabel `riwayat_status_kausa` (actor `user_id`, `status_sebelumnya`, `status_baru`, dan `catatan`), serta mengirimkan `Notifikasi` ke Admin Pemkab.

2. **Modul Laporan Penggunaan Dana (LPJ Instansi):**
   - Menggunakan controller `App\Http\Controllers\Dashboard\InstansiLaporanDanaController` untuk mengelola siklus hidup LPJ instansi (`index`, `create`, `store`, `show`, `edit`, `update`).
   - Tabel `laporan_dana` dan `rincian_laporan_dana` yang sudah tersedia di database dimanfaatkan sepenuhnya. Input rincian pengeluaran (uraian, nominal, tanggal, penerima manfaat, upload berkas bukti nota/kuitansi/BAST, keterangan) dikelola menggunakan tabel dinamis Alpine.js dengan kalkulasi real-time subtotal `total_digunakan`.
   - Penyimpanan laporan dan rincian belanja dibungkus dalam `DB::transaction()` untuk menjamin integritas data secara atomik. Status awal dapat berupa `draf` atau `menunggu_verifikasi`. Ketika dikirim untuk verifikasi, notifikasi otomatis dibuat untuk Admin Pemkab.

3. **Halaman Panduan SPJ & Kuitansi:**
   - Menghidupkan placeholder `resources/views/dashboard/instansi/panduan.blade.php` dengan pedoman resmi Pemkab Tulungagung: dasar hukum akuntabilitas bansos/hibah, format kuitansi dinas sah & ketentuan bea meterai UU No. 10 Tahun 2020, format Berita Acara Serah Terima (BAST) untuk penerima manfaat, batasan operasional (maksimal 10%) dan larangan belanja non-relevan, checklist interaktif kelengkapan berkas SPJ, serta unduhan format template dokumen dinas.

4. **Testing, Standar Otorisasi, & Kepatuhan Proyek:**
   - Feature tests komprehensif ditulis di `tests/Feature/InstansiKausaRevisiTest.php` dan `tests/Feature/InstansiLaporanDanaTest.php` mencakup positive cases, ownership checks, status constraints, file upload handling, transaction integrity, dan notification dispatch.
   - Format kode diverifikasi dengan `vendor/bin/pint --dirty --format agent`, dan checklist `TASKS.md` serta `docs/09-Traceability-Matrix.md` diperbarui.

---

# Implementation Plan

- [ ] 1. Harmonize model relations and configure routing for Kausa Revision and LPJ.
      Update `LaporanDana` and `RincianLaporanDana` models with clean casts and relation helpers, and register all required routes (`/dashboard/instansi/{kausa}/edit`, `/dashboard/instansi/{kausa}`, `/dashboard/instansi/laporan*`, and `/dashboard/instansi/panduan`) in `routes/web.php` with proper numeric constraints.
      Files: app/Models/LaporanDana.php, app/Models/RincianLaporanDana.php, routes/web.php
      Verify: `php artisan route:list --name=instansi` displays all newly registered routes without route collisions or syntax errors.

- [ ] 2. Create UpdateKausaRequest Form Request for Kausa revision and resubmission.
      Implement validation rules for kausa update including ownership authorization check, status validation (`draf` or `perlu_diperbaiki`), numeric target dana sanitization, document uploads, and optional revision note.
      Files: app/Http/Requests/UpdateKausaRequest.php
      Verify: `php artisan tinker --execute "new \App\Http\Requests\UpdateKausaRequest();"` executes cleanly without syntax errors.

- [ ] 3. Implement edit and update methods in InstansiDashboardController.
      Add `edit(Kausa $kausa)` to authorize and load kausa with admin notes and documents, `update(UpdateKausaRequest $request, Kausa $kausa)` to handle draft saving or resubmission (transitioning status to `menunggu_verifikasi`, creating `RiwayatStatusKausa`, and notifying Admin), and `panduan()` to serve the guidelines view.
      Files: app/Http/Controllers/Dashboard/InstansiDashboardController.php
      Verify: `php artisan route:list --name=dashboard.instansi` runs successfully and controller methods are bound correctly.

- [ ] 4. Create Blade view for Kausa revision (dashboard/instansi/edit.blade.php) and update navigation links.
      Create the full edit view featuring the official Admin feedback box (`catatan_admin` and latest `riwayat_status_kausa`), pre-filled fields, document manager with delete/upload support, and draft/resubmit actions; update edit buttons in `dashboard/instansi/detail.blade.php` and `dashboard/instansi/index.blade.php`.
      Files: resources/views/dashboard/instansi/edit.blade.php, resources/views/dashboard/instansi/detail.blade.php, resources/views/dashboard/instansi/index.blade.php
      Verify: `php artisan view:cache` compiles all Blade views successfully, followed by `php artisan view:clear`.

- [ ] 5. Create SimpanLaporanDanaRequest Form Request for LPJ and expense items.
      Implement authorization and validation for campaign ownership, report metadata (title, summary, date range, action), and itemized expenses (`rincian` array with uraian, nominal, tanggal, penerima manfaat, bukti file receipt, keterangan).
      Files: app/Http/Requests/SimpanLaporanDanaRequest.php
      Verify: `php artisan tinker --execute "new \App\Http\Requests\SimpanLaporanDanaRequest();"` runs without syntax errors.

- [ ] 6. Implement InstansiLaporanDanaController for full LPJ lifecycle.
      Create controller handling LPJ index (with campaign needing LPJ list and KPI metrics), create form, store in `DB::transaction()` (calculating `total_digunakan`, saving `rincian_laporan_dana`, uploading receipt files, and notifying Admin on submit), show detail, edit, and update.
      Files: app/Http/Controllers/Dashboard/InstansiLaporanDanaController.php
      Verify: `php artisan route:list --name=instansi.laporan` displays index, create, store, show, edit, and update routes without errors.

- [ ] 7. Build Blade views for Modul Laporan Penggunaan Dana (LPJ).
      Implement `resources/views/dashboard/instansi/laporan.blade.php` (KPI summary cards, campaigns needing LPJ list, and LPJ history table with status badges), `laporan-create.blade.php` (interactive dynamic expense rows with Alpine.js real-time subtotal calculation and receipt file upload), and `laporan-detail.blade.php` (complete itemized review, attached proofs, and admin feedback notes).
      Files: resources/views/dashboard/instansi/laporan.blade.php, resources/views/dashboard/instansi/laporan-create.blade.php, resources/views/dashboard/instansi/laporan-detail.blade.php
      Verify: `php artisan view:cache` compiles all views without errors, followed by `php artisan view:clear`.

- [ ] 8. Implement Panduan SPJ & Kuitansi view (resources/views/dashboard/instansi/panduan.blade.php).
      Replace placeholder with comprehensive official Pemkab Tulungagung SPJ guidelines, legal basis, receipt standards and stamp duty rules, BAST guidelines for beneficiary handovers, 10% operational budget cap, interactive document checklist, and downloadable templates.
      Files: resources/views/dashboard/instansi/panduan.blade.php
      Verify: `php artisan view:cache` compiles the panduan view cleanly, followed by `php artisan view:clear`.

- [ ] 9. Write Feature Tests for Kausa Revision in tests/Feature/InstansiKausaRevisiTest.php.
      Write comprehensive test cases covering edit view access for `draf` and `perlu_diperbaiki`, display of admin revision feedback, 403 forbidden for invalid statuses and unauthorized instansi, resubmission status transition to `menunggu_verifikasi`, logging in `riwayat_status_kausa`, notification dispatch to admin, and draft preservation.
      Files: tests/Feature/InstansiKausaRevisiTest.php
      Verify: `php artisan test --compact --filter=InstansiKausaRevisiTest` passes all assertions.

- [ ] 10. Write Feature Tests for Modul LPJ in tests/Feature/InstansiLaporanDanaTest.php.
      Write feature tests covering LPJ index and create page access, draft saving and verification submission with dynamic expense breakdown in DB transaction, automatic `total_digunakan` computation, receipt file upload handling, validation error handling, cross-instansi ownership isolation, and LPJ detail view.
      Files: tests/Feature/InstansiLaporanDanaTest.php
      Verify: `php artisan test --compact --filter=InstansiLaporanDanaTest` passes all assertions.

- [ ] 11. Format codebase with Laravel Pint and update task checklists.
      Run Laravel Pint to format all created and modified PHP files, update checklist in `TASKS.md` for all TAHAP 2 items, update `docs/09-Traceability-Matrix.md`, and run the test suite to guarantee zero regressions.
      Files: TASKS.md, docs/09-Traceability-Matrix.md
      Verify: `vendor/bin/pint --dirty --format agent` succeeds with exit code 0, and `php artisan test --compact --filter=Instansi` passes all tests.
