# Decision Log

## ADR-001 — Proyek baru dipisahkan dari DONASI
**Keputusan:** Proyek baru berada di `C:\XAMPP\htdocs\pendanaan`.
**Alasan:** Proyek lama tetap menjadi referensi dan tidak boleh terganggu.

## ADR-002 — Istilah Pemkab digunakan sebagai pemilik sistem
**Keputusan:** Label utama menggunakan Pemerintah Kabupaten/ Pemkab, bukan PPID.
**Alasan:** Cakupan sistem adalah pendanaan sosial, bukan fungsi PPID khusus.

## ADR-003 — Role teknis dibatasi tiga role
**Keputusan:** `admin`, `institution_user`, dan `donatur`.
**Alasan:** Menjaga otorisasi dan scope tetap jelas.

## ADR-004 — Integrasi eksternal tidak diasumsikan aktif
**Keputusan:** SSO, payment gateway, QRIS, email resmi, WhatsApp, dan BSrE ditandai Planned/Simulation sampai endpoint dan credential resmi tersedia.
**Alasan:** Field atau tombol UI bukan bukti integrasi telah berjalan.

## ADR-005 — Penamaan tabel bisnis menggunakan Bahasa Indonesia
**Keputusan:** Tabel autentikasi tetap `users` untuk kompatibilitas Laravel. Tabel bisnis menggunakan nama Bahasa Indonesia seperti `instansi`, `kausa`, `donasi`, dan `laporan_dana`.
**Alasan:** Memenuhi konvensi Laravel pada autentikasi sekaligus menjaga bahasa domain bisnis konsisten.

## ADR-006 — Satu instansi memiliki satu user
**Keputusan:** `instansi.user_id` wajib unique dan hanya role `institution_user` yang boleh memiliki instansi.
**Alasan:** Model akses yang disepakati adalah satu akun pengelola untuk satu instansi.

## ADR-007 — Database proyek baru menggunakan schema baru
**Keputusan:** Tidak memakai tabel lama seperti `kampanyes` dan `donasis` sebagai ketergantungan utama.
**Alasan:** Schema baru perlu konsisten dan mudah diuji.

## ADR-006 — Perubahan schema melalui migration
**Keputusan:** Migration menjadi sumber perubahan database.
**Alasan:** Perubahan dapat dilacak, diuji, dan diulang dengan aman.
