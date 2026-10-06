# Laporan Audit Visual & Fungsional
## Portal Pendanaan Sosial Pemkab Tulungagung

**Tanggal Audit:** 5 Oktober 2026  
**Auditor:** Kiro AI Assistant  
**Basis Perbandingan:** Mockup Stitch Snapshot `dana-e7fd9893031a`

---

## Ringkasan Eksekutif

- **Total frame mockup:** 10 (termasuk 2 placeholder kosong)
- **Frame substantif:** 8 (canvas 003-010)
- **Sudah diimplementasikan:** 5 halaman
- **Belum diimplementasikan:** 3 halaman
- **Parsial/meleset:** 4 halaman

### Status Implementasi Per Kategori

| Kategori | Jumlah | Status |
|----------|--------|--------|
| ✅ Sesuai mockup | 1 | Dashboard Admin |
| ⚠️ Parsial | 4 | Landing, Detail Kausa, Form Kausa, Dashboard Instansi |
| ❌ Belum ada | 3 | Profil Instansi, Modul Penyaluran, Verifikasi Pembayaran |

---

## Detail Per Halaman

### Frame 001 & 002: Web & Mobile Placeholder
**Status:** N/A (placeholder kosong, byteLength 713 = identik)

---

### Frame 003: Beranda & Katalog Kausa Terpadu
**Route:** `/` → `landing`  
**Status Visual:** ⚠️ Parsial (85% sesuai)  
**Status Fungsional:** ⚠️ Parsial (70% sesuai)

#### ✅ Yang Sudah Sesuai:
- Hero section dengan gradient hijau gelap
- Search bar dengan quick tags
- Impact metrics card (Total Dana, Kausa Terverifikasi, Instansi Mitra, Donatur Aktif)
- Grid katalog kausa dengan card design
- Filter kategori tab (Semua, Bencana, Panti, Ibadah, Lansia)
- Section "Alur Verifikasi 3 Langkah"
- Modal donasi dengan Alpine.js

#### ❌ Yang Meleset:

**Visual:**
- [ ] Mockup memiliki **Top Official Bar** dengan "Pembaruan Audit: 24 Menit Lalu" (timestamp dinamis) — implementasi statis "Akuntabilitas Terbuka • Audit Publik Real-Time"
- [ ] Filter kategori di beranda hanya dekoratif — tab Alpine.js tidak terhubung filter server-side (controller tidak mengirim `$kategoris` ke view)
- [ ] Footer mockup lebih lengkap dengan kolom-kolom terstruktur — perlu dicek `layouts/app.blade.php`

**Fungsional:**
- [ ] **Donasi modal tidak fungsional** — tidak ada route `POST /donasi` untuk menyimpan donasi
- [ ] Button "Donasi" di card landing membuka modal, tapi di `/kausa` (katalog) malah redirect ke `kausa.show#donasi` — inkonsistensi behavior
- [ ] Filter kategori di beranda tidak mengirim parameter ke backend — `KausaController@index` menerima `kategori_kausa_id` tapi `landing()` tidak pass `$kategoris`

**Prioritas Fix:** 🔴 Tinggi (modal donasi adalah core feature yang non-fungsional)

---

### Frame 004: Detail Kausa & Pusat Transparansi Penyaluran
**Route:** `/kausa/{slug}` → `kausa.show`  
**Status Visual:** ⚠️ Parsial (75% sesuai)  
**Status Fungsional:** ⚠️ Parsial (65% sesuai)

#### ✅ Yang Sudah Sesuai:
- Layout dua kolom (content kiri, sidebar donasi kanan)
- Tab navigasi Alpine.js (Deskripsi, Update Program, Alokasi Dana, Donatur)
- Progress bar target donasi
- Badge status kausa (Aktif, Selesai, dll)
- Sidebar donasi sticky dengan pilihan nominal (Rp 25rb, 50rb, 100rb, 250rb, dll)
- Load relasi `donasi`, `dokumen`, `laporanDana` dengan eager loading

#### ❌ Yang Meleset:

**Visual:**
- [ ] Mockup menampilkan **galeri foto** (foto utama + thumbnail pendukung) — implementasi hanya 1 foto via `$kausa->foto_url`
- [ ] Field `foto_url` **tidak ada di `$fillable` model Kausa** dan tidak ada di migration — selalu fallback/null
- [ ] Mockup menampilkan **daftar donatur publik** di tab Donatur (nama + nominal) — perlu verifikasi apakah view menampilkan `$kausa->donasi`

**Fungsional:**
- [ ] **Form donasi di sidebar tidak bisa submit** — tidak ada route `POST /kausa/{id}/donasi`
- [ ] `checkoutSimulate()` Alpine.js adalah simulasi — tidak terhubung backend
- [ ] Field `foto_url` digunakan di view tapi tidak ada di model — kemungkinan error atau selalu fallback
- [ ] Tidak ada fitur upload bukti transfer untuk donasi manual

**Prioritas Fix:** 🔴 Tinggi (form donasi tidak fungsional, `foto_url` missing di schema)

---

### Frame 005: Profil & Verifikasi Legalitas Lembaga
**Route:** `/dashboard/instansi/profil` → `instansi.profil`  
**Status Visual:** ❌ Belum (0%)  
**Status Fungsional:** ❌ Belum (0%)

#### Deskripsi Mockup:
Halaman profil instansi dengan:
- Sidebar navigasi dashboard instansi
- Form data lembaga (nama, jenis, alamat, nomor registrasi, penanggung jawab)
- Upload dokumen legalitas (SK Kemenkumham, NPWP, SK Kepengurusan, foto kantor)
- Status verifikasi per dokumen (pending/approved/rejected)
- Progress indicator "4 berkas legalitas dibutuhkan"

#### ❌ Yang Belum Ada:

**Backend:**
- [ ] Route `instansi.profil` sudah ada di `routes/web.php` ✅
- [ ] Controller `InstansiDashboardController@profil()` sudah ada ✅
- [ ] Controller `InstansiDashboardController@updateProfil()` sudah ada ✅
- [ ] Model `Instansi` ada tapi field `nama_pj` tidak di `$fillable` — view mengakses `$instansi->nama_pj` yang akan null
- [ ] Model `DokumenInstansi` sudah ada dengan field lengkap ✅

**Frontend:**
- [ ] View `dashboard/instansi/profil.blade.php` **BELUM ADA** ❌
- [ ] Form upload dokumen legalitas belum ada
- [ ] UI status verifikasi per dokumen belum ada

**Prioritas Fix:** 🔴 Tinggi (instansi tidak bisa upload dokumen legalitas yang dibutuhkan untuk mengajukan kausa — blocker critical path)

---

### Frame 006: Formulir Pengajuan Kausa Baru
**Route:** `/kausa/ajukan` → `kausa.create`  
**Status Visual:** ✅ Sesuai (90%)  
**Status Fungsional:** ⚠️ Parsial (60%)

#### ✅ Yang Sudah Sesuai:
- Multi-step form visual dengan 4 stepper
- Form fields lengkap (judul, deskripsi, kategori, target dana, tanggal, dokumen)
- Upload dokumen pendukung (multiple files)
- Revision banner (untuk status `perlu_diperbaiki`)
- Validasi dengan `SimpanKausaRequest`

#### ❌ Yang Meleset:

**Visual:**
- [ ] **Stepper JavaScript tidak fungsional** — button `step-nav-btn` tidak terhubung show/hide section form
- [ ] **Revision banner class `hidden` hardcoded** — tidak kondisional berdasarkan status kausa
- [ ] Preview gambar saat upload foto belum ada

**Fungsional:**
- [ ] **Tombol "Simpan Draf" di header** adalah `type="button"` tanpa handler — tidak bisa submit form sebagai draf
- [ ] **Tombol "Kirim Pengajuan"** berada **di luar tag `<form>`** — kemungkinan submit tidak berfungsi
- [ ] Validasi `foto` tidak ada di `SimpanKausaRequest` — field upload foto utama tidak divalidasi
- [ ] Redirect setelah store kembali ke `kausa.create` (route yang sama) alih-alih dashboard dengan flash message

**Prioritas Fix:** 🔴 Tinggi (form submit bermasalah, draf tidak bisa disimpan)

---

### Frame 007: Dashboard Instansi & Pelaporan Dana
**Route:** `/dashboard/instansi` → `dashboard.instansi`  
**Status Visual:** ✅ Sesuai (85%)  
**Status Fungsional:** ⚠️ Parsial (70%)

#### ✅ Yang Sudah Sesuai:
- Layout dengan sidebar gelap kiri + content area putih
- 4 metric card di header (Total, Menunggu, Disetujui, Draf/Revisi)
- Tabel kausa dengan kolom: judul, status, terkumpul, target, aksi
- Filter & sort dropdown
- Badge status berwarna (menunggu, disetujui, ditolak, perlu_diperbaiki)

#### ❌ Yang Meleset:

**Visual:**
- [ ] Mockup card ke-4 adalah "Dana Terkumpul Total" — implementasi menampilkan "Draf / Revisi"
- [ ] Mockup memiliki **sidebar navigasi lengkap** (Ringkasan, Profil, Program, Laporan) — implementasi hanya top-bar

**Fungsional:**
- [ ] Controller tidak menghitung total `dana_terkumpul` agregat — metric tidak dikirim ke view
- [ ] Filter `sort=tercanggih` kemungkinan typo — seharusnya `terbaru`/`terkini`
- [ ] `$instansi->nama_pj` diakses di view tapi field tidak ada di model — akan null
- [ ] **Tidak ada halaman "Laporan Dana"** — instansi tidak bisa mengisi laporan penggunaan dana (model `LaporanDana` ada tapi tidak ada UI/route)

**Prioritas Fix:** 🔴 Tinggi (laporan dana adalah fitur kritis untuk akuntabilitas)

---

### Frame 008: Dashboard Verifikator & Antrean Kausa
**Route:** `/dashboard/admin` → `dashboard.admin`  
**Status Visual:** ✅ Sesuai (95%)  
**Status Fungsional:** ⚠️ Parsial (80%)

#### ✅ Yang Sudah Sesuai:
- Dark topbar dengan branding Pemkab
- Desktop sidebar putih dengan menu navigasi
- 5 metric card (Total, Menunggu, Perlu Revisi, Disetujui, Ditolak)
- Tabel antrean verifikasi dengan semua kolom yang diperlukan
- Filter status dropdown
- Action button (Setujui, Tolak, Detail)

#### ❌ Yang Meleset:

**Visual:**
- [ ] Mockup memiliki **sidebar navigasi lengkap** dengan semua modul — implementasi ada tapi beberapa route belum dibuat (sudah difix ke `#` placeholder)
- [ ] Tabel menampilkan `$item->instansi->nama_pj` yang akan null (field tidak di model)

**Fungsional:**
- [ ] `AdminDashboardController@verify` tidak memvalidasi request apapun — form approval bisa dikirim tanpa catatan
- [ ] Status `perlu_diperbaiki` ada di filter, tapi **tidak ada route/action untuk set status ini** — admin tidak bisa kirim catatan revisi ke instansi
- [ ] Tidak ada route untuk modul **Penyaluran Dana** (frame 009) dan **Verifikasi Pembayaran Manual** (frame 010)

**Prioritas Fix:** 🔴 Tinggi (status `perlu_diperbaiki` tidak bisa diset dari UI — workflow terputus)

---

### Frame 009: Modul Manajemen Penyaluran Dana & Audit
**Route:** `/dashboard/admin/penyaluran` (belum ada)  
**Status Visual:** ❌ Belum (0%)  
**Status Fungsional:** ❌ Belum (0%)

#### Deskripsi Mockup:
Halaman admin untuk mengelola pencairan/penyaluran dana:
- Tabel laporan dana dari instansi
- Upload bukti fisik (kuitansi, BAST, foto serah terima)
- Rekonsiliasi total dana tersalur
- Status audit per laporan (pending/disetujui/dipublikasikan)
- Sidebar dengan menu verifikator lengkap

#### ❌ Yang Belum Ada:
- [ ] Tidak ada route `/dashboard/admin/penyaluran`
- [ ] Tidak ada controller untuk mengelola `LaporanDana` dari sisi admin
- [ ] Model `LaporanDana` dan `RincianLaporanDana` sudah ada ✅
- [ ] Tidak ada UI admin untuk review/approve laporan
- [ ] `TransparansiController` hanya menampilkan laporan published — tidak ada alur admin untuk publish

**Prioritas Fix:** 🔴 Tinggi (inti dari transparansi — dana harus bisa dilaporkan dan dipublikasikan)

---

### Frame 010: Verifikasi Pembayaran Manual
**Route:** `/dashboard/admin/verifikasi-pembayaran` (belum ada)  
**Status Visual:** ❌ Belum (0%)  
**Status Fungsional:** ❌ Belum (0%)

#### Deskripsi Mockup:
Halaman admin untuk memverifikasi bukti pembayaran manual:
- Tabel donasi dengan status "menunggu konfirmasi"
- Preview bukti transfer
- Tombol verifikasi/tolak
- Riwayat verifikasi
- Notifikasi badge "3 Baru" di header

#### ❌ Yang Belum Ada:
- [ ] Tidak ada route untuk verifikasi pembayaran manual
- [ ] Tidak ada controller untuk verify/reject donasi manual
- [ ] Model `TransaksiPembayaran` ada (`status`, `dibayar_pada`) ✅
- [ ] Model `Donasi` memiliki `metode_pembayaran` tapi tidak ada logika pembedaan manual vs gateway
- [ ] Tidak ada field untuk menyimpan bukti transfer (path file) di model `Donasi` atau `TransaksiPembayaran`

**Prioritas Fix:** 🟡 Sedang (diperlukan jika metode transfer manual diaktifkan)

---

## Halaman Tambahan (Tidak Ada di Mockup)

### `/transparansi` — Transparansi Publik
**Status:** ✅ Sudah ada  
**Catatan:** Model `LaporanDana` dan view sudah ada, tapi karena tidak ada alur admin untuk publish laporan, halaman ini akan kosong di awal.

### `/dashboard` — Router Page
**Status:** ✅ Sudah ada (sudah diperbaiki untuk redirect otomatis berdasarkan peran)

### `/profile` — Edit Profil User
**Status:** ⚠️ Inkonsistensi  
**Masalah:** Menggunakan `x-app-layout` (Breeze default) sementara semua halaman lain pakai `layouts.app` — design system tidak konsisten (gray vs emerald)

### `/dashboard/donatur` — Dashboard Donatur
**Status:** ⚠️ CSS Issue  
**Masalah:** View menggunakan CSS custom classes (`bg-background`, `bg-surface`, `text-text-primary`) yang tidak didefinisikan di Tailwind config — akan tampil dengan warna default/salah

---

## Audit Fungsional Cross-Cutting

### ✅ Yang Sudah Baik:

**Middleware & Auth:**
- ✅ `PeranMiddleware` terdaftar dan berfungsi
- ✅ Route grouping dengan `auth` dan `peran:*` sudah sesuai
- ✅ `abort_unless` untuk ownership check sudah ada
- ✅ Tidak ada controller yang pakai `$this->middleware()` di constructor (Laravel 13 compatible)

**Form Validation:**
- ✅ `SimpanKausaRequest` memvalidasi field yang dibutuhkan
- ✅ Rejection reason validation di admin (`min:10`)

**Model Relasi:**
- ✅ Semua relasi Eloquent terdefinisi dengan benar
- ✅ Eager loading untuk hindari N+1 query

**Empty State:**
- ✅ Katalog dan landing menggunakan `@forelse` dengan empty state yang baik

### ⚠️ Yang Perlu Diperbaiki:

**Field Missing di Model:**
- ⚠️ `Instansi` tidak punya field `nama_pj` di `$fillable` — view mengaksesnya = null
- ⚠️ `Kausa` tidak punya field `foto_url` di `$fillable` — gambar tidak bisa disimpan
- ⚠️ `Kausa` tidak punya field `kode_registrasi` — view generate sendiri dengan `str_pad`

**Query Optimization:**
- ⚠️ `InstansiDashboardController@index` menghitung `statusCounts` dengan 5 query terpisah — bisa dioptimasi dengan `groupBy`

**Validation Gap:**
- ⚠️ Tidak ada validasi untuk upload foto utama kausa
- ⚠️ `AdminDashboardController@verify` tidak memvalidasi request

**CSS Issues:**
- ⚠️ Dashboard donatur pakai CSS classes undefined
- ⚠️ Profile page pakai layout Breeze (gray) vs design system (emerald)

---

## Rekomendasi (Prioritas)

### 🔴 Prioritas Tinggi — Blocker Core Feature

1. **Implementasi sistem donasi end-to-end**
   - Buat `DonasiController@store` dengan route `POST /donasi`
   - Sambungkan modal donasi di landing & detail kausa ke backend
   - Tambahkan validasi dan redirect setelah donasi

2. **Tambah field `foto_url` ke model & migration `Kausa`**
   - Buat migration: `$table->string('foto_url')->nullable();`
   - Update `$fillable` di model `Kausa`
   - Update validasi di `SimpanKausaRequest`

3. **Fix form "Simpan Draf" di pengajuan kausa**
   - Pindahkan tombol "Kirim Pengajuan" ke dalam tag `<form>`
   - Tambahkan handler untuk tombol "Simpan Draf"
   - Pastikan form bisa submit dengan `action=draft` atau `action=submit`

4. **Implementasi halaman Profil & Upload Dokumen Legalitas Instansi**
   - Buat view `dashboard/instansi/profil.blade.php`
   - Form data instansi dengan semua field
   - UI upload 4 dokumen legalitas dengan status verifikasi
   - Tambahkan field `nama_pj` ke migration & model `Instansi`

5. **Tambahkan action "Kirim Catatan Revisi" di admin**
   - Buat route `POST /dashboard/admin/kausa/{kausa}/revise`
   - Update `AdminDashboardController@revise()` untuk set status `perlu_diperbaiki`
   - Tambahkan validasi `catatan_admin` (min:20)

6. **Implementasi modul Laporan Dana**
   - **Instansi:** Buat UI untuk mengisi laporan penggunaan dana (form + upload kuitansi)
   - **Admin:** Buat UI untuk review/approve/publish laporan
   - Route: `/dashboard/instansi/laporan/create`, `/dashboard/admin/laporan`

### 🟡 Prioritas Sedang — UX & Konsistensi

7. **Tambahkan field `nama_pj` ke model `Instansi`**
   - Migration: `$table->string('nama_pj')->nullable();`
   - Update `$fillable`

8. **Fix CSS di dashboard donatur dan profile**
   - Ganti custom classes ke Tailwind concrete classes
   - Ubah profile layout dari `x-app-layout` ke `@extends('layouts.app')`

9. **Implementasi stepper JavaScript di form pengajuan kausa**
   - Tambahkan Alpine.js logic untuk show/hide section per step
   - Validasi per step sebelum lanjut

10. **Kondisikan Revision Banner di form kausa**
    - Hapus class `hidden` hardcoded
    - Kondisikan dengan `@if($kausa->status === 'perlu_diperbaiki')`
    - Kirim data kausa yang sedang diedit ke view

11. **Filter kategori di beranda**
    - Kirim `$kategoris` dari `LandingController` ke view
    - Sambungkan tab Alpine.js dengan query parameter `?kategori=X`

12. **Implementasi Verifikasi Pembayaran Manual** (jika transfer manual diaktifkan)
    - Route `/dashboard/admin/verifikasi-pembayaran`
    - Controller untuk verify/reject donasi manual
    - Tambahkan field `bukti_transfer_path` di model `Donasi`

### 🟢 Prioritas Rendah — Polishing

13. **Tambahkan sidebar navigasi terpadu**
    - Dashboard instansi: sidebar dengan menu Ringkasan, Profil, Program, Laporan
    - Dashboard admin: sudah ada, tinggal route yang belum dibuat

14. **Optimalkan query `statusCounts`**
    - Ganti 5 query terpisah dengan satu `groupBy('status')->count()`

15. **Perbaiki typo `sort=tercanggih`**
    - Ganti nilai filter menjadi `terbaru` atau `terkini`

16. **Tambahkan validasi foto kausa**
    - Tambahkan `'foto' => 'nullable|image|max:2048'` di `SimpanKausaRequest`

17. **Redirect setelah store kausa**
    - Ganti dari `kausa.create` ke `dashboard.instansi` dengan flash success

18. **Top Official Bar timestamp dinamis**
    - Update "Akuntabilitas Terbuka" menjadi "Pembaruan Audit: X menit lalu" (dinamis dari `updated_at` terakhir)

---

## Kesimpulan

Aplikasi sudah memiliki **fondasi yang solid** dengan implementasi 5 dari 8 halaman mockup substantif. Namun ada **3 blocker kritis** yang harus diselesaikan sebelum aplikasi bisa digunakan secara fungsional:

1. **Sistem donasi** (modal & form tidak terhubung backend)
2. **Upload dokumen legalitas instansi** (blocker untuk pengajuan kausa)
3. **Modul laporan dana** (inti dari transparansi)

Setelah 3 blocker ini diselesaikan, aplikasi bisa masuk tahap testing dengan user flow lengkap: registrasi instansi → upload legalitas → ajukan kausa → verifikasi admin → donatur beri donasi → laporan dana → publikasi transparansi.

---

**Audit selesai pada:** 5 Oktober 2026, 23:45 WIB  
**Next Action:** Prioritaskan fix blocker 🔴 Tinggi items #1-6
