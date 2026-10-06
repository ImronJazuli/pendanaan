# PRODUCT REQUIREMENTS DOCUMENT (PRD)

## Portal Pendanaan Sosial Pemkab Tulungagung

**STATUS: DRAFT SEMENTARA**

| | |
| --- | --- |
| **Nama Produk** | Portal Pendanaan Sosial Pemkab Tulungagung |
| **Versi Dokumen** | v0.2 (Updated) |
| **Disusun oleh** | Tim Pengembang (Pengembang) |
| **Untuk** | Pemerintah Kabupaten Tulungagung (Klien) |
| **Tanggal** | 24 Oktober 2023 |
| **Dokumen Terkait** | Notulen Rapat (MoM) Spesifikasi Portal Donasi & Transparansi Pemkab Tulungagung |

---

# 1. Ringkasan Produk (Overview)

Penggalangan dana sosial kemasyarakatan dan penyaluran bantuan darurat di wilayah Kabupaten Tulungagung selama ini menghadapi tantangan besar terkait akuntabilitas, validitas pengaju, serta potensi penyalahgunaan dari inisiatif perorangan yang tidak terverifikasi. Selain itu, masyarakat umum dan donatur kerap mengalami keterbatasan akses informasi dalam memantau ke mana dana yang terkumpul disalurkan, ketiadaan bukti nota belanja/penyaluran fisik yang dapat diaudit publik, serta tidak adanya kanal daring terpadu yang berada di bawah kurasi langsung otoritas resmi pemerintah daerah.

Portal Pendanaan Sosial Pemkab Tulungagung hadir sebagai solusi platform berbasis web resmi pemerintah daerah yang memadukan penggalangan donasi terkurasi dengan prinsip keterbukaan dan transparansi publik. Sistem ini memisahkan akses secara tegas menjadi Portal Publik untuk pencarian kausa, donasi daring via payment gateway Midtrans, dan pemantauan transparansi penyaluran dana real-time, serta Halaman Verifikator Admin Pemkab untuk seleksi kelayakan kausa dan pencatatan audit nota penyaluran. Dengan menerapkan restriksi ketat bagi pengaju (hanya instansi pemerintah dan lembaga berbadan hukum terdaftar), platform ini bertujuan memulihkan kepercayaan publik, mengeliminasi penggalangan dana fiktif, serta memastikan setiap rupiah donasi tercatat dan tersalurkan tepat sasaran.

# 2. Tujuan & Sasaran (Goals)

- Memusatkan seluruh inisiatif penggalangan donasi sosial dan kebencanaan resmi di wilayah Kabupaten Tulungagung di bawah pengawasan serta verifikasi langsung Pemkab.
- Memberikan transparansi mutlak kepada donatur dan publik melalui penyediaan data pengeluaran dana real-time, tampilan bukti fisik nota penyaluran, serta riwayat donasi terbuka.
- Mencegah inisiatif penggalangan dana fiktif atau perorangan tanpa izin dengan membatasi hak akses pengaju hanya untuk instansi dinas resmi dan lembaga sosial berbadan hukum.
- Mempermudah partisipasi donasi masyarakat secara aman dan efisien melalui penyediaan saluran pembayaran digital terintegrasi (Midtrans Snap).
- Menyediakan instrumen kontrol dan data analitik bagi verifikator Pemkab Tulungagung dalam memonitor capaian penggalangan dana, antrean verifikasi kausa, dan progres distribusi logistik/bantuan.

# 3. Pengguna & Peran (Users & Roles)

- **Masyarakat Umum / Donatur :** Pengguna publik yang dapat mengakses portal tanpa login untuk mencari kausa berdasarkan kategori atau lokasi, melihat detail kausa, menyalurkan donasi via Midtrans Snap (dengan identitas nama terang atau anonim/Hamba Allah), melihat riwayat donasi, serta meninjau laporan transparansi penyaluran dan legitimasi pengaju kausa. Donatur tidak mengajukan permohonan bantuan dan tidak menggunakan fitur pengaduan pada ruang lingkup MVP.
- **Instansi / OPD Pengaju Kausa :** Pengguna dari instansi pemerintah atau lembaga sosial resmi yang telah terverifikasi untuk membuat dan mengelola pengajuan kausa, melengkapi profil/legalitas, mengunggah dokumen pendukung, memantau status pengajuan dan donasi, serta mengunggah laporan penggunaan dana.
- **Admin / Superadmin Pemkab :** Tim internal pengelola Pemerintah Kabupaten Tulungagung yang memvalidasi legalitas pengaju, meninjau dan memverifikasi kausa, memantau KPI donasi, memverifikasi pembayaran, mengelola penyaluran dan laporan transparansi, serta mengelola pengguna dan konten portal.

# 4. Ruang Lingkup (Scope)

## 4.1 Termasuk (MVP)

- **Antarmuka Portal Publik (Public Portal):** Header navigasi lengkap dengan logo Pemkab Tulungagung, dropdown kategori, bilah pencarian real-time, tombol CTA pengajuan, tombol login, serta penyajian Landing Page berbasis pengelompokan kategori penuh (full-page grouping) dengan lencana status "Terverifikasi Pemkab".
- **Halaman Detail Kausa & Pusat Transparansi:** Halaman detail dengan deskripsi kausa, galeri foto, panel donasi sticky (progres target, persentase, sisa hari), tab laporan pengeluaran dana real-time beserta tautan nota fisik, dan tab riwayat donatur.
- **Integrasi Payment Gateway:** Modul donasi langsung menggunakan Midtrans Snap Modal terintegrasi callback webhook listener untuk pembaruan status donasi otomatis. Kanal pembayaran yang didukung mencakup: QRIS, Virtual Account Bank (BCA, BNI, BRI, Mandiri, BSI), e-Wallet (GoPay, OVO, Dana, ShopeePay), dan transfer mBanking.
- **Autentikasi Pengaju Multi-Jalur:** Modal autentikasi mencakup Jalur 1 (SSO Pemkab Tulungagung @tulungagung.go.id) dan Jalur 2 (Login NIK e-KTP Penanggung Jawab, verifikasi OTP WhatsApp, serta unggah dokumen legalitas lembaga/surat keterangan desa).
- **Lencana Legitimasi Publik:** Tampilan badge verifikasi identitas pengaju pada card dan detail kausa yang dapat diklik publik untuk melihat informasi profil legalitas pengaju.
- **Dashboard Verifikator Admin Pemkab:** Widget KPI donasi dan kausa, antrean peninjauan kausa baru dengan aksi Setujui/Tolak beserta input alasan, serta modul manajemen pelaporan penyaluran dana (input data pengeluaran dan unggah foto nota).

## 4.2 Di Luar Lingkup Awal / Fase Lanjutan

Fitur berikut berada di luar ruang lingkup MVP saat ini: pengajuan permohonan bantuan oleh Masyarakat/Donatur, pelacakan status permohonan bantuan, pengajuan pengaduan oleh Masyarakat/Donatur, dan pelacakan status pengaduan. Fitur tersebut tidak menjadi use case Donatur dan tidak perlu diimplementasikan pada tahap ini. Usulan pengembangan lanjutan lainnya akan dikompilasi pada Bab 11.

# 5. Asumsi & Batasan (Assumptions & Constraints)

- **Pilihan Arsitektur Backend:** Menggunakan kerangka kerja Laravel 13 dengan basis data relasional PostgreSQL melalui Supabase yang mengimplementasikan integritas referensial (foreign key) antartabel `users`, `instansi`, `kausa`, `donasi`, dan `log_transparansi`.
- **Pilihan Arsitektur Frontend:** Memanfaatkan Tailwind CSS dan JavaScript Native untuk menghasilkan performa rendering cepat dan interaktivitas responsif bernuansa Single Page Application (SPA).
- **Ketergantungan Payment Gateway:** Menggunakan API Midtrans Snap dengan listener webhook internal; konfigurasi pada tahap MVP diasumsikan berjalan pada lingkungan pengujian (Sandbox Mode) sebelum aktivasi akun produksi milik Pemkab.
- **Ketergantungan Layanan OTP WhatsApp (Asumsi Pengembang):** Mekanisme pengiriman kode OTP WhatsApp untuk login pengaju lembaga sosial diasumsikan memerlukan penyedia gateway pesan pihak ketiga (misal: Fonnte, Twilio, atau vendor sejenis) dengan ketersediaan kuota pesan aktif yang disiapkan pengelola.
- **Ketersediaan Protokol SSO Pemkab (Asumsi Pengembang):** Autentikasi SSO instansi dinas mengasumsikan bahwa infrastruktur server email/akun Pemkab Tulungagung (@tulungagung.go.id) telah menyediakan protokol autentikasi standar (OAuth2 / OpenID Connect / SAML / Google Workspace SSO) yang dapat diintegrasikan oleh aplikasi.
- **Penyimpanan Berkas Digital (Asumsi Pengembang):** Berkas legalitas lembaga, foto galeri kausa, serta foto bukti nota fisik penyaluran untuk kebutuhan MVP diasumsikan disimpan langsung di media penyimpanan lokal terproteksi pada server aplikasi (Laravel Local Storage) sebelum diintegrasikan ke layanan cloud object storage (seperti S3) jika volume dokumen bertambah.
- **Batasan Akses Pengajuan:** Akses pengajuan kausa dana secara sengaja dibatasi; perorangan mandiri tanpa naungan lembaga resmi atau tanpa rekomendasi desa/kelurahan tidak diperkenankan membuat kausa donasi dalam sistem.

# 6. Kebutuhan Fungsional (Functional Requirements)

## 6.1 Publik - Penelusuran & Navigasi Kausa

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **PUB-1** | Sistem menampilkan bilah navigasi (navbar) dengan logo resmi Pemerintah Kabupaten Tulungagung, dropdown filter kategori, bilah pencarian real-time, tombol CTA "Ajukan Dana / Bantuan", dan tombol "Login / Masuk". | **Wajib** |
| **PUB-2** | Sistem menyediakan dropdown pengelompokan kausa dengan pilihan kategori: Bencana Alam, Panti Asuhan, Tempat Ibadah, serta Lansia & Dhuafa. | **Wajib** |
| **PUB-3** | Sistem menyediakan kolom pencarian real-time pada header dan hero section untuk menyaring kausa berdasarkan kata kunci judul atau lokasi secara dinamis. | **Wajib** |
| **PUB-4** | Sistem menampilkan hero section pada Landing Page yang memuat welcoming banner, kolom pencarian cepat, serta ringkasan dampak sosial Pemkab Tulungagung. | **Wajib** |
| **PUB-5** | Sistem menampilkan daftar kausa secara visual penuh (full-page grouping) per kategori dalam blok seksi terpisah. | **Wajib** |
| **PUB-6** | Sistem menampilkan card kausa pada setiap blok kategori yang memuat foto thumbnail, judul kausa, nama lembaga pengaju, lencana status "Terverifikasi Pemkab", progress bar, persentase dana terkumpul, dan sisa hari. | **Wajib** |
| **PUB-7** | Sistem menyediakan tombol navigasi cepat (quick filter tabs) untuk berpindah langsung ke blok kategori kausa tertentu (jump-to-category) pada Landing Page. | **Penting** |
| **PUB-8** | Publik dapat mengeklik lencana/badge verifikasi identitas pengaju pada card kausa maupun halaman detail untuk membuka modal informasi legalitas penanggung jawab resmi kausa. | **Wajib** |
| **PUB-9** | Sistem menampilkan halaman detail kausa yang memuat judul lengkap, nama lembaga pengaju, galeri dokumentasi foto lokasi, deskripsi kronologi/latar belakang kebutuhan dana, dan panel donasi sticky. | **Wajib** |

## 6.2 Donatur - Transaksi Donasi & Transparansi

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **DON-1** | Sistem menyediakan panel donasi sticky pada halaman detail kausa yang menampilkan nominal target donasi, realisasi donasi terkumpul, hitung mundur sisa hari, dan tombol CTA "Donasi Sekarang". | **Wajib** |
| **DON-2** | Donatur dapat menginput nominal donasi, memilih opsi identitas (nama terang atau opsi anonim "Hamba Allah"), serta memasukkan alamat email atau nomor kontak. | **Wajib** |
| **DON-3** | Sistem memunculkan pop-up Midtrans Snap Modal saat donatur menekan tombol donasi untuk melanjutkan pembayaran melalui berbagai kanal pembayaran digital. | **Wajib** |
| **DON-4** | Sistem menangani webhook callback dari Midtrans secara real-time untuk memperbarui status transaksi donasi (berhasil/gagal) dan mengakumulasi perolehan dana kausa. | **Wajib** |
| **DON-5** | Sistem menampilkan Pusat Transparansi Berbasis Tab (Tabbed Transparency Center) pada halaman detail kausa. | **Wajib** |
| **DON-6** | Sistem menampilkan Tab 1 (Laporan Transparansi Penyaluran) berisi tabel pencatatan pengeluaran dana real-time beserta tautan/pratinjau nota fisik dan dokumentasi penyaluran. | **Wajib** |
| **DON-7** | Sistem menampilkan Tab 2 (Riwayat Donatur) berisi daftar nama donatur (atau "Hamba Allah"), nominal donasi yang diberikan, dan waktu donasi masuk. | **Wajib** |

## 6.3 Pengaju - Autentikasi & Registrasi Lembaga

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **AUT-1** | Sistem membatasi akses pengajuan dana hanya untuk entitas terverifikasi: Instansi/Badan Pemerintah Pemkab Tulungagung dan Lembaga Sosial Resmi berbadan hukum. | **Wajib** |
| **AUT-2** | Sistem menyediakan Modal Login dengan dua jalur autentikasi terpisah (Jalur 1: SSO Pemkab dan Jalur 2: Verifikasi NIK e-KTP). | **Wajib** |
| **AUT-3** | Pengaju dari instansi pemerintah dapat melakukan autentikasi melalui Single Sign-On (SSO) akun resmi Pemerintah Kabupaten Tulungagung (@tulungagung.go.id) dan langsung terverifikasi tanpa unggah berkas legalitas manual. | **Wajib** |
| **AUT-4** | Pengaju dari lembaga sosial masyarakat dapat mendaftar dan masuk menggunakan NIK e-KTP Penanggung Jawab serta kode OTP yang dikirimkan via WhatsApp. | **Wajib** |
| **AUT-5** | Pengaju lembaga sosial wajib mengunggah dokumen legalitas legitimasi (Surat Keterangan Desa / SK Kemenkumham / NPWP Lembaga / Bukti Terdaftar Dinsos) pada profil lembaganya. | **Wajib** |
| **AUT-6** | Sistem memvalidasi kelengkapan dokumen pengaju lembaga sosial sebelum mengizinkan pembuatan formulir pengajuan kausa baru. | **Wajib** |

## 6.4 Pengaju - Formulir Pengajuan Kausa

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **AJU-1** | Pengaju yang telah berhasil login dapat mengakses formulir pembuatan permohonan penggalangan dana melalui tombol CTA "Ajukan Dana / Bantuan". | **Wajib** |
| **AJU-2** | Pengaju dapat menginput data kausa berupa judul, kategori kausa, lokasi sasaran bantuan di Kabupaten Tulungagung, target dana, durasi/tenggat waktu kausa, dan kronologi/alasan kebutuhan bantuan. | **Wajib** |
| **AJU-3** | Pengaju dapat mengunggah berkas foto pendukung lokasi/kondisi riil sasaran bantuan untuk ditampilkan pada galeri kausa. | **Wajib** |
| **AJU-4** | Sistem menyimpan draf pengajuan kausa dan mengirimkannya ke antrean verifikasi Admin Pemkab dengan status awal "Menunggu Verifikasi". | **Wajib** |

## 6.5 Admin Verifikator - Verifikasi Kausa & Dashboard KPI

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **VER-1** | Admin Verifikator dapat melakukan login ke area terbatas admin dengan hak akses khusus (role-based access) internal Pemkab Tulungagung. | **Wajib** |
| **VER-2** | Sistem menampilkan ringkasan KPI (Summary Widgets) pada dashboard admin yang mencakup total akumulasi donasi terkumpul via Midtrans, jumlah kausa aktif, dan total antrean verifikasi pengajuan baru. | **Wajib** |
| **VER-3** | Sistem menampilkan Tabel Antrean Verifikasi Pengajuan yang memuat daftar kausa berstatus "Menunggu Verifikasi" beserta detail informasi pemohon dan berkas pendukung. | **Wajib** |
| **VER-4** | Admin Verifikator dapat menyetujui pengajuan kausa (Aksi "Setujui"), yang secara otomatis mengubah status kausa menjadi "Disetujui" dan menayangkannya ke Landing Page publik. | **Wajib** |
| **VER-5** | Admin Verifikator dapat menolak pengajuan kausa (Aksi "Tolak") dengan kewajiban mengisi catatan/alasan penolakan di sistem. | **Wajib** |

## 6.6 Instansi / OPD Pengaju Kausa - Manajemen Program & Laporan Dana

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **PGD-1** | Instansi/OPD Pengaju Kausa dapat melihat daftar seluruh program yang pernah diajukan beserta status pengajuan (Menunggu Verifikasi, Disetujui, Ditolak, Perlu Diperbaiki). | **Wajib** |
| **PGD-2** | Instansi/OPD Pengaju Kausa dapat memantau jumlah dana terkumpul, jumlah donatur, dan progres pencapaian target pada setiap program miliknya. | **Wajib** |
| **PGD-3** | Instansi/OPD Pengaju Kausa dapat melihat riwayat donasi yang masuk pada setiap program miliknya. | **Wajib** |
| **PGD-4** | Instansi/OPD Pengaju Kausa wajib mengunggah laporan penggunaan dana beserta bukti fisik (foto kegiatan, nota/kuitansi) setelah dana disalurkan. | **Wajib** |
| **PGD-5** | Instansi/OPD Pengaju Kausa dapat melihat status pemeriksaan laporan oleh Admin (Menunggu, Disetujui, Perlu Diperbaiki). | **Wajib** |
| **PGD-6** | Instansi/OPD Pengaju Kausa dapat memperbaiki dan mengirim ulang laporan penggunaan dana apabila Admin meminta perbaikan. | **Wajib** |

## 6.7 Admin Verifikator - Verifikasi Bukti Pembayaran Manual

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **PAY-1** | Sistem mendukung alur pembayaran manual sebagai alternatif: donatur mengunggah bukti pembayaran (screenshot transfer/QRIS) untuk diverifikasi Admin. | **Penting** |
| **PAY-2** | Admin dapat melihat daftar bukti pembayaran yang menunggu verifikasi beserta detail: nama donatur, program, nominal, tanggal, dan foto bukti. | **Penting** |
| **PAY-3** | Admin dapat menyetujui atau menolak bukti pembayaran manual disertai catatan alasan jika ditolak. | **Penting** |
| **PAY-4** | Sistem hanya memperbarui total dana terkumpul setelah Admin menyetujui bukti pembayaran manual. | **Penting** |
| **PAY-5** | Satu bukti pembayaran tidak boleh dihitung lebih dari satu kali meskipun diproses ulang. | **Wajib** |

## 6.8 Sistem - Notifikasi Status

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **NOT-1** | Sistem mengirimkan notifikasi in-app kepada Instansi/OPD Pengaju Kausa saat status pengajuan program berubah (disetujui, ditolak, perlu diperbaiki). | **Wajib** |
| **NOT-2** | Sistem mengirimkan notifikasi in-app kepada Donatur saat status pembayaran berubah (menunggu verifikasi, disetujui, ditolak). | **Wajib** |
| **NOT-3** | Sistem mengirimkan notifikasi in-app kepada Instansi/OPD Pengaju Kausa saat laporan penggunaan dana diperiksa Admin (disetujui, perlu diperbaiki, dipublikasikan). | **Wajib** |
| **NOT-4** | Sistem menampilkan ikon notifikasi dengan badge jumlah notifikasi belum dibaca pada navbar pengguna yang telah login. | **Penting** |

## 6.9 Admin Verifikator - Manajemen Penyaluran Dana

| **ID** | **Kebutuhan Fungsional** | **Prioritas** |
| --- | --- | --- |
| **SAL-1** | Admin Verifikator dapat mengelola modul penyaluran dana untuk setiap kausa yang aktif atau telah memenuhi target. | **Wajib** |
| **SAL-2** | Admin Verifikator dapat menginput rincian pengeluaran dana yang mencakup nama alokasi belanja, nominal pengeluaran, tanggal penyaluran, dan deskripsi penggunaan dana. | **Wajib** |
| **SAL-3** | Admin Verifikator dapat mengunggah foto bukti fisik penyaluran dan nota kuitansi belanja ke dalam sistem. | **Wajib** |
| **SAL-4** | Sistem secara otomatis mempublikasikan data pengeluaran dan nota fisik yang diinput verifikator ke Tab Transparansi Penyaluran pada halaman detail kausa publik. | **Wajib** |

---

# 7. Alur Pengguna Utama (Key User Flows)

## 7.1 Alur Transaksi Donasi Publik via Midtrans Snap (Happy Path)

1. Donatur mengakses Landing Page Portal Donasi Pemkab Tulungagung dan memilih salah satu card kausa atau memanfaatkan bilah pencarian/filter kategori.
2. Donatur membuka halaman detail kausa, membaca kronologi kebutuhan dana, dan mengamati target serta realisasi pengumpulan dana pada panel donasi sticky.
3. Donatur menekan tombol "Donasi Sekarang" pada panel sticky.
4. Sistem menampilkan formulir donasi; donatur memasukkan nominal donasi, memilih identitas nama terang atau opsi anonim "Hamba Allah", serta mengisi alamat email dan nomor telepon.
5. Donatur menekan tombol konfirmasi pembayaran.
6. Sistem memanggil API Midtrans Snap dan memunculkan pop-up Midtrans Snap Modal di antarmuka pengguna dengan status transaksi "Menunggu Pembayaran".
7. Donatur menyelesaikan pembayaran menggunakan metode yang dipilih (QRIS, Virtual Account Bank, atau e-Wallet).
8. Sistem Midtrans mengirimkan webhook callback notifikasi pembayaran ke server Laravel backend.
9. Backend memverifikasi tanda tangan transaksi, mengubah status transaksi menjadi "Berhasil", menambahkan nominal ke akumulasi dana kausa, serta menambahkan data donatur ke Tab 2 (Riwayat Donatur).
10. Antarmuka sistem donatur menampilkan notifikasi pembayaran berhasil secara real-time.

## 7.2 Alur Pengajuan Kausa Baru oleh Lembaga Sosial (Jalur NIK & Verifikasi Legitimasi)

1. Penanggung jawab lembaga sosial mengakses portal publik dan mengeklik tombol "Login / Masuk".
2. Pada modal autentikasi, pengguna memilih "Jalur 2: Verifikasi NIK e-KTP / Lembaga".
3. Pengguna menginput NIK e-KTP penanggung jawab dan nomor WhatsApp aktif, kemudian menekan "Kirim OTP".
4. Sistem mengirimkan kode OTP ke WhatsApp pengguna; pengguna memasukkan kode OTP ke sistem untuk verifikasi login.
5. Setelah berhasil masuk, jika lembaga belum terdaftar/terverifikasi, pengguna melengkapi profil lembaga dan mengunggah dokumen legitimasi (Surat Keterangan Desa / SK Kemenkumham / NPWP / Bukti Terdaftar Dinsos).
6. Pengguna mengeklik tombol "Ajukan Dana / Bantuan" di bilah navigasi.
7. Pengguna mengisi formulir pengajuan kausa: judul, kategori (Bencana Alam/Panti Asuhan/Tempat Ibadah/Lansia & Dhuafa), lokasi terperinci di Kabupaten Tulungagung, target dana, batas waktu kampanye, kronologi masalah, serta mengunggah foto-foto kondisi riil sasaran bantuan.
8. Pengguna menekan tombol "Kirim Pengajuan".
9. Sistem menyimpan data pengajuan kausa dengan status "Menunggu Verifikasi" dan memasukkannya ke dalam tabel antrean verifikasi Admin Pemkab.

## 7.3 Alur Pengajuan Kausa Baru oleh Instansi Pemerintah via SSO Pemkab

1. Aparatur sipil dari dinas/kecamatan/kelurahan mengakses portal dan mengeklik tombol "Login / Masuk".
2. Pada modal autentikasi, aparatur memilih "Jalur 1: SSO Pemkab Tulungagung".
3. Pengguna melakukan autentikasi menggunakan akun resmi instansi bertaraf domain `@tulungagung.go.id`.
4. Sistem memvalidasi identitas SSO; akun pengguna langsung otomatis memperoleh status institusi "Terverifikasi Pemkab" tanpa memerlukan unggah berkas legalitas manual.
5. Pengguna mengeklik tombol "Ajukan Dana / Bantuan".
6. Pengguna mengisi detail kausa (judul, kategori, target dana, lokasi, deskripsi kondisi darurat/sosial, dan foto lapangan).
7. Pengguna mengirimkan pengajuan; sistem menetapkan status kausa menjadi "Menunggu Verifikasi" untuk divalidasi oleh verifikator Pemkab pusat.

## 7.4 Alur Peninjauan dan Verifikasi Kausa oleh Admin Pemkab

1. Admin Verifikator Pemkab melakukan login ke dashboard internal admin portal.
2. Sistem menampilkan ringkasan KPI (donasi, kausa aktif, dan kausa pending) serta menyajikan Tabel Antrean Verifikasi Pengajuan.
3. Admin memilih salah satu pengajuan baru untuk meninjau berkas permohonan, dokumen legalitas lembaga pengaju (jika dari lembaga sosial), deskripsi urgensi, dan foto sasaran.
4. **Kondisi Persetujuan (Happy Path):**
   - Admin menilai permohonan telah memenuhi syarat dan menekan tombol "Setujui".
   - Sistem memperbarui status kausa dari "Menunggu Verifikasi" menjadi "Disetujui".
   - Sistem menyematkan badge "Terverifikasi Pemkab" lengkap dengan nomor registrasi pada kausa tersebut dan langsung menampilkannya pada seksi kategori Landing Page Publik.
5. **Kondisi Penolakan (Exception Path):**
   - Admin menemukan dokumen tidak valid, lokasi di luar wewenang, atau indikasi duplikasi, lalu menekan tombol "Tolak".
   - Sistem memunculkan dialog input; Admin wajib mengetikkan alasan penolakan secara spesifik.
   - Sistem memperbarui status kausa menjadi "Ditolak" beserta alasan penolakan dan mengirimkan informasi status kepada pengaju.

## 7.5 Alur Manajemen dan Publikasi Penyaluran Dana Transparansi

1. Admin Verifikator Pemkab membuka Modul Penyaluran Dana di portal admin dan memilih kausa donasi yang dananya telah dicairkan/disalurkan ke lapangan.
2. Admin mengeklik tombol "Tambah Laporan Penyaluran".
3. Admin menginput formulir pengeluaran: rincian judul belanja bantuan, tanggal transaksi penyaluran riil, nominal pengeluaran, serta mengunggah foto bukti fisik nota kuitansi dan foto penyerahan bantuan.
4. Admin menekan tombol "Simpan dan Publikasikan".
5. Sistem memvalidasi data dan menyimpan catatan mutasi ke basis data.
6. Sistem secara instan memperbarui halaman detail kausa publik pada Tab 1 (Laporan Transparansi Penyaluran), sehingga donatur dan masyarakat luas dapat langsung melihat rincian pengeluaran dana beserta tautan dokumen nota fisik yang sah.

---

# 8. Model Data (High-Level)

| **Entitas** | **Field Utama** | **Keterangan** |
| --- | --- | --- |
| **users** | `id`, `name`, `email`, `nik`, `phone_number`, `role` (admin, institution_user), `sso_id`, `created_at`, `updated_at` | Menyimpan kredensial identitas pengguna sistem, baik aparatur dinas (SSO), perwakilan lembaga masyarakat (NIK & No. WA), maupun staf Admin Verifikator Pemkab. |
| **instansi** | `id`, `user_id` (FK, unique), `jenis`, `nama`, `nomor_registrasi`, `status_verifikasi`, `alamat`, `nomor_telepon`, `terverifikasi_pada`, `created_at`, `updated_at`, `deleted_at` | Menyimpan profil instansi/OPD atau lembaga resmi. Satu instansi memiliki tepat satu user pengelola. |
| **kausa** | `id`, `instansi_id` (FK), `judul`, `slug`, `kategori`, `lokasi`, `target_dana`, `dana_terkumpul`, `tanggal_berakhir`, `deskripsi`, `status`, `catatan_admin`, `created_at`, `updated_at`, `deleted_at` | Menyimpan seluruh data penggalangan dana kausa, target perolehan, progres realisasi dana terkumpul, kategori visual, galeri foto, serta status kurasi verifikator. |
| **donasi** | `id`, `kausa_id` (FK), `pesanan_pembayaran`, `token_pembayaran`, `nama_donatur`, `anonim`, `email_donatur`, `telepon_donatur`, `nominal`, `metode_pembayaran`, `status`, `dibayar_pada`, `created_at`, `updated_at` | Mencatat transaksi donasi dan status pembayarannya. |
| **log_transparansi** | `id`, `kausa_id` (FK), `admin_id` (FK ke users), `judul_pengeluaran`, `nominal`, `tanggal_pengeluaran`, `path_bukti`, `deskripsi`, `created_at`, `updated_at` | Mencatat pengeluaran dana dan bukti transparansi yang dapat dipublikasikan. |
| **laporan_dana** | `id`, `kausa_id` (FK), `user_id` (FK ke users), `judul`, `deskripsi`, `nominal_digunakan`, `tanggal_penyaluran`, `penerima_manfaat`, `path_foto_kegiatan`, `path_bukti`, `status`, `catatan_admin`, `created_at`, `updated_at` | Menyimpan laporan penggunaan dana dari instansi. |
| **notifikasi** | `id`, `user_id` (FK ke users), `judul`, `isi`, `jenis`, `sudah_dibaca`, `created_at` | Menyimpan notifikasi dalam aplikasi. |

**Catatan:** field dalam [tanda kurung siku] merupakan bagian dari fitur usulan/Fase Lanjutan (Bab 11). Seluruh field di atas merupakan atribut esensial yang dialokasikan penuh untuk pemenuhan fungsionalitas MVP rilis awal.

# 9. Kebutuhan Non-Fungsional (Non-Functional Requirements)

- **Responsivitas & Tampilan Antarmuka :** Antarmuka web harus sepenuhnya adaptif (responsive web design) menggunakan Tailwind CSS agar nyaman diakses melalui perangkat seluler (smartphone), tablet, maupun peramban desktop dengan nuansa interaktif menyerupai Single Page Application (SPA).
- **Keamanan & Kontrol Hak Akses :** Sistem menerapkan kontrol akses berbasis peran (Role-Based Access Control / RBAC) yang memisahkan ranah publik, pengaju terverifikasi, dan admin verifikator Pemkab. Seluruh formulir unggah berkas (legalitas lembaga dan foto nota) wajib melalui validasi tipe MIME, pemindaian ukuran berkas, serta sanitasi input guna mencegah celah keamanan SQL Injection dan Cross-Site Scripting (XSS).
- **Performa & Waktu Muat :** Waktu respons pemuatan halaman awal (Landing Page) ditargetkan di bawah 3 detik pada koneksi internet standar, dan fungsi pencarian real-time pada katalog kausa harus memberikan hasil instan tanpa membebani server database melalui optimalisasi indeks query MySQL.
- **Privasi & Kerahasiaan Data :** Sistem menjamin kerahasiaan data pribadi donatur dengan mendukung opsi anonimitas ("Hamba Allah"), serta melindungi data sensitif penanggung jawab lembaga (NIK e-KTP dan nomor telepon) dari akses publik umum.
- **Integritas & Keandalan Transaksi :** Modul penanganan webhook callback Midtrans Snap harus bersifat idempoten (idempotent listener) dengan memvalidasi signature key transaksi untuk mencegah manipulasi status pembayaran ganda atau fiktif.

# 10. Integrasi Pihak Ketiga

| **Layanan** | **Fungsi** | **Catatan** |
| --- | --- | --- |
| **Midtrans Snap API & Webhook Listener** | Payment Gateway untuk memproses penerimaan donasi daring melalui berbagai kanal pembayaran: QRIS, Virtual Account Bank (BCA, BNI, BRI, Mandiri, BSI), e-Wallet (GoPay, OVO, Dana, ShopeePay), dan transfer mBanking, serta pembaruan status transaksi secara otomatis via webhook. | Diimplementasikan menggunakan lingkungan pengujian (Sandbox Mode) untuk rilis awal sebelum aktivasi akun produksi resmi Pemkab. |
| **Gateway WhatsApp OTP** | Layanan pengiriman kode sandi sekali pakai (OTP) ke nomor WhatsApp penanggung jawab lembaga masyarakat untuk kebutuhan autentikasi Jalur 2. | Memerlukan penyedia API perpesanan pihak ketiga (misal: Fonnte, Twilio, atau vendor sejenis) dengan kuota pesan aktif. |
| **SSO Pemkab Tulungagung** | Protokol autentikasi tunggal bagi aparatur dinas/kecamatan/desa di Kabupaten Tulungagung yang menggunakan alamat email resmi (@tulungagung.go.id) pada autentikasi Jalur 1. | Bergantung pada ketersediaan protokol autentikasi (OAuth2 / SAML / Google Workspace SSO) yang dikelola Diskominfo Tulungagung. |

# 11. Fitur Usulan / Fase Lanjutan

Belum ada usulan fase lanjutan.

# 12. Pertanyaan Terbuka / TBD

- Protokol teknis spesifik yang saat ini aktif digunakan oleh infrastruktur email/akun dinas Pemkab Tulungagung (@tulungagung.go.id) untuk kebutuhan integrasi SSO (apakah OAuth2, SAML 2.0, OpenID Connect, atau Google Workspace).
- Penentuan vendor resmi dan alokasi anggaran operasional untuk penyedia gateway WhatsApp OTP (misal: Fonnte, Twilio, atau vendor SMS alternatif).
- Kebijakan kepemilikan rekening bank penampung donasi (apakah rekening kas daerah, rekening giro resmi Pemkab, atau rekening BAZNAS Tulungagung) untuk proses pendaftaran akun Midtrans Production serta alur pencairan dana (disbursement/payout) ke penerima manfaat.
- Ketetapan batas waktu (SLA) dan Standar Operasional Prosedur (SOP) bagi tim internal Admin Verifikator Pemkab dalam memproses persetujuan atau penolakan antrean kausa baru.
- Batasan minimum dan maksimum nominal donasi per transaksi serta batas durasi waktu maksimal kampanye penggalangan dana yang diperbolehkan aktif di portal.
- Ketentuan retensi penyimpanan fisik dan mekanisme audit berkala terhadap bukti kuitansi/nota asli yang diunggah verifikator ke portal.

# 13. Glosarium

- **Pemkab (Pemerintah Kabupaten) :** Pemerintah daerah tingkat kabupaten yang menjadi pemilik dan pengelola utama portal pendanaan sosial ini.
- **Midtrans Snap :** Komponen antarmuka pop-up modal dari penyedia gerbang pembayaran (payment gateway) Midtrans yang memfasilitasi transaksi donasi dengan beragam kanal bayar secara aman.
- **Webhook Callback :** Mekanisme komunikasi otomatis antar-server berbasis HTTP POST yang dikirimkan oleh payment gateway ke backend portal untuk memperbarui status donasi secara real-time.
- **SSO (Single Sign-On) :** Sistem autentikasi yang mengizinkan pengguna mengakses beberapa aplikasi menggunakan satu set kredensial identitas terpusat.
- **OTP (One-Time Password) :** Kode numerik rahasia sekali pakai yang berlaku dalam durasi singkat untuk memverifikasi keabsahan nomor kontak penanggung jawab lembaga.
- **NIK (Nomor Induk Kependudukan) :** Nomor identitas kependudukan tunggal dan permanen milik warga negara Indonesia yang tercantum pada e-KTP.
- **Kausa (Campaign) :** Inisiatif permohonan penggalangan donasi publik untuk program sosial kemasyarakatan, penanganan bencana, sarana ibadah, atau bantuan dhuafa yang telah disetujui untuk tayang di portal.
- **SPA Feel (Single Page Application UX Feel) :** Sensasi interaktivitas antarmuka web yang cepat dan mulus tanpa jeda pemuatan ulang halaman secara menyeluruh saat pengguna melakukan penelusuran atau penyaringan data.

---

*Dokumen ini merupakan draft sementara dan dapat berubah seiring pembahasan lebih lanjut dengan klien.*