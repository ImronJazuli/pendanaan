# Implementasi Lengkap Gap Sprint A hingga E dan Resolusi Temuan Keamanan

Implementasi Sprint A hingga E pada Portal Pendanaan Sosial Pemkab Tulungagung mengintegrasikan subsistem notifikasi in-app terpusat, penanganan webhook payment gateway resmi Midtrans dengan pengecualian CSRF dan verifikasi tanda tangan digital waktu-konstan, alur donasi transfer manual Kasda Bank Jatim dengan antrean kurasi admin, koneksi data operasional riil pada tab transparansi dan riwayat donatur dengan penyamaran 'Hamba Allah', serta penguatan otorisasi via `KausaPolicy` dan penambahan 8 suite pengujian fitur otomatis. Perubahan susulan pada commit `2c0b225` berhasil menuntaskan seluruh 6 temuan keamanan dan integritas data dari tinjauan sebelumnya, meliputi penguncian transisi status pembayaran manual, eliminasi celah konkurensi saldo melalui kombinasi row lock dan atomic increment database, migrasi berkas bukti bayar ke disk privat lokal yang terotorisasi, mitigasi open redirect pada penandaan notifikasi, serta validasi signature constant-time `hash_equals`. Seluruh 36 kasus uji baru pada suite pengujian berjalan sukses tanpa regresi fungsional maupun pelanggaran standar formatting Laravel Pint.

Watch for: Tidak ada isu pemblokir tersisa; sistem kini menerapkan fail-closed pada pengecualian transisi status ilegal (**confirmed**), penguncian pesimistik pada mutasi saldo donasi (**confirmed**), dan streaming berkas bukti transfer tertutup (**confirmed**).

**Verdict**: APPROVED

## High-level view

Subsistem notifikasi in-app mengabstraksi pengiriman pesan lintas siklus hidup kausa, verifikasi legalitas, LPJ, dan konfirmasi donasi melalui `NotifikasiService::kirim()`. Akses kotak masuk dibatasi ketat per pengguna terautentikasi, dengan pengalihan pasca-pembacaan yang membatasi target URL hanya pada jalur internal lokal aplikasi guna mencegah eksploitasi open redirect.

Integrasi webhook Midtrans beroperasi di bawah rute terdaftar dengan pengecualian token CSRF di middleware bootstrap, menegakkan pengecekan konfigurasi server key sebelum kalkulasi serta membandingkan signature SHA512 menggunakan `hash_equals()` untuk mengeliminasi serangan saluran samping berbasis waktu. Idempotensi dua lapis dan penguncian pesimistik `lockForUpdate()` pada entitas donasi dan kausa menjamin akurasi mutasi dana ketika terjadi pemanggilan callback duplikat atau konkurensi transaksi.

Alur donasi manual Kasda Bank Jatim melengkapi sistem dengan penegakan status machine yang ketat, di mana pengunggahan bukti bayar dilarang keras untuk transaksi yang telah berstatus final (sukses, kedaluwarsa, atau dibatalkan). Berkas bukti transfer disimpan secara terisolasi pada storage privat lokal dan hanya dapat diakses melalui endpoint streaming terotorisasi khusus admin atau pemilik donasi sah, sementara tindakan penolakan admin mewajibkan catatan argumentatif dan menolak pembatalan sepihak atas donasi yang telah berstatus sukses.

Katalog detail kausa sepenuhnya terhubung ke data operasional riil dari relasi `logTransparansi` yang telah dipublikasikan dan transaksi donasi sukses, menyamarkan identitas donatur bertanda anonim sebagai 'Hamba Allah' serta mendukung pengunggahan galeri foto dokumentasi lapangan. Batas otorisasi multi-lembaga diperkuat lewat registrasi `KausaPolicy` pada Gate, didukung cakupan pengujian komprehensif 100% lulus dan pembaruan menyeluruh pada matriks keterlacakan spesifikasi.

<details>
<summary>Issues (0)</summary>

Seluruh 6 temuan dari tinjauan putaran sebelumnya telah diselesaikan secara tuntas dan diverifikasi melalui penambahan kasus uji otomatis:
1. **Validasi Transisi Status Upload Bukti Manual** — Telah diselesaikan: `DonasiController@uploadBukti` memvalidasi status awal dan menolak status final (`abort(422)`).
2. **Validasi Status Penolakan Pembayaran Manual** — Telah diselesaikan: `AdminDashboardController@rejectManual` mencegah penolakan atas donasi yang telah sukses (`abort(422)`).
3. **Race Condition Akumulasi Saldo Kausa** — Telah diselesaikan: `Kausa::tambahDanaTerkumpul()` menggunakan operasi `$this->increment('dana_terkumpul', $nominal)` didukung penguncian pesimistik `lockForUpdate()`.
4. **Verifikasi Signature Webhook Constant-Time** — Telah diselesaikan: `MidtransWebhookController` menggunakan `hash_equals()` dan memeriksa ketersediaan server key.
5. **Mitigasi Open Redirect pada NotifikasiController** — Telah diselesaikan: `NotifikasiController@markRead` membatasi pengalihan hanya ke URI lokal atau domain host aplikasi.
6. **Isolasi Berkas Bukti Transfer pada Storage Privat** — Telah diselesaikan: Berkas disimpan pada disk `local` dan disajikan lewat endpoint streaming terproteksi otorisasi peran.

</details>

<details>
<summary>Details</summary>

### State Machine Pembayaran Manual dan Proteksi Transisi Status

Verifikasi pembayaran manual transfer Kasda Pemkab Tulungagung pada Sprint B kini menerapkan penjagaan status mesin yang fail-closed. Pada `DonasiController@uploadBukti`, request pengunggahan bukti bayar diperiksa terhadap status terkini transaksi sebelum berkas diproses ke disk:

```php
if ($donasi->status === Donasi::STATUS_SUCCESS || $donasi->status === 'berhasil') {
    abort(422, 'Donasi sudah berstatus berhasil dan tidak dapat mengunggah bukti pembayaran lagi.');
}

if (in_array($donasi->status, [Donasi::STATUS_EXPIRED, 'kadaluarsa', 'dibatalkan'])) {
    abort(422, 'Donasi sudah tidak aktif.');
}
```

Pengecekan ini menutup celah manipulasi status dari transaksi yang telah diverifikasi kembali ke status `menunggu_verifikasi_manual`, menggagalkan potensi rollback ilegal dan mitigasi penggandaan kredit saldo kausa (**confirmed**).

Simetris dengan aturan tersebut, `AdminDashboardController@rejectManual` menerapkan validasi eksplisit guna mencegah pembatalan atas donasi yang telah berstatus sukses:

```php
if ($donasi->status === Donasi::STATUS_SUCCESS || $donasi->status === 'berhasil') {
    abort(422, 'Donasi yang sudah berstatus berhasil tidak dapat ditolak.');
}
```

Ketentuan ini menjamin integritas buku besar penerimaan dana kausa tidak dapat diputus sepihak melalui aksi penolakan tanpa mekanisme pembukuan retur atau rekonsiliasi kas resmi.

### Konkurensi Akumulasi Saldo Kausa dan Penguncian Pesimistik

Pembaruan saldo penerimaan donasi pada `Kausa::tambahDanaTerkumpul()` telah dialihkan dari manipulasi nilai atribut di memori aplikasi ke pernyataan SQL atomik langsung di tingkat mesin basis data:

```php
public function tambahDanaTerkumpul(float $nominal): void
{
    $this->increment('dana_terkumpul', $nominal);
    $this->refresh();
}
```

Di samping eksekusi atomik pada level database, seluruh alur yang memicu penambahan dana—persetujuan manual admin (`AdminDashboardController@approveManual`), pembayaran simulasi gateway (`DonasiController@simulate`), dan callback webhook (`MidtransWebhookController@handle`)—menjalankan penguncian baris pesimistik terkoordinasi:

```php
$kausaLocked = Kausa::where('id', $donasiLocked->kausa_id)->lockForUpdate()->first();
$kausaLocked->tambahDanaTerkumpul((float) $donasiLocked->nominal);
```

Dengan mengunci baris `kausa` bersamaan dengan baris `donasi` di dalam `DB::transaction()`, risiko lost update saat terjadi serbuan donasi secara simultan tereliminasi sepenuhnya (**confirmed**).

### Verifikasi Signature Webhook Midtrans dan Keamanan Saluran Samping

Endpoint webhook pada `MidtransWebhookController` mematuhi spesifikasi kriptografi proteksi saluran samping dengan menerapkan pembanding tanda tangan digital konstan-waktu (`hash_equals`):

```php
$serverKey = (string) config('services.midtrans.server_key');
if (empty($serverKey)) {
    return response()->json([
        'status' => 'error',
        'message' => 'Midtrans server key is not configured.',
    ], 500);
}

$expectedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);
if (! hash_equals($expectedSignature, $signatureKey)) {
    return response()->json([
        'status' => 'error',
        'message' => 'Invalid signature key.',
    ], 403);
}
```

Pemeriksaan awal terhadap string kosong pada konfigurasi `serverKey` mencegah kegagalan validasi tak terduga pada lingkungan tanpa kredensial aktif, sementara fungsi `hash_equals()` menihilkan peluang serangan timing attack terhadap hash SHA512 (**confirmed**). Idempotensi dua lapis—sebelum membuka blok transaksi dan di dalam transaksi berkunci—memastikan penerimaan callback berulang Midtrans ditanggapi dengan HTTP 200 tanpa risiko kredit ganda.

### Subsistem Notifikasi In-App dan Proteksi Open Redirect

Arsitektur notifikasi in-app pada `NotifikasiService::kirim()` berhasil mengintegrasikan seluruh titik mutasi status sistem (persetujuan, revisi, penolakan kausa/LPJ, serta konfirmasi pembayaran donasi). Pengelolaan pembacaan pesan pada `NotifikasiController@markRead` menegakkan otorisasi kepemilikan (`abort_unless($notifikasi->user_id === $request->user()->id, 403)`), serta memvalidasi parameter tujuan pengalihan (`redirect_to` dan `notifikasi->tautan`):

```php
if ($request->filled('redirect_to')) {
    $redirectTo = (string) $request->input('redirect_to');
    if (str_starts_with($redirectTo, '/') && ! str_starts_with($redirectTo, '//')) {
        return redirect($redirectTo);
    }

    $parsed = parse_url($redirectTo);
    if (isset($parsed['host']) && $parsed['host'] === $request->getHost()) {
        return redirect($redirectTo);
    }
}
```

Pola sanitasi ini memblokir skema manipulasi URI eksternal maupun protocol-relative URL (`//evil.com`), mengarahkan request ke fallback aman saat parameter tidak valid terdeteksi (**confirmed**).

### Isolasi Akses Bukti Transfer Finansial pada Private Storage

Penyimpanan berkas bukti pembayaran manual donatur pada `DonasiController@uploadBukti` dialihkan dari direktori publik ke disk penyimpanan privat lokal (`store('bukti-manual', 'local')`). Berkas bukti tidak lagi dapat diakses langsung melalui URL publik statis tanpa proteksi.

Sistem menyediakan dua rute terproteksi untuk streaming berkas bukti transfer:
1. `GET /dashboard/admin/donasi/{donasi}/bukti` (`admin.donasi.bukti`) — Dibatasi hanya untuk administrator Pemkab melalui middleware `auth.admin`.
2. `GET /donasi/{kode}/bukti` (`donasi.bukti`) — Dibatasi hanya untuk administrator, donatur pemilik akun terdaftar, atau sesi donatur tamu pemilik token donasi aktif (`session('donasi_token_'.$kode)`).

Akses dari pihak ketiga yang tidak berwenang ditolak seketika dengan respon HTTP 403 (**confirmed**), melindungi kerahasiaan identitas finansial dan nomor rekening perbankan masyarakat.

### Deserialisasi Transparansi Riil, Masking Donatur, dan Galeri Foto

Halaman publik detail kausa (`resources/views/kausa/show.blade.php`) dan controller pendukungnya (`KausaController@show`) mengonsumsi data operasional riil dari basis data. Query membatasi relasi donasi hanya pada status `success` atau `berhasil`, serta membatasi `logTransparansi` hanya pada rekaman yang bertanda `dipublikasikan = true`.

Identitas donatur yang memilih opsi anonim disamarkan secara konsisten sebagai 'Hamba Allah' pada kartu riwayat donatur dan inisial avatar ('HA'), meniadakan kebocoran informasi pribadi donatur. Formulir pengajuan kausa (`SimpanKausaRequest`) dan persistensi `KausaController@store` mengelola pengunggahan multi-foto dokumentasi lapangan (`foto_kausa[]`), yang disajikan pada antarmuka publik via galeri Alpine.js dengan penanganan fallback placeholder beresolusi tinggi saat arsip foto belum diunggah.

### Penegakan KausaPolicy, Isolasi Multi-Tenant, dan Matriks Pengujian

Otorisasi kepemilikan kausa dikonsolidasikan melalui `KausaPolicy` yang didaftarkan pada Gate Laravel (`AppServiceProvider`). Instansi dilarang menyunting atau menghapus kausa milik instansi lain, serta dilarang menyunting kausa miliknya sendiri apabila status program telah memasuki tahap `disetujui` atau `selesai`.

Cakupan pengujian otomatis bertambah 8 file feature test komprehensif (`NotifikasiTest`, `WebhookMidtransTest`, `PembayaranManualTest`, `KausaShowDetailTest`, `KausaPolicyTest`, `RoleAccessTest`, `KausaWorkflowStatusTest`, dan `TransparansiPublikTest`). Seluruh 36 pengujian lulus dengan 115 asersi valid, dokumen `docs/09-Traceability-Matrix.md` telah memetakan kebutuhan PRD hingga baris pengujian, dan seluruh file PHP yang dimodifikasi lolos verifikasi standar penataan kode Laravel Pint.

</details>

<details>
<summary>File map</summary>

- `app/Services/NotifikasiService.php` — Fasad pembantu pengiriman notifikasi in-app terpusat ke tabel `notifikasi`.
- `app/Http/Controllers/NotifikasiController.php` — Pengelolaan kotak masuk notifikasi, proteksi otorisasi pembacaan, dan mitigasi open redirect.
- `app/Http/Controllers/MidtransWebhookController.php` — Handler callback resmi Midtrans dengan validasi signature `hash_equals`, idempotensi ganda, dan locking kausa.
- `app/Http/Controllers/DonasiController.php` — Inisiasi donasi, simulasi pembayaran, validasi transisi status bukti manual, penyimpanan disk lokal, dan endpoint streaming terproteksi.
- `app/Http/Controllers/Dashboard/AdminDashboardController.php` — Kurasi verifikasi manual admin, penolakan donasi berargumen, pencegahan penolakan donasi sukses, dan streaming bukti admin.
- `app/Http/Controllers/KausaController.php` — Eager loading relasi transparansi publik terfilter, donatur sukses, dan persistensi multi-foto galeri kausa.
- `app/Policies/KausaPolicy.php` — Penegakan hak akses kepemilikan instansi dan pembatasan mutasi berbasis status workflow kausa.
- `app/Providers/AppServiceProvider.php` — Registrasi `KausaPolicy` pada Gate Laravel.
- `app/Http/Requests/SimpanKausaRequest.php` — Validasi upload berkas multi-foto galeri kausa (`foto_kausa[]`).
- `app/Models/Donasi.php` — Konstanta status pembayaran manual dan atribut bukti transfer.
- `app/Models/Kausa.php` — Persistensi penambahan dana atomik via `$this->increment('dana_terkumpul', $nominal)`.
- `app/Models/User.php` — Helper otorisasi peran pengguna `hasRole()` dan `isAdmin()`.
- `bootstrap/app.php` — Pengecualian CSRF untuk rute webhook Midtrans.
- `config/services.php` — Konfigurasi kredensial payment gateway Midtrans.
- `database/migrations/2026_10_08_000001_add_bukti_manual_to_donasi_table.php` — Skema penambahan kolom bukti transfer manual dan catatan admin.
- `resources/views/notifikasi/index.blade.php` — Antarmuka halaman kotak masuk notifikasi in-app.
- `resources/views/kausa/show.blade.php` — Integrasi tab transparansi riil, donatur sukses 'Hamba Allah', opsi transfer manual Kasda, dan thumbnail galeri foto.
- `resources/views/donasi/payment.blade.php` — Formulir transfer rekening Kasda Bank Jatim, upload bukti bayar, dan tautan streaming bukti terproteksi.
- `resources/views/dashboard/admin/donasi.blade.php` — Antrean verifikasi manual admin, modal penolakan berargumen, dan tautan tinjauan bukti terproteksi.
- `resources/views/layouts/dashboard-instansi.blade.php` — Counter unread notifikasi dinamis pada layout instansi.
- `resources/views/layouts/navigation.blade.php` — Counter unread notifikasi pada navigasi pengguna donatur.
- `docs/09-Traceability-Matrix.md` — Pembaruan matriks keterlacakan spesifikasi untuk seluruh fitur Sprint A-E.
- `TASKS.md` — Pembaruan status penyelesaian task plan Sprint A-E.
- `tests/Feature/NotifikasiTest.php` — Kasus uji pengiriman notifikasi, otorisasi baca, dan proteksi open redirect.
- `tests/Feature/WebhookMidtransTest.php` — Kasus uji verifikasi signature SHA512, penanganan ketiadaan server key, idempotensi, dan kedaluwarsa.
- `tests/Feature/PembayaranManualTest.php` — Kasus uji alur unggah bukti lokal, persetujuan admin atomik, penolakan berargumen, penjagaan transisi status, dan otorisasi streaming bukti.
- `tests/Feature/KausaShowDetailTest.php` — Kasus uji isolasi data transparansi kausa, masking 'Hamba Allah', dan unggah multi-foto galeri.
- `tests/Feature/KausaPolicyTest.php` — Kasus uji kepemilikan antar-instansi dan restriksi status kausa.
- `tests/Feature/RoleAccessTest.php` — Kasus uji partisi akses route antar-peran (Admin, Instansi, Donatur, Tamu).
- `tests/Feature/KausaWorkflowStatusTest.php` — Kasus uji transisi status kausa dan isolasi katalog publik.
- `tests/Feature/TransparansiPublikTest.php` — Kasus uji proteksi kebocoran draf laporan pada portal transparansi publik.

Pointer ke git diff: `git diff 9eb303d HEAD`
</details>
