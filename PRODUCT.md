# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

1. **Masyarakat Umum / Donatur**: Warga Tulungagung dan donatur publik yang ingin menyalurkan bantuan sosial/bencana secara aman, transparan, dan terpercaya langsung ke inisiatif terverifikasi Pemkab Tulungagung.
2. **Instansi / Organisasi Perangkat Daerah (OPD) & Lembaga Sosial Berbadan Hukum**: Pihak terakreditasi yang mengajukan kausa pendanaan, mengunggah bukti legalitas, dan melaporkan realisasi penggunaan dana beserta bukti nota fisik.
3. **Verifikator / Admin Pemkab Tulungagung**: Pengelola resmi yang memvalidasi keabsahan pengaju, menyetujui/menolak kausa, serta mengawasi audit dan laporan transparansi penyaluran dana.

## Product Purpose

Platform resmi penggalangan dana sosial dan tanggap darurat Kabupaten Tulungagung yang mengeliminasi penggalangan dana fiktif melalui kurasi ketat pemerintah daerah dan menyajikan transparansi penyaluran dana mutlak secara real-time dengan bukti fisik nota belanja yang dapat diaudit publik.

## Positioning

Portal filantropi resmi daerah pertama yang menggabungkan kemudahan donasi digital (Midtrans/QRIS) dengan jaminan akuntabilitas birokrasi pemerintah daerah: setiap kausa wajib diajukan oleh instansi resmi/lembaga berbadan hukum terverifikasi dan setiap pengeluaran dana wajib disertai publikasi nota fisik yang dapat dilihat siapa saja.

## Operating Context

- Dioperasikan dalam ekosistem layanan publik Pemerintah Kabupaten Tulungagung.
- Berinteraksi dengan pembayaran digital masyarakat (QRIS, VA Bank, e-Wallet).
- Digunakan oleh ASN/staf OPD dalam mengelola kausa sosial dan bencana alam.
- Tampilan responsif optimal untuk ponsel pintar (mayoritas donatur) dan desktop (dashboard operasional admin/instansi).

## Capabilities and Constraints

- **Stack**: Laravel 13, Blade templating, Tailwind CSS, Vite, database PostgreSQL (Supabase).
- **Pembayaran**: Gateway pembayaran simulasi (Fase dev/test) dan adapter Midtrans Snap (Fase produksi).
- **Autentikasi & Otorisasi**: RBAC dengan peran `admin`, `institution_user`, dan `donatur`.
- **Integritas Bukti**: Nota pengeluaran dana dan dokumen legalitas tersimpan aman dengan hak akses transparan.
- **Workflow Ketat**: Transisi status kausa mengikuti aturan baku di `docs/14-Status-and-Workflow-Rules.md`.

## Brand Commitments

- **Identitas**: Pemerintah Kabupaten Tulungagung — resmi, berwibawa, bersih, dan melayani.
- **Warna Utama**: Deep Emerald / Forest Green (`#087F5B`, `#123B32`) mencerminkan integritas, kedamaian sosial, dan transparansi lingkungan asri Tulungagung.
- **Tipografi**: Heading menggunakan *Plus Jakarta Sans* (modern & tegap), Body text menggunakan *Inter* (keterbacaan optimal).
- **Nada Bicara (Voice)**: Bersahabat, transparan, akuntabel, tanpa kesan birokratis yang kaku bagi publik.

## Evidence on Hand

- Dokumen spesifikasi kebutuhan: `PRD-Portal-Pendanaan-Pemkab.md`
- Pedoman desain lengkap: `docs/05-DESIGN.md`
- Skema database dan kamus data: `docs/04-ERD-Database-Specification.md`, `docs/10-Data-Dictionary.md`
- Alur kerja dan transisi status: `docs/14-Status-and-Workflow-Rules.md`

## Product Principles

1. **Transparansi Tanpa Kompromi**: Publik berhak melihat ke mana setiap rupiah donasi disalurkan hingga ke bukti foto kuitansi/nota riil.
2. **Hanya Kausa Sahih & Terkurasi**: Menutup celah penipuan; hanya instansi dan lembaga terverifikasi yang boleh menggalang dana.
3. **Aksesibilitas Donasi Tanpa Gesekan**: Donatur dapat berdonasi dalam hitungan detik tanpa dipersulit form yang berbelit.
4. **Keamanan & Akuntabilitas Data**: Menjaga data sensitif pengaju sekaligus memastikan transparansi dana sosial tetap terjaga.
