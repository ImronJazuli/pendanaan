# Implementation Plan - Gap Completion Sprint A hingga E (TASKS.md)

Implementasi menyeluruh untuk menyelesaikan Sprint A hingga E (Gap Completion) dari TASKS.md pada Portal Pendanaan Sosial Pemkab Tulungagung (Laravel 13 + PostgreSQL):
- SPRINT A: Notifikasi In-App (NOT-1 ~ NOT-4)
- SPRINT C: Midtrans Webhook Listener (DON-4)
- SPRINT B: Verifikasi Pembayaran Manual (PAY-1 ~ PAY-5)
- SPRINT D: Audit & Perbaikan Tab Kausa + Galeri Foto (DON-5~7, AJU-3, PUB-9)
- SPRINT E: Melengkapi Test Suite & Traceability (docs/08-Test-Plan.md)

## Rationale & Keputusan Arsitektur

1. **Sprint A — Notifikasi In-App:**
   - Dibuat `App\Services\NotifikasiService` dengan method statis `kirim(User|int $user, string $jenis, string $judul, string $isi, ?string $tautan = null): void` untuk memastikan pemanggilan seragam dan konsisten dari seluruh controller maupun listener.
   - Dibuat `NotifikasiController` (`index`, `markRead`, `markAllRead`) dan routes web di bawah middleware `auth`.
   - Disisipkan pemanggilan `NotifikasiService::kirim()` ke seluruh titik perubahan status: persetujuan/penolakan/permintaan revisi kausa (`AdminDashboardController`), persetujuan/penolakan/permintaan revisi LPJ (`AdminDashboardController`), dan donasi berhasil (`DonasiController@simulate`).
   - Navbar bell badge dihubungkan ke count unread notifikasi user di `resources/views/layouts/dashboard-instansi.blade.php` dan `resources/views/layouts/navigation.blade.php` (digunakan oleh dashboard donatur); sementara layout admin tetap menampilkan count 0 karena tabel admin terpisah dari tabel users.

2. **Sprint C — Midtrans Webhook Listener:**
   - Dibuat route `POST /webhook/midtrans` yang dikecualikan dari proteksi CSRF di `bootstrap/app.php` (`validateCsrfTokens(except: ['webhook/midtrans'])`).
   - `MidtransWebhookController` memverifikasi signature SHA512 (`hash('sha512', $orderId . $statusCode . $grossAmount . config('services.midtrans.server_key'))`) dan mengembalikan HTTP 403 jika tidak cocok.
   - Pengecekan idempotensi menjamin jika status donasi sudah `success` atau `berhasil`, webhook langsung mengembalikan HTTP 200 tanpa menambah dana berulang kali.
   - Pada status `settlement` atau `capture` (accept), sistem memperbarui status donasi ke `Donasi::STATUS_SUCCESS`, mengisi `dibayar_pada`, menambah `dana_terkumpul` kausa secara atomik di dalam `DB::transaction()`, dan mengirim notifikasi in-app via `NotifikasiService`.

3. **Sprint B — Verifikasi Pembayaran Manual:**
   - Dibuat migration penambahan kolom `path_bukti_manual` (nullable string) dan `catatan_verifikasi_manual` (nullable text) pada tabel `donasi`.
   - Form checkout di `resources/views/kausa/show.blade.php` ditambahkan pilihan metode transfer manual dengan informasi rekening penampungan resmi Kasda Pemkab Tulungagung (Bank Jatim).
   - Halaman instruksi pembayaran `resources/views/donasi/payment.blade.php` dilengkapi form upload bukti transfer ke endpoint `POST /donasi/{kode}/upload-bukti` (validasi MIME jpg/png/pdf, max 2MB, simpan ke storage public `bukti-manual`, ubah status ke `menunggu_verifikasi_manual`).
   - Admin donasi controller dan view `resources/views/dashboard/admin/donasi.blade.php` ditambahkan tab verifikasi manual dengan modal preview bukti, tombol approve (`POST /dashboard/admin/donasi/{donasi}/approve-manual`), dan tombol reject dengan catatan wajib (`POST /dashboard/admin/donasi/{donasi}/reject-manual`).

4. **Sprint D — Audit & Perbaikan Tab Kausa + Galeri Foto:**
   - Tab Transparansi di `resources/views/kausa/show.blade.php` dihubungkan ke data riil `LogTransparansi` yang telah dipublikasikan (`dipublikasikan = true`), bukan data dummy/hardcoded.
   - Tab Riwayat Donatur di `resources/views/kausa/show.blade.php` menampilkan donasi sukses (`status = success|berhasil`), menyamarkan identitas donatur anonim menjadi "Hamba Allah", dan menampilkan doa dukungan secara rapi.
   - Form create kausa diizinkan mengunggah multiple foto galeri (`foto_kausa[]`), yang disimpan ke storage public dan dicatat di `dokumen_kausa` dengan `jenis_dokumen = 'foto_galeri'`.
   - Section galeri foto di `resources/views/kausa/show.blade.php` menampilkan foto dokumentasi dari `dokumen_kausa` dengan fallback placeholder yang elegan.

5. **Sprint E — Test Suite Lengkap & Traceability:**
   - Dibuat `App\Policies\KausaPolicy` dan didaftarkan untuk verifikasi kepemilikan kausa antar-instansi.
   - Ditulis feature tests spesifik: `KausaPolicyTest`, `RoleAccessTest`, `KausaWorkflowStatusTest`, dan `TransparansiPublikTest`.
   - Diperbarui `docs/09-Traceability-Matrix.md` dan `TASKS.md` untuk menandai semua item selesai `[x]`, serta formatting kode dengan Laravel Pint.

---

# Implementation Plan

- [x] 1. SPRINT A: Create NotifikasiService in app/Services/NotifikasiService.php.
      Implement static method `kirim(User|int $user, string $jenis, string $judul, string $isi, ?string $tautan = null): void` that saves records to `notifikasi` table.
      Files: app/Services/NotifikasiService.php
      Verify: `php artisan tinker --execute "\App\Services\NotifikasiService::class;"` runs with exit code 0.

- [x] 2. SPRINT A: Create NotifikasiController in app/Http/Controllers/NotifikasiController.php and register routes in routes/web.php.
      Implement `index()` with pagination, `markRead(Notifikasi $notifikasi)` ensuring user ownership, and `markAllRead()`; register `GET /notifikasi`, `POST /notifikasi/{notifikasi}/read`, and `POST /notifikasi/read-all` under `auth` middleware.
      Files: app/Http/Controllers/NotifikasiController.php, routes/web.php
      Verify: `php artisan route:list --name=notifikasi` lists all 3 routes correctly.

- [x] 3. SPRINT A: Integrate NotifikasiService at status transition points in AdminDashboardController and DonasiController.
      Call `NotifikasiService::kirim()` on kausa approval, rejection, and revision; on LPJ approval, rejection, and revision in `AdminDashboardController`; and on donation success in `DonasiController@simulate`.
      Files: app/Http/Controllers/Dashboard/AdminDashboardController.php, app/Http/Controllers/DonasiController.php
      Verify: `php artisan test --compact --filter=AdminManagementTest` and `php artisan test --compact --filter=DonasiSimulasiTest` pass.

- [x] 4. SPRINT A: Create notification view resources/views/notifikasi/index.blade.php and update navbar bell badges.
      Build the full notification list page with read/unread visual distinction, mark-all-read action, link redirects, and empty state; update unread counter badges in `layouts/dashboard-instansi.blade.php` and `layouts/navigation.blade.php`.
      Files: resources/views/notifikasi/index.blade.php, resources/views/layouts/dashboard-instansi.blade.php, resources/views/layouts/navigation.blade.php
      Verify: `php artisan view:cache` compiles all Blade views successfully, followed by `php artisan view:clear`.

- [x] 5. SPRINT A: Write feature test in tests/Feature/NotifikasiTest.php.
      Test notification creation on kausa status change, LPJ status change, donation success, mark as read, mark all as read, and 403 unauthorized access when accessing other users' notifications.
      Files: tests/Feature/NotifikasiTest.php
      Verify: `php artisan test --compact --filter=NotifikasiTest` passes all assertions.

- [x] 6. SPRINT C: Configure CSRF exemption and Midtrans config in config/services.php and bootstrap/app.php.
      Add `services.midtrans` configuration array (`server_key`, `client_key`, `is_production`) and exclude `webhook/midtrans` from CSRF verification in `bootstrap/app.php`.
      Files: config/services.php, bootstrap/app.php
      Verify: `php artisan tinker --execute "config('services.midtrans.server_key');"` outputs the configured key without error.

- [x] 7. SPRINT C: Create MidtransWebhookController in app/Http/Controllers/MidtransWebhookController.php and register route in routes/web.php.
      Handle `POST /webhook/midtrans` with SHA512 signature validation (`order_id + status_code + gross_amount + ServerKey`), idempotency checking, DB transaction for settlement/capture fund increment and notification, and handling for cancel/expire/deny.
      Files: app/Http/Controllers/MidtransWebhookController.php, routes/web.php
      Verify: `php artisan route:list --name=webhook.midtrans` displays the route.

- [x] 8. SPRINT C: Write feature test in tests/Feature/WebhookMidtransTest.php.
      Test valid signature updates donation to success and increments funds, invalid signature returns 403, duplicate webhook call returns 200 without double increment, and expired status updates donation to expired.
      Files: tests/Feature/WebhookMidtransTest.php
      Verify: `php artisan test --compact --filter=WebhookMidtransTest` passes all tests.

- [x] 9. SPRINT B: Create migration for manual payment proof in donasi table and update Donasi model.
      Add `path_bukti_manual` and `catatan_verifikasi_manual` to `donasi` table, run migration, and update `Donasi` model fillable, status constants (`STATUS_MENUNGGU_VERIFIKASI_MANUAL`, `STATUS_DITOLAK_MANUAL`), and `scopeSuccess`.
      Files: database/migrations/2026_10_08_000001_add_bukti_manual_to_donasi_table.php, app/Models/Donasi.php
      Verify: `php artisan migrate` executes successfully and columns exist on `donasi` table.

- [x] 10. SPRINT B: Implement manual payment upload in DonasiController and views.
      Add manual payment option with Pemkab Tulungagung bank account in `kausa/show.blade.php`; add upload proof form in `donasi/payment.blade.php`; implement `uploadBukti()` method in `DonasiController` with file validation (jpg/png/pdf, max 2MB) and status update to `menunggu_verifikasi_manual`; register route `POST /donasi/{kode}/upload-bukti` in `routes/web.php`.
      Files: app/Http/Controllers/DonasiController.php, resources/views/kausa/show.blade.php, resources/views/donasi/payment.blade.php, routes/web.php
      Verify: `php artisan route:list --name=donasi.uploadBukti` displays the route.

- [x] 11. SPRINT B: Implement Admin manual verification actions and dashboard views.
      Implement `approveManual()` (DB transaction, atomic fund increment, notification) and `rejectManual()` (mandatory note, notification) in `AdminDashboardController`; register routes `POST /dashboard/admin/donasi/{donasi}/approve-manual` and `POST /dashboard/admin/donasi/{donasi}/reject-manual`; update `resources/views/dashboard/admin/donasi.blade.php` with manual verification queue, proof preview modal, and action forms.
      Files: app/Http/Controllers/Dashboard/AdminDashboardController.php, resources/views/dashboard/admin/donasi.blade.php, routes/web.php
      Verify: `php artisan route:list --name=admin.donasi` shows the new approve and reject routes.

- [x] 12. SPRINT B: Write feature test in tests/Feature/PembayaranManualTest.php.
      Test file upload updates status to `menunggu_verifikasi_manual`, admin approval increments campaign funds and sends notification, duplicate approval is idempotent, rejection requires note and sets `ditolak_manual`, and non-admin cannot access verification endpoints.
      Files: tests/Feature/PembayaranManualTest.php
      Verify: `php artisan test --compact --filter=PembayaranManualTest` passes all assertions.

- [x] 13. SPRINT D: Update KausaController and requests to support multiple gallery photos upload.
      Update `SimpanKausaRequest` to validate `foto_kausa` images, update `KausaController@store` to store uploaded images in storage public and record to `dokumen_kausa` with `jenis_dokumen = 'foto_galeri'`.
      Files: app/Http/Requests/SimpanKausaRequest.php, app/Http/Controllers/KausaController.php
      Verify: `php artisan tinker --execute "new \App\Http\Requests\SimpanKausaRequest();"` runs cleanly.

- [x] 14. SPRINT D: Connect real data to Transparency Tab, Donor History Tab, and Gallery in kausa/show.blade.php.
      Update `KausaController@show` to eager-load published `logTransparansi`, successful `donasi`, and `dokumen`; in `resources/views/kausa/show.blade.php`, render real published `log_transparansi` records in Tab 3, real successful donations with masked "Hamba Allah" for anonymous donors in Tab 4, and real gallery photos from `dokumen_kausa` with fallback.
      Files: app/Http/Controllers/KausaController.php, resources/views/kausa/show.blade.php
      Verify: `php artisan view:cache` compiles successfully, followed by `php artisan view:clear`.

- [x] 15. SPRINT E: Create KausaPolicy and register in AuthServiceProvider or AppServiceProvider.
      Create `app/Policies/KausaPolicy.php` enforcing ownership checks (`instansi_id`) and status constraints (can only edit/update when `draf` or `perlu_diperbaiki`, delete when `draf`).
      Files: app/Policies/KausaPolicy.php
      Verify: `php artisan tinker --execute "new \App\Policies\KausaPolicy();"` runs cleanly.

- [x] 16. SPRINT E: Write comprehensive policy and RBAC feature tests in tests/Feature/KausaPolicyTest.php and tests/Feature/RoleAccessTest.php.
      In `KausaPolicyTest`, test institution A cannot edit or delete campaign of institution B, but can edit its own campaign in draft/revision status. In `RoleAccessTest`, test donor cannot access admin routes (403), institution cannot access admin routes (403), admin cannot access institution routes (403/redirect), and unauthenticated guests are redirected to login.
      Files: tests/Feature/KausaPolicyTest.php, tests/Feature/RoleAccessTest.php
      Verify: `php artisan test --compact --filter="KausaPolicyTest|RoleAccessTest"` passes all assertions.

- [x] 17. SPRINT E: Write workflow status and public transparency feature tests in tests/Feature/KausaWorkflowStatusTest.php and tests/Feature/TransparansiPublikTest.php.
      In `KausaWorkflowStatusTest`, verify rejected campaign cannot directly become approved without resubmission, and only approved campaigns appear in public catalog and landing page. In `TransparansiPublikTest`, verify only published reports appear on public `/transparansi` and draft reports remain private.
      Files: tests/Feature/KausaWorkflowStatusTest.php, tests/Feature/TransparansiPublikTest.php
      Verify: `php artisan test --compact --filter="KausaWorkflowStatusTest|TransparansiPublikTest"` passes all assertions.

- [x] 18. SPRINT E: Update Traceability Matrix, TASKS.md checklist, and format codebase with Pint.
      Update `docs/09-Traceability-Matrix.md` adding rows for NOT-1~4, PAY-1~5, DON-4, DON-5~7, AJU-3, and PUB-9; mark all completed tasks `[x]` in `TASKS.md`; format all modified PHP files with Laravel Pint; run entire test suite to ensure 100% green tests.
      Files: docs/09-Traceability-Matrix.md, TASKS.md
      Verify: `vendor/bin/pint --dirty --format agent` succeeds and `php artisan test` runs all tests with 0 failures.
