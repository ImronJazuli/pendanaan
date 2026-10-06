# ARCHITECTURE.md

## 1. Tujuan dan Fungsi Dokumen

`ARCHITECTURE.md` adalah peta teknis proyek **Portal Pendanaan Sosial Pemkab Tulungagung**. Dokumen ini menerjemahkan PRD menjadi struktur implementasi yang dapat dikerjakan bertahap oleh developer maupun coding agent.

Dokumen ini menjelaskan:

- teknologi dan infrastruktur yang digunakan;
- batas modul dan tanggung jawabnya;
- struktur direktori proyek;
- alur request dan data;
- aturan dependency antar-modul;
- workflow status;
- integrasi eksternal;
- Definition of Done setiap modul.

Dokumen ini menjawab **bagaimana sistem dibangun**, sedangkan PRD menjawab **apa yang harus dibangun**.

## 2. Prinsip Arsitektur

- Gunakan struktur dan konvensi Laravel sebelum membuat abstraksi tambahan.
- Pisahkan validasi, authorization, logika bisnis, akses data, dan presentasi.
- Controller mengorkestrasi request; logika bisnis kompleks berada di Action/Service.
- Semua perubahan database dilakukan melalui migration.
- Setiap fitur harus memiliki acceptance criteria dan test.
- Integrasi yang belum tersedia tidak boleh dianggap sebagai fitur produksi.
- Perubahan status penting harus memiliki history atau audit log.
- Modul hanya boleh bergantung pada kontrak yang stabil, bukan implementasi detail modul lain.

## 3. Tech Stack Overview

| Layer | Teknologi | Tujuan |
|---|---|---|
| Framework | Laravel 13 | Backend, routing, middleware, validation, queue, dan struktur aplikasi |
| Bahasa | PHP 8.3+ | Implementasi backend dan domain logic |
| Templating | Blade | Server-rendered UI yang terintegrasi dengan Laravel |
| CSS | Tailwind CSS | Styling konsisten dan responsive |
| Build tool | Vite | Bundling asset CSS dan JavaScript |
| Database | PostgreSQL melalui Supabase | Penyimpanan data aplikasi target |
| ORM | Eloquent | Model, relasi, query, dan database abstraction |
| Authentication | Laravel authentication stack | Login, session, password, dan identity |
| Authorization | Middleware, Gates, dan Policies | Pembatasan akses berdasarkan role dan ownership |
| Storage | Laravel Filesystem | Penyimpanan dokumen publik atau privat |
| Testing | Pest/PHPUnit | Unit test, feature test, dan integrasi |
| Queue/Cache | Laravel Queue/Cache | Notifikasi dan pekerjaan asinkron jika diperlukan |
| Deployment | PHP web server + PostgreSQL/Supabase | Menjalankan aplikasi sesuai environment |

Teknologi tambahan hanya boleh ditambahkan jika ada kebutuhan yang terdokumentasi dan tidak bertentangan dengan PRD.

## 4. Modul Aplikasi

### 4.1 Identity and RBAC

Mengelola autentikasi, user, role, session, dan authorization.

Role teknis:

- `admin` — Admin Pemkab;
- `institution_user` — Instansi/OPD Pengaju Kausa;
- `donatur` — Masyarakat/Donatur.

### 4.2 Public Catalog

Menampilkan kausa yang telah disetujui, detail kausa, progres dana, dan laporan transparansi yang telah dipublikasikan.

### 4.3 Institution Submission

Mengelola profil instansi, dokumen pendukung, pembuatan kausa, pengiriman pengajuan, status, dan perbaikan pengajuan.

### 4.4 Admin Verification

Mengelola pemeriksaan instansi, verifikasi kausa, permintaan perbaikan, penolakan, persetujuan, dan catatan pemeriksaan.

### 4.5 Donations and Payment

Mengelola pembuatan donasi, status transaksi, riwayat donatur, dan adapter pembayaran. Payment gateway/QRIS produksi tetap Planned atau Simulation sampai integrasi resmi tersedia.

### 4.6 Fund Reports and Transparency

Mengelola laporan penggunaan dana, item laporan, verifikasi Admin, publikasi, dan tampilan transparansi publik.

### 4.7 Notifications and Audit

Mengelola notifikasi perubahan status dan pencatatan tindakan penting pengguna maupun Admin.

## 5. Alur Request dan Data

```text
Browser
  ↓
Route
  ↓
Middleware: auth, role, CSRF
  ↓
Form Request: validasi input
  ↓
Controller: orkestrasi request/response
  ↓
Action atau Service: logika bisnis dan transaksi
  ↓
Policy/Gate: pemeriksaan ownership/permission
  ↓
Eloquent Model/Repository seperlunya
  ↓
PostgreSQL/Supabase
  ↓
Response Blade atau redirect dengan feedback
```

Untuk perubahan multi-tabel atau nominal dana, gunakan database transaction. Notifikasi dibuat setelah perubahan utama berhasil.

## 6. Struktur Direktori

```text
pendanaan/
├── AGENTS.md
├── ARCHITECTURE.md
├── README.md
├── PRD-Portal-Pendanaan-Pemkab.md
├── TASKS.md
├── app/
│   ├── Actions/
│   │   ├── Campaigns/
│   │   ├── Donations/
│   │   ├── FundReports/
│   │   └── Verification/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   ├── Institution/
│   │   │   ├── Donor/
│   │   │   └── Public/
│   │   ├── Requests/
│   │   └── Middleware/
│   ├── Models/
│   ├── Policies/
│   ├── Services/
│   │   ├── Payments/
│   │   ├── Notifications/
│   │   └── Storage/
│   └── Support/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── layouts/
│       ├── components/
│       ├── public/
│       ├── admin/
│       ├── institution/
│       └── donor/
├── routes/
│   ├── web.php
│   └── console.php
├── tests/
│   ├── Feature/
│   └── Unit/
└── docs/
```

Struktur folder boleh berkembang, tetapi lokasi dan tanggung jawab modul harus tetap jelas.

## 7. Database dan Domain Rules

Entitas utama:

```text
users
instansi
dokumen_instansi
kausa
dokumen_kausa
riwayat_status_kausa
donasi
transaksi_pembayaran
laporan_dana
rincian_laporan_dana
log_transparansi
notifikasi
log_audit
```

Detail field dan relasi berada di `docs/04-ERD-Database-Specification.md` dan `docs/10-Data-Dictionary.md`.

Status dan transisi wajib mengikuti `docs/14-Status-and-Workflow-Rules.md`; kode tidak boleh menerima transisi ilegal hanya karena nilai status dikirim dari form.

## 8. Integrasi Eksternal

Gunakan interface/adapter agar implementasi simulasi dapat diganti tanpa mengubah domain utama.

```text
PaymentGatewayInterface
├── FakePaymentGateway       # local/test
└── ProductionPaymentGateway # Planned/Sandbox sampai resmi
```

Status integrasi:

- SSO/NIP kedinasan: Planned;
- payment gateway/QRIS: Simulation atau Sandbox;
- WhatsApp: Planned;
- SMTP resmi: Planned;
- BSrE: Planned;
- rekening pemerintah/escrow: Planned.

Jangan menyimpan credential di source code dan jangan menyatakan integrasi aktif sebelum endpoint, callback, credential, dan pengujian resmi tersedia.

## 9. Keamanan dan Performa

- Validasi input dengan Form Request.
- Gunakan Policy/Gate untuk ownership dan permission.
- Validasi MIME type, ukuran, dan akses file upload.
- Jangan menampilkan data pribadi atau dokumen privat ke publik.
- Verifikasi signature callback dan pastikan idempotent.
- Gunakan eager loading dan pagination.
- Hindari query berulang dalam loop.
- Gunakan transaction untuk perubahan dana dan status penting.
- Session/cache/queue development tidak harus memakai database cloud.
- Ikuti `docs/11-Security-and-Privacy.md` dan `docs/12-Deployment-and-Environment.md`.

## 10. Roadmap Implementasi Modul

Urutan implementasi teknis:

1. konfigurasi environment dan struktur dasar;
2. users, role, authentication, dan authorization;
3. instansi dan dokumen instansi;
4. kausa dan workflow pengajuan;
5. verifikasi Admin dan notifikasi;
6. katalog publik dan detail kausa;
7. donasi dan adapter pembayaran simulasi;
8. laporan_dana dan verifikasi laporan;
9. transparency dan audit;
10. integrasi resmi jika persyaratannya tersedia;
11. hardening keamanan, performa, dan deployment.

Setiap tahap harus memperbarui `TASKS.md`, dokumentasi terkait, dan traceability matrix.

## 11. Definition of Done

Satu modul dianggap selesai jika:

- kebutuhan PRD dan Use Case sudah terpetakan;
- migration dan constraint database tersedia;
- model dan relasi sudah dibuat;
- validation dan authorization diterapkan;
- route, controller/action, dan view tersedia;
- loading, empty, validation error, dan server error ditangani;
- unit/feature test relevan lulus;
- audit/notifikasi dibuat jika diwajibkan workflow;
- tidak ada credential di repository;
- dokumentasi dan `docs/09-Traceability-Matrix.md` diperbarui;
- acceptance criteria terpenuhi.

## 12. Dokumen Referensi

- `PRD-Portal-Pendanaan-Pemkab.md`
- `AGENTS.md`
- `docs/02-Business-Process.md`
- `docs/03-Use-Case.md`
- `docs/04-ERD-Database-Specification.md`
- `docs/05-DESIGN.md`
- `docs/08-Test-Plan.md`
- `docs/10-Data-Dictionary.md`
- `docs/11-Security-and-Privacy.md`
- `docs/14-Status-and-Workflow-Rules.md`
