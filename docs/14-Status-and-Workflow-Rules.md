# Status and Workflow Rules

## Campaign
```text
draft -> menunggu_verifikasi
menunggu_verifikasi -> disetujui | perlu_diperbaiki | ditolak
perlu_diperbaiki -> menunggu_verifikasi
 disetujui -> selesai
```

- Hanya Instansi pemilik yang dapat mengirim atau memperbaiki kausa.
- Hanya Admin yang dapat menyetujui, menolak, atau meminta perbaikan.
- Kausa publik harus berstatus `disetujui`.
- Setiap perubahan status menyimpan actor, waktu, status lama, status baru, dan catatan.

## Donation
```text
pending -> success | failed | expired
```

- `success` hanya boleh ditetapkan backend melalui pembayaran terverifikasi atau adapter simulasi.
- Donasi gagal atau kedaluwarsa tidak menambah total dana terkumpul.
- Callback yang sama tidak boleh menggandakan pencatatan dana.

## Fund report
```text
draft -> menunggu
menunggu -> disetujui | perlu_diperbaiki
perlu_diperbaiki -> menunggu
disetujui -> dipublikasikan
```

- Instansi membuat dan mengirim laporan.
- Admin memverifikasi laporan.
- Hanya laporan `dipublikasikan` yang tampil di area transparansi publik.
- Status final tidak boleh diubah tanpa proses koreksi dan audit yang jelas.

## Aturan umum
- Status tidak boleh diubah langsung dari request tanpa validasi transisi.
- Transisi ilegal ditolak dan dicatat bila relevan.
- Notifikasi dibuat setelah transisi berhasil, bukan sebelum transaksi database selesai.
