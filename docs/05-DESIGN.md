---
name: Portal Pendanaan Sosial Pemkab Tulungagung
description: Design system dan aturan UI/UX untuk portal pengajuan kausa, donasi, laporan penggunaan dana, dan transparansi publik.
colors:
  primary: "#087F5B"
  primary-hover: "#066A4C"
  primary-active: "#05563E"
  primary-subtle: "#E6F4EF"
  on-primary: "#FFFFFF"
  secondary: "#123B32"
  secondary-hover: "#1A5144"
  on-secondary: "#FFFFFF"
  background: "#F6F8F7"
  surface: "#FFFFFF"
  surface-muted: "#EEF3F1"
  text-primary: "#17211E"
  text-secondary: "#52615C"
  text-muted: "#73817C"
  border: "#D9E2DE"
  border-hover: "#B9CCC4"
  success: "#15803D"
  warning: "#CA8A04"
  info: "#2563EB"
  error: "#B91C1C"
  status-pending-bg: "#FEF3C7"
  status-pending-text: "#92400E"
  status-revision-bg: "#FFEDD5"
  status-revision-text: "#9A3412"
  status-rejected-bg: "#FEE2E2"
  status-rejected-text: "#991B1B"
  status-approved-bg: "#DCFCE7"
  status-approved-text: "#166534"
typography:
  h1:
    fontFamily: "'Plus Jakarta Sans', sans-serif"
    fontSize: "3rem"
    fontWeight: 700
    lineHeight: 1.15
    letterSpacing: "-0.025em"
  h2:
    fontFamily: "'Plus Jakarta Sans', sans-serif"
    fontSize: "2.25rem"
    fontWeight: 600
    lineHeight: 1.25
    letterSpacing: "-0.02em"
  h3:
    fontFamily: "'Plus Jakarta Sans', sans-serif"
    fontSize: "1.35rem"
    fontWeight: 600
    lineHeight: 1.35
  body-md:
    fontFamily: "'Inter', sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.6
  body-sm:
    fontFamily: "'Inter', sans-serif"
    fontSize: "0.875rem"
    fontWeight: 400
    lineHeight: 1.5
  label:
    fontFamily: "'Inter', sans-serif"
    fontSize: "0.875rem"
    fontWeight: 600
    lineHeight: 1.4
rounded:
  sm: "6px"
  md: "10px"
  lg: "14px"
  xl: "18px"
  full: "9999px"
spacing:
  xs: "4px"
  sm: "8px"
  md: "16px"
  lg: "24px"
  xl: "32px"
  2xl: "48px"
  3xl: "64px"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.on-primary}"
    rounded: "{rounded.md}"
    padding: "12px 20px"
    fontSize: "{typography.label.fontSize}"
    fontWeight: "600"
  button-secondary:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.primary}"
    borderColor: "{colors.primary}"
    rounded: "{rounded.md}"
    padding: "12px 20px"
  button-danger:
    backgroundColor: "{colors.error}"
    textColor: "#FFFFFF"
    rounded: "{rounded.md}"
    padding: "12px 20px"
  card-surface:
    backgroundColor: "{colors.surface}"
    borderColor: "{colors.border}"
    borderWidth: "1px"
    rounded: "{rounded.xl}"
    padding: "{spacing.lg}"
  status-badge:
    rounded: "{rounded.full}"
    padding: "6px 12px"
    fontSize: "{typography.body-sm.fontSize}"
    fontWeight: "600"
---

# Design System dan UI/UX Guideline

## 1. Tujuan

Design system ini menjadi acuan visual untuk **Portal Pendanaan Sosial Pemkab Tulungagung**. Fokusnya adalah kepercayaan publik, kemudahan pengajuan kausa, kejelasan status, keamanan donasi, dan transparansi penggunaan dana.

Dokumen ini menggabungkan:

- wireframe tekstual;
- token warna, tipografi, spacing, dan radius;
- aturan komponen UI;
- aturan aksesibilitas;
- responsive behavior;
- state loading, empty, error, dan validation.

Gunakan Blade, Tailwind CSS, dan komponen reusable. Jangan membuat gaya baru yang bertentangan dengan token tanpa memperbarui dokumen ini.

## 2. Karakter Visual

Gaya visual harus terasa:

- resmi tetapi tidak kaku;
- terpercaya dan transparan;
- hangat untuk masyarakat;
- bersih dan mudah dipindai;
- konsisten antara portal publik dan dashboard internal.

Hindari tampilan seperti marketplace agresif, terlalu banyak animasi, atau penggunaan warna yang membuat status administrasi tidak jelas.

## 3. Warna

- **Primary hijau teal** digunakan untuk aksi utama, link aktif, progres positif, dan identitas pendanaan sosial.
- **Secondary hijau gelap** digunakan untuk navbar, heading penting, sidebar, dan panel resmi.
- **Background** digunakan sebagai kanvas halaman agar card tetap terlihat terangkat.
- **Surface** digunakan untuk card, form, tabel, dan modal.
- **Success** untuk berhasil/disetujui.
- **Warning** untuk menunggu verifikasi.
- **Orange** untuk perlu diperbaiki.
- **Error** untuk ditolak, gagal, dan kesalahan validasi.
- **Info** untuk informasi publik dan status dipublikasikan.

Jangan memakai warna sebagai satu-satunya penanda status. Badge wajib memiliki teks atau ikon pendukung.

## 4. Tipografi

- Heading memakai Plus Jakarta Sans.
- Body, label, tabel, dan form memakai Inter.
- Gunakan Bahasa Indonesia yang jelas dan konsisten.
- Heading harus singkat dan menjelaskan tujuan halaman.
- Label form tidak boleh hanya mengandalkan placeholder.
- Angka nominal dana harus mudah dibaca dan menggunakan format Rupiah.

## 5. Layout dan Responsive

- Gunakan pendekatan mobile-first.
- Container desktop maksimal sekitar 1200–1320px.
- Gunakan grid 12 kolom pada desktop dan satu kolom pada mobile.
- Dashboard menggunakan sidebar desktop dan drawer/menu pada mobile.
- Tabel lebar harus dapat digeser horizontal atau diubah menjadi card pada mobile.
- Tombol aksi utama tetap mudah dijangkau pada layar kecil.
- Spacing mengikuti skala 4, 8, 16, 24, 32, 48, dan 64px.

## 6. Wireframe Tekstual Halaman

### Landing page

```text
Navbar: logo/nama portal | Katalog Kausa | Transparansi | Login
Hero: tujuan portal + tombol Lihat Kausa
Ringkasan: jumlah kausa | dana terkumpul | laporan dipublikasikan
Katalog unggulan: card kausa dengan progres dan status
Cara kerja: Ajukan → Verifikasi → Donasi → Laporkan
Footer: kontak, kebijakan, dan informasi Pemkab
```

### Login dan registrasi

```text
Card form terpusat
- email
- password
- lupa password
- tombol masuk
- tautan registrasi
- pesan error validasi
```

Registrasi memilih kebutuhan akun secara jelas: Masyarakat/Donatur atau Instansi/OPD Pengaju Kausa.

### Dashboard Instansi

```text
Sidebar: Ringkasan | Profil Instansi | Kausa Saya | Laporan Dana
Topbar: nama user | notifikasi | logout
Ringkasan: total kausa | menunggu verifikasi | disetujui | dana terkumpul
Tabel/card kausa: judul | status | target | terkumpul | aksi
```

### Form pengajuan kausa

```text
Header: Ajukan Kausa Baru + indikator langkah
Bagian 1: data dasar
- judul
- kategori
- lokasi
- deskripsi
Bagian 2: target dan periode
- target_dana
- tanggal mulai
- tanggal berakhir
Bagian 3: dokumen dan media pendukung
Bagian 4: pratinjau dan konfirmasi
Footer: Simpan Draf | Kirim Pengajuan
```

Form harus menampilkan catatan Admin jika status `perlu_diperbaiki`.

### Dashboard Admin

```text
Sidebar: Ringkasan | Instansi | Kausa | Donasi | Laporan | Audit
Ringkasan KPI
Filter status dan tanggal
Tabel pengajuan
Detail kausa: dokumen, data pengaju, riwayat status, dan catatan
Aksi: Setujui | Minta Perbaikan | Tolak
```

Aksi destruktif atau perubahan status wajib memakai konfirmasi dan catatan jika diperlukan.

### Katalog dan detail kausa

```text
Katalog: pencarian | filter kategori/lokasi/status | card kausa
Card: gambar | judul | instansi | progres dana | sisa waktu | Lihat Detail
Detail: ringkasan | target | terkumpul | progres | profil instansi | tombol Donasi
Tab: Ringkasan | Laporan Transparansi | Riwayat Publik
```

Hanya kausa `disetujui` yang tampil di katalog publik.

### Donasi dan riwayat

```text
Form donasi: nominal | nama | email | opsi anonim | metode pembayaran
Ringkasan: kausa tujuan | nominal | status pembayaran
Riwayat: tanggal | kausa | nominal | status | detail
```

Status pembayaran tidak boleh dianggap berhasil hanya karena form browser selesai dikirim.

### Laporan dana dan transparansi

Instansi melihat form laporan, rincian penggunaan, bukti, periode, dan catatan perbaikan. Publik hanya melihat laporan berstatus `dipublikasikan`.

## 7. Status Badge

| Status | Warna | Teks |
|---|---|---|
| `draf` | netral | Draf |
| `menunggu_verifikasi` | kuning | Menunggu Verifikasi |
| `perlu_diperbaiki` | oranye | Perlu Diperbaiki |
| `disetujui` | hijau | Disetujui |
| `ditolak` | merah | Ditolak |
| `selesai` | hijau gelap | Selesai |
| `menunggu` | kuning | Menunggu |
| `berhasil` | hijau | Berhasil |
| `gagal` | merah | Gagal |
| `kedaluwarsa` | netral/merah | Kedaluwarsa |
| `dipublikasikan` | biru | Dipublikasikan |

## 8. Komponen

- **Button:** primary untuk aksi utama, secondary untuk aksi alternatif, danger untuk penolakan/penghapusan.
- **Form field:** label, input, helper text, error text, dan indikator wajib jika diperlukan.
- **Card:** ringkasan kausa, KPI, detail donasi, dan laporan.
- **Table:** header jelas, pagination, filter, sorting bila diperlukan, dan versi mobile.
- **Progress bar:** tampilkan nominal terkumpul, target, persentase, serta teks alternatif.
- **Modal:** hanya untuk konfirmasi atau detail singkat; jangan menyembunyikan form panjang di modal.
- **Toast/notifikasi:** konfirmasi aksi berhasil atau gagal tanpa menggantikan pesan validasi.

## 9. State Wajib

Setiap halaman data harus memiliki:

- loading state;
- empty state dengan penjelasan dan aksi berikutnya;
- validation error;
- server error dengan pesan aman;
- success feedback;
- unauthorized/forbidden state;
- not found state.

## 10. Aksesibilitas dan Keamanan UI

- Pastikan kontras teks minimal WCAG AA.
- Semua input memiliki label yang terhubung.
- Fokus keyboard harus terlihat.
- Tombol memiliki nama yang menjelaskan aksi.
- Jangan menampilkan password, token, data rekening, atau dokumen privat.
- Jangan menampilkan data donatur anonim sebagai identitas publik.
- Konfirmasi status pembayaran hanya berasal dari backend.
- Jangan menaruh credential atau data sensitif di JavaScript frontend.

## 11. Aturan yang Tidak Boleh Dilanggar

- Jangan menggunakan istilah `campaign` pada UI; gunakan **kausa** atau **program** sesuai konteks.
- Jangan menampilkan kausa yang belum disetujui di katalog publik.
- Jangan mencampur label role teknis dengan label pengguna; gunakan `Admin Pemkab`, `Instansi/OPD Pengaju Kausa`, dan `Masyarakat/Donatur`.
- Jangan membuat status baru tanpa memperbarui `docs/14-Status-and-Workflow-Rules.md`.
- Jangan membuat komponen visual yang berbeda-beda untuk fungsi yang sama.
- Jangan menganggap payment gateway, SSO, QRIS, WhatsApp, SMTP, atau BSrE aktif sebelum integrasi resmi tersedia.
