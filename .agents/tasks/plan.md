# Implementation Plan - Modul Profil Instansi & Dokumen Legalitas (TAHAP 1)

Rencana implementasi modul Profil Instansi & Dokumen Legalitas pada Portal Pendanaan Sosial Pemkab Tulungagung sesuai spesifikasi arsitektur, database migration, dan referensi UI Canvas 005.

## Rationale & Keputusan Arsitektur

1. **Harmonisasi Role & Middleware:**
   Sistem memiliki warisan penamaan role `institution_user` (dari PRD & Data Dictionary) dan `instansi` (dari formulir auth dan EnsureInstansi). `PeranMiddleware` dan `EnsureInstansi` diperbarui agar menerima kedua varian role tersebut secara fleksibel tanpa menimbulkan konflik 403 Forbidden. Route name `dashboard.instansi` dan `instansi.dashboard` keduanya disediakan agar sidebar layout `layouts/dashboard-instansi.blade.php` tidak mengalami `RouteNotFoundException`.
2. **Penanganan Auto-Provision Record Instansi:**
   Jika akun instansi yang login belum memiliki baris pada tabel `instansi` (misalnya akun terdaftar via alur cepat), method `profil()` di `InstansiDashboardController` akan secara otomatis membuat atau menginisialisasi record default instansi berstatus `belum_diverifikasi` agar form binding pada view tidak mengalami error null property.
3. **Fleksibilitas Form Request Validation:**
   Form Request `UpdateProfilInstansiRequest` memetakan baik format atribut standar (`nama`, `jenis`, `nomor_registrasi`, `alamat`, `nomor_telepon`) maupun atribut spesifik canvas (`nama_lembaga`, `jenis_badan_hukum`, `no_sk_kemenkumham`, `alamat_kantor`, `wa_pj`, `nama_pj`, `nik_pj`, `npwp_lembaga`), dengan pesan validasi berbahasa Indonesia yang jelas.
4. **Desain UI Blade Konsisten:**
   View `resources/views/dashboard/instansi/profil.blade.php` mengadopsi tema resmi Pemkab Tulungagung (`#087F5B` hijau primer, `#123B32` hijau gelap, `#F6F8F7` canvas) sesuai canvas 005, terintegrasi ke dalam layout `layouts.dashboard-instansi`, lengkap dengan badge status legalitas, tahapan progres verifikasi, form identitas lembaga & PIC, serta daftar dokumen legalitas fisik.

---

## Daftar Pekerjaan Terurut (Implementation Plan)

- [x] 1. Harmonisasi Role Middleware, Route, dan Model Instansi
      Memperbarui `PeranMiddleware` dan `EnsureInstansi` agar mendukung role `instansi` dan `institution_user` secara konsisten, menambahkan alias relasi `dokumenInstansi()` pada model `Instansi`, serta mendaftarkan route `GET /dashboard/instansi/profil` (name: `instansi.profil`), `PUT /dashboard/instansi/profil` (name: `instansi.profil.update`), dan route alias `dashboard.instansi` di `routes/web.php`.
      Files:
      - `app/Http/Middleware/PeranMiddleware.php`
      - `app/Http/Middleware/EnsureInstansi.php`
      - `app/Models/Instansi.php`
      - `routes/web.php`
      Verify: `php artisan route:list --name=instansi` menampilkan rute profil GET dan PUT, serta `php artisan tinker --execute "echo route('dashboard.instansi');"` berjalan sukses tanpa `RouteNotFoundException`.

- [x] 2. Membuat Form Request UpdateProfilInstansiRequest
      Membuat Form Request `app/Http/Requests/UpdateProfilInstansiRequest.php` dengan otorisasi khusus user instansi, rules validasi lengkap untuk data instansi dan PIC (nama, jenis, nomor_registrasi, alamat, nomor_telepon, nama_pj, nik_pj, npwp_lembaga), serta pesan error kustom dalam Bahasa Indonesia yang informatif.
      Files:
      - `app/Http/Requests/UpdateProfilInstansiRequest.php`
      Verify: `php artisan test --filter=InstansiProfilTest` atau uji coba instansiasi request via artisan tinker berjalan lancar tanpa syntax error.

- [x] 3. Mengimplementasikan Method profil() dan updateProfil() di InstansiDashboardController
      Melengkapi method `profil()` untuk memuat relasi user, instansi, dan dokumen (dokumenInstansi), menangani user yang belum memiliki record instansi dengan auto-create status `belum_diverifikasi`, dan mengirim data ke view `dashboard.instansi.profil`. Mengimplementasikan method `updateProfil()` untuk menyimpan perubahan data instansi dan kontak PIC user, memberikan flash message sukses `'Profil instansi berhasil diperbarui.'`, dan me-redirect kembali ke `route('instansi.profil')`.
      Files:
      - `app/Http/Controllers/Dashboard/InstansiDashboardController.php`
      Verify: `php artisan tinker --execute "(new App\Http\Controllers\Dashboard\InstansiDashboardController);"` sukses di-load tanpa fatal error.

- [x] 4. Membuat View Blade Profil Instansi & Dokumen Legalitas
      Membuat tampilan `resources/views/dashboard/instansi/profil.blade.php` yang meng-extends `layouts.dashboard-instansi`. Tampilan memuat: status/badge legalitas instansi (`belum_diverifikasi`, `menunggu_verifikasi`, `terverifikasi`, `ditolak`), indikator 4 tahapan verifikasi, formulir identitas lembaga & penanggung jawab resmi, daftar berkas dokumen legalitas (SK Kemenkumham, Surat Desa, NPWP, Rekomendasi Dinsos) dengan status verifikasi tiap berkas, accordion regulasi Pemkab Tulungagung, feedback notifikasi flash, error validation alert, serta form `@method('PUT')` dan `@csrf`.
      Files:
      - `resources/views/dashboard/instansi/profil.blade.php`
      Verify: `php artisan view:cache` berhasil tanpa syntax error Blade, dilanjutkan dengan `php artisan view:clear`.

- [x] 5. Menulis Feature Test Lengkap di tests/Feature/InstansiProfilTest.php
      Membuat test komprehensif yang menguji:
      - Akses halaman profil bagi instansi yang terautentikasi (HTTP 200 dan melihat identitas instansi).
      - Redireksi unauthenticated user ke halaman login.
      - Pembatasan hak akses 403 Forbidden bagi non-instansi (donatur/masyarakat).
      - Auto-provision record instansi jika user instansi baru belum memiliki data di tabel `instansi`.
      - Pembaruan profil instansi berhasil dengan data valid, verifikasi perubahan data di database, redirect ke `instansi.profil`, dan keberadaan flash message sukses.
      - Penolakan update ketika input wajib kosong (validasi gagal dan session memiliki errors).
      Files:
      - `tests/Feature/InstansiProfilTest.php`
      Verify: `php artisan test --compact --filter=InstansiProfilTest` seluruh assertions lulus (100% green).

- [x] 6. Format Kode dengan Laravel Pint & Update Checklist TASKS.md
      Menjalankan Laravel Pint untuk memastikan seluruh file PHP yang dibuat atau diubah mematuhi standar koding Laravel, serta memperbarui checklist pada `TASKS.md` untuk item TAHAP 1 (Profil Instansi dan Harmonisasi Penamaan Role & Middleware).
      Files:
      - `TASKS.md`
      - Seluruh file PHP terkait
      Verify: `vendor/bin/pint --dirty --format agent` selesai dengan exit code 0 dan `php artisan test --compact --filter=InstansiProfilTest` tetap lulus.
