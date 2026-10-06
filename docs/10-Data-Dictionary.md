# Data Dictionary

Dokumen ini menjadi acuan nama field, arti data, format, dan aturan penggunaannya.

## Aturan umum
- Nama tabel dan kolom menggunakan bahasa Inggris, snake_case, dan bentuk jamak untuk tabel.
- Primary key menggunakan UUID atau bigint secara konsisten sesuai keputusan schema.
- Semua timestamp disimpan dalam UTC dan ditampilkan mengikuti timezone aplikasi.
- Status menggunakan nilai yang didefinisikan di `14-Status-and-Workflow-Rules.md`.
- Data sensitif tidak boleh ditampilkan pada katalog publik.

## Entitas utama

### users

Tabel autentikasi Laravel tetap bernama `users`. Setiap user hanya memiliki satu nilai `peran`: `admin`, `institution_user`, atau `donatur`. Satu `institution_user` memiliki tepat satu instansi melalui `instansi.user_id` yang unique.
- `id`: identitas pengguna.
- `name`: nama tampilan pengguna.
- `email`: alamat login dan notifikasi email.
- `password`: password yang sudah di-hash; tidak pernah ditampilkan.
- `role`: `admin`, `institution_user`, atau `donatur`.
- `status`: status aktif/nonaktif akun.

### instansi
- `id`: identitas instansi/OPD.
- `user_id`: pemilik atau pengguna utama instansi.
- `name`: nama resmi instansi.
- `legal_status`: status verifikasi legalitas.
- `address`: alamat instansi.
- `contact_phone`: kontak resmi.
- `verified_at`: waktu verifikasi Admin.

### kausa
- `id`: identitas kausa/program.
- `instansi_id`: instansi pengaju.
- `judul`: judul kausa.
- `description`: uraian kausa.
- `target_dana`: target dana.
- `dana_terkumpul`: total dana berhasil tercatat.
- `status`: status workflow kausa.
- `admin_note`: catatan verifikasi Admin.
- `published_at`: waktu dipublikasikan.

### donasi
- `id`: identitas donasi.
- `kausa_id`: kausa tujuan.
- `user_id`: donatur jika login; boleh null jika donasi tamu diizinkan.
- `nominal`: nominal donasi positif.
- `status`: status transaksi donasi.
- `paid_at`: waktu pembayaran berhasil.

### laporan_dana
- `id`: identitas laporan penggunaan dana.
- `kausa_id`: kausa terkait.
- `submitted_by`: pengguna yang mengirim laporan.
- `period_start` dan `period_end`: periode laporan.
- `summary`: ringkasan penggunaan dana.
- `status`: status verifikasi laporan.
- `admin_note`: catatan perbaikan dari Admin.
- `published_at`: waktu laporan transparansi dipublikasikan.

## Data publik dan privat
- Publik: judul kausa, ringkasan, target, progres agregat, dan laporan yang telah dipublikasikan.
- Privat: password, token, dokumen legal yang belum dipublikasikan, data identitas, dan catatan internal Admin.
