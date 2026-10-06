<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines — Portal Pendanaan Sosial Pemkab Tulungagung

## Project Context

Kamu sedang mengerjakan **Portal Pendanaan Sosial Pemkab Tulungagung** — platform web yang memfasilitasi pengajuan kausa sosial oleh instansi/OPD, verifikasi oleh Admin Pemkab, donasi masyarakat, dan pelaporan transparansi dana.

### Stack

- **Framework:** Laravel 13, PHP 8.3+
- **Templating:** Blade + Tailwind CSS + Vite
- **Database target:** PostgreSQL via Supabase
- **ORM:** Eloquent
- **Auth:** Laravel authentication stack
- **Authorization:** Middleware, Gates, Policies
- **Testing:** Pest/PHPUnit

### Role Pengguna

- `admin` — Admin Pemkab (verifikasi, approval, reject)
- `institution_user` — Instansi/OPD (pengaju kausa)
- `donatur` — Masyarakat/Donatur

## Dokumen Wajib Dibaca Sebelum Coding

Sebelum membuat atau mengubah file apapun, baca dokumen berikut yang relevan dengan task:

| Dokumen | Kapan Dibaca |
|---|---|
| `ARCHITECTURE.md` | Selalu — peta teknis utama |
| `PRD-Portal-Pendanaan-Pemkab.md` | Selalu — requirement dan fitur |
| `TASKS.md` | Selalu — task aktif dan status |
| `docs/02-Business-Process.md` | Saat mengerjakan alur bisnis/workflow |
| `docs/03-Use-Case.md` | Saat membuat fitur baru |
| `docs/04-ERD-Database-Specification.md` | Saat membuat migration/model |
| `docs/05-DESIGN.md` | Saat membuat view/UI |
| `docs/08-Test-Plan.md` | Saat membuat test |
| `docs/10-Data-Dictionary.md` | Saat mendefinisikan field/kolom |
| `docs/11-Security-and-Privacy.md` | Saat mengerjakan auth/upload/akses data |
| `docs/14-Status-and-Workflow-Rules.md` | Saat mengerjakan transisi status kausa |
| `docs/09-Traceability-Matrix.md` | Saat menandai fitur selesai |

## Alur Request

```
Browser → Route → Middleware (auth, role, CSRF)
→ Form Request (validasi)
→ Controller (orkestrasi)
→ Action/Service (logika bisnis)
→ Policy/Gate (ownership/permission)
→ Eloquent Model
→ PostgreSQL/Supabase
→ Blade Response / Redirect
```

## Roadmap Implementasi (Urutan Prioritas)

Kerjakan sesuai urutan ini. Jangan skip tahap:

1. Konfigurasi environment dan struktur dasar
2. Migration users, instansi, tabel akses → **TASK AKTIF**
3. Authentication dan RBAC
4. Workflow pengajuan kausa dan verifikasi
5. Katalog publik dan detail kausa
6. Donasi dan adapter pembayaran simulasi
7. Laporan dana dan verifikasi laporan
8. Transparansi dan audit
9. Feature tests lengkap
10. Integrasi eksternal (hanya jika sudah resmi)

## Konvensi Penamaan

- Controller: `Admin/KausaController`, `Institution/PengajuanController`
- Action: `ApproveCampaignAction`, `SubmitPengajuanAction`
- Model: `Kausa`, `Instansi`, `Donasi`, `LaporanDana`
- Policy: `KausaPolicy`, `InstansiPolicy`
- Form Request: `StoreKausaRequest`, `UpdateInstansiRequest`
- View: `resources/views/admin/`, `resources/views/institution/`, `resources/views/public/`
- Nama class dan method: **Bahasa Inggris**
- Komentar dan commit: **Bahasa Indonesia diperbolehkan**

## Entitas Database Utama

```
users, instansi, dokumen_instansi,
kausa, dokumen_kausa, riwayat_status_kausa,
donasi, transaksi_pembayaran,
laporan_dana, rincian_laporan_dana,
log_transparansi, notifikasi, log_audit
```

Detail field → `docs/04-ERD-Database-Specification.md` dan `docs/10-Data-Dictionary.md`
Transisi status → `docs/14-Status-and-Workflow-Rules.md` (WAJIB diikuti, jangan terima transisi ilegal dari form)

## Integrasi Eksternal — Semua Planned/Simulation

Jangan mengimplementasikan sebagai fitur produksi aktif:

- Payment gateway/QRIS → **Simulation** (gunakan `FakePaymentGateway`)
- SSO/NIP kedinasan → **Planned**
- WhatsApp notifikasi → **Planned**
- SMTP resmi → **Planned**
- BSrE → **Planned**
- Rekening pemerintah/escrow → **Planned**

Gunakan interface/adapter: `PaymentGatewayInterface` dengan implementasi `FakePaymentGateway` untuk development/test.

## Aturan Keamanan

- Validasi input SELALU dengan Form Request
- Gunakan Policy/Gate untuk setiap ownership dan permission check
- Validasi MIME type, ukuran, dan akses file upload
- Jangan tampilkan data pribadi atau dokumen privat ke publik
- Gunakan database transaction untuk perubahan dana dan status
- Jangan simpan credential di source code atau repository

## Definition of Done — Satu Modul Selesai Jika

- [ ] Kebutuhan PRD dan Use Case terpetakan
- [ ] Migration dan constraint database tersedia
- [ ] Model dan relasi dibuat
- [ ] Validation dan authorization diterapkan
- [ ] Route, controller/action, dan view tersedia
- [ ] Loading, empty state, validation error, dan server error ditangani
- [ ] Unit/feature test relevan lulus
- [ ] Audit/notifikasi dibuat jika diwajibkan workflow
- [ ] Tidak ada credential di repository
- [ ] `docs/09-Traceability-Matrix.md` diperbarui

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost adalah MCP server dengan tools khusus untuk aplikasi ini.
- Gunakan `database-query` untuk query read-only ke database.
- Gunakan `database-schema` untuk inspeksi struktur tabel sebelum membuat migration/model.
- Gunakan `get-absolute-url` untuk resolve URL yang benar.
- Gunakan `browser-logs` untuk membaca error browser.

## Searching Documentation

- Gunakan `search-docs` sebelum mengubah sesuatu yang bergantung pada Laravel ecosystem API.
- Gunakan query yang broad dan topic-based: `['rate limiting', 'routing rate limiting']`.
- Jangan tambahkan nama package ke query.

## Project Rules

- Cek `.ai/rules/index.md` jika direktori `.ai/rules` ada.
- Baca setiap rule file yang glob-nya mencakup path yang sedang dikerjakan.
- Jalankan `grep -rin 'keyword' .ai/rules` untuk tangkap aturan yang tidak tercakup glob.
- Catat rule baru hanya jika user **secara eksplisit** meminta dengan `record-rule`.

## Artisan

- Gunakan `php artisan make:` untuk membuat file baru.
- Jalankan `php artisan list` untuk discovery command.
- Gunakan `--no-interaction` di semua Artisan command.
- Inspeksi route: `php artisan route:list --except-vendor`.

## Tinker

- Gunakan single quote: `php artisan tinker --execute 'Your::code();'`
- Jangan buat model tanpa persetujuan user di tinker; gunakan factory di test.

=== php rules ===

# PHP

- Selalu gunakan curly braces untuk control structures.
- Gunakan PHP 8 constructor property promotion.
- Gunakan explicit return type dan type hints semua parameter.
- Gunakan TitleCase untuk Enum keys.
- Prefer PHPDoc blocks daripada inline comments.
- Gunakan array shape type definitions di PHPDoc.

=== tests rules ===

# Test

- Tambah atau update test untuk setiap perubahan behavior dan logika.
- Pure copy, styling, dan layout-only tidak wajib test.
- Jalankan test yang terpengaruh dan pastikan lulus sebelum selesai.
- Baca `testing-best-practices` skill sebelum menulis test.

=== laravel/core rules ===

# Do Things the Laravel Way

- Gunakan `php artisan make:` untuk semua file baru.
- Gunakan `php artisan make:class` untuk generic PHP class.
- Saat buat model baru, buat factory dan seeder juga.
- Untuk API, gunakan Eloquent API Resources dan API versioning.
- Prefer named route dan fungsi `route()` untuk generate URL.
- Gunakan factory dengan custom states untuk test, bukan setup manual.

=== pint/core rules ===

# Laravel Pint

- Setelah modifikasi file PHP apapun, jalankan: `vendor/bin/pint --dirty --format agent`
- Jangan jalankan `--test`, langsung jalankan untuk fix formatting.

=== phpunit/core rules ===

# PHPUnit

- Proyek ini menggunakan Pest/PHPUnit.
- Buat test dengan `php artisan make:test --phpunit {name}`.
- Jalankan test tersempit yang mencakup perubahan: `php artisan test --compact --filter=testName`.

</laravel-boost-guidelines>