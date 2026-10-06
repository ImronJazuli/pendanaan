# Security and Privacy

## Prinsip
- Terapkan least privilege dan deny by default.
- Semua input divalidasi melalui Form Request.
- Semua operasi yang mengubah data memiliki authorization Policy atau Gate.
- Credential hanya berasal dari `.env` dan tidak boleh masuk repository.

## Autentikasi dan otorisasi
- Role teknis: `admin`, `institution_user`, `donatur`.
- Middleware role membatasi area aplikasi.
- Policy memastikan pengguna hanya mengakses data miliknya atau data yang memang publik.
- Password disimpan menggunakan hashing Laravel.
- Session, cache, dan queue development tidak wajib memakai database cloud.

## Upload dokumen
- Validasi MIME type, ekstensi, ukuran, dan nama file.
- Simpan dokumen privat di storage non-public.
- Gunakan nama file acak dan cegah path traversal.
- File hanya dapat diunduh setelah authorization.

## Pembayaran
- Status pembayaran ditentukan backend berdasarkan callback resmi.
- Callback harus memverifikasi signature dan bersifat idempotent.
- Browser tidak boleh menjadi sumber kebenaran status transaksi.
- Mode simulasi harus diberi penanda dan tidak boleh menyerupai transaksi produksi.

## Audit dan privasi
- Catat perubahan status penting dalam audit log.
- Jangan menampilkan data pribadi donatur tanpa dasar dan persetujuan yang sesuai.
- Jangan mencatat password, token, nomor kartu, atau credential ke log.
- Error publik tidak boleh membocorkan stack trace atau detail database.
