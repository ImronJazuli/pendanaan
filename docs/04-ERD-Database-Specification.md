# ERD and Database Specification

Entitas: users, instansi, dokumen_instansi, kategori_kausa, kausa, dokumen_kausa, riwayat_status_kausa, donasi, transaksi_pembayaran, laporan_dana, rincian_laporan_dana, log_transparansi, notifikasi, log_audit.

Relasi identitas: satu baris `users` dengan peran `institution_user` memiliki tepat satu baris `instansi`; `instansi.user_id` wajib foreign key dan unique. Admin dan donatur tidak memiliki instansi.

Relasi: `instansi` memiliki banyak `kausa`; `kausa` memiliki banyak `donasi`, `dokumen_kausa`, `riwayat_status_kausa`, dan `laporan_dana`; `donasi` memiliki satu `transaksi_pembayaran`. Gunakan foreign key, index status/foreign key/timestamps, constraint nominal positif, timestamps, dan soft delete sesuai kebutuhan.
