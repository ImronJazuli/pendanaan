# Modul Autentikasi Portal Pendanaan Sosial Pemkab Tulungagung

## Overview

Modul autentikasi untuk **SI-PEDULI Tulungagung** - Portal Pendanaan Sosial berbasis Laravel dengan dukungan multi-role authentication:
- **Donatur**: Masyarakat umum (dengan Google OAuth)
- **Instansi**: OPD/Lembaga sosial (dengan Email atau NPWP)
- **Admin**: Admin Pemkab (guard terpisah)

## Stack Teknologi

- Laravel 13, PHP 8.3+
- PostgreSQL via Supabase
- Blade + Tailwind CSS + Vite
- Laravel Socialite (Google OAuth)
- Guard: `web` (donatur, instansi) + `admin` (admin pemkab)

## Struktur Database

### Tabel `users`
Kolom baru yang ditambahkan:
- `role` (VARCHAR 30, default: 'donatur', indexed) - kolom baru untuk auth
- `nik` (VARCHAR 16, nullable, unique)
- `phone_number` (VARCHAR 20, nullable)
- `sso_id` (VARCHAR, nullable, unique)
- `google_id` (VARCHAR, nullable, unique)
- `avatar` (VARCHAR, nullable)
- `npwp` (VARCHAR 20, nullable, unique) - format: XX.XXX.XXX.X-XXX.XXX
- `address` (TEXT, nullable)

**Catatan**: Kolom `peran` (lama) tetap ada untuk backward compatibility dengan seeder existing.

### Tabel `admins` (Baru)
- `id` (BIGINT, primary key)
- `name` (VARCHAR)
- `email` (VARCHAR, unique)
- `password` (VARCHAR, hashed)
- `remember_token` (VARCHAR 100, nullable)
- `created_at`, `updated_at` (TIMESTAMP)

## Guards & Providers

```php
'guards' => [
    'web'   => ['driver' => 'session', 'provider' => 'users'],   // donatur + instansi
    'admin' => ['driver' => 'session', 'provider' => 'admins'],  // admin pemkab
],
'providers' => [
    'users'  => ['driver' => 'eloquent', 'model' => App\Models\User::class],
    'admins' => ['driver' => 'eloquent', 'model' => App\Models\Admin::class],
],
```

## Routes

### Auth Page
- `GET /login` → Halaman login tunggal dengan tab switcher

### Donatur Routes
- `POST /donatur/register` → Registrasi donatur
- `POST /donatur/login` → Login donatur
- `POST /donatur/logout` → Logout donatur
- `GET /donatur/auth/google` → Redirect ke Google OAuth
- `GET /donatur/auth/google/callback` → Callback Google OAuth
- `GET /donatur/email/verify/{id}/{hash}` → Verifikasi email
- `POST /donatur/email/resend` → Kirim ulang email verifikasi

### Instansi Routes
- `POST /instansi/register` → Registrasi instansi
- `POST /instansi/login` → Login instansi (email atau NPWP)
- `POST /instansi/logout` → Logout instansi
- `GET /instansi/email/verify/{id}/{hash}` → Verifikasi email
- `POST /instansi/email/resend` → Kirim ulang email verifikasi

### Admin Routes
- `POST /admin/login` → Login admin
- `POST /admin/logout` → Logout admin

## Controllers

### AuthPageController
- `index()` → Menampilkan halaman login dengan tab dan mode yang sesuai

### DonaturAuthController
- `register()` → Handle registrasi donatur
- `login()` → Handle login donatur
- `redirectToGoogle()` → Redirect ke Google OAuth
- `handleGoogleCallback()` → Handle callback dari Google
- `verifyEmail()` → Verifikasi email donatur
- `resendVerification()` → Kirim ulang email verifikasi
- `logout()` → Logout donatur

### InstansiAuthController
- `register()` → Handle registrasi instansi (support email atau NPWP)
- `login()` → Handle login instansi
- `verifyEmail()` → Verifikasi email instansi
- `resendVerification()` → Kirim ulang email verifikasi
- `logout()` → Logout instansi

### AdminAuthController
- `login()` → Handle login admin
- `logout()` → Logout admin

## Middleware

### EnsureDonatur (`auth.donatur`)
Memastikan user yang login adalah donatur dengan `role === 'donatur'`.

### EnsureInstansi (`auth.instansi`)
Memastikan user yang login adalah instansi dengan `role === 'instansi'`.

### EnsureAdmin (`auth.admin`)
Memastikan user yang login adalah admin menggunakan guard `admin`.

## Views

### `layouts/guest.blade.php`
Layout untuk halaman autentikasi dengan:
- Inter/Figtree font untuk body
- Plus Jakarta Sans untuk heading
- Lucide icons
- Responsive design

### `auth/login.blade.php`
Halaman login dengan:
- **Desain 2 kolom**: Form di kiri, hero image di kanan
- **Tab Switcher**: Donatur, Instansi, Admin
- **Mode Switcher**: Sign In / Register (untuk donatur dan instansi)
- **Animasi**: fadeSlideIn dan slideRightIn
- **Flash Messages**: Berbagai status notifikasi
- **Form Features**:
  - Toggle show/hide password
  - Remember me checkbox
  - Email/NPWP toggle untuk instansi
  - Auto-format NPWP
  - Google OAuth button untuk donatur

### `auth/verify-email.blade.php`
Halaman email verification dengan:
- Icon dan judul centered
- Tombol kirim ulang verifikasi
- Tombol logout

## Features

### 1. Google OAuth untuk Donatur
- Menggunakan Laravel Socialite
- Auto-create user jika belum ada
- Auto-verify email untuk user baru dari Google
- Update `google_id` dan `avatar` untuk user existing

### 2. Registrasi Instansi Flexible
Dua metode registrasi:
- **Email**: Memerlukan email verification
- **NPWP**: Langsung verified, menunggu approval admin

### 3. Auto-format NPWP
JavaScript auto-format input NPWP ke format: `XX.XXX.XXX.X-XXX.XXX`

### 4. Email Verification
- Laravel built-in email verification
- Signed URL untuk keamanan
- Support resend verification email
- Throttle: 6 requests per menit

### 5. Multi-Guard Authentication
- Guard `web` untuk donatur dan instansi
- Guard `admin` terpisah untuk admin pemkab
- Redirect logic berdasarkan role

## Configuration

### .env Variables
```env
# Google OAuth
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=https://yourdomain.com/donatur/auth/google/callback

# Email (untuk verification)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@tulungagung.go.id"
MAIL_FROM_NAME="${APP_NAME}"
```

## Seeder

### AdminSeeder
Membuat admin default:
- Email: `admin@tulungagung.go.id`
- Password: `GantiPasswordIni2024!`

Jalankan dengan:
```bash
php artisan db:seed --class=AdminSeeder
```

## Testing

### Login Testing
1. **Donatur**:
   - Email: `donatur@example.test`
   - Password: `password`

2. **Instansi**:
   - Email: `instansi@example.test`
   - Password: `password`

3. **Admin**:
   - Email: `admin@tulungagung.go.id`
   - Password: `GantiPasswordIni2024!`

### Route Testing
```bash
php artisan route:list --except-vendor | findstr login
php artisan route:list --except-vendor | findstr donatur
php artisan route:list --except-vendor | findstr instansi
php artisan route:list --except-vendor | findstr admin
```

## Security Features

1. **CSRF Protection**: Semua form menggunakan `@csrf`
2. **Password Hashing**: Menggunakan Laravel `Hash::make()`
3. **Signed URLs**: Email verification menggunakan signed routes
4. **Throttling**: Rate limiting untuk resend verification
5. **Guard Separation**: Admin menggunakan guard dan tabel terpisah
6. **Session Management**: `session()->regenerate()` setelah login
7. **Middleware**: Role-based access control

## Styling & UI

### Warna Tailwind
```
primary.DEFAULT = #087F5B      primary.hover = #066A4C
secondary.DEFAULT = #123B32    tulungagung.bg = #F6F8F7
tulungagung.text = #17211E     tulungagung.muted = #73817C
tulungagung.border = #D9E2DE   surface.DEFAULT = #FFFFFF
surface.muted = #EEF3F1
```

### Animations
- `animate-element`: fadeSlideIn (opacity + blur + translateY)
- `animate-slide-right`: slideRightIn (opacity + blur + translateX)
- Delay classes: `animate-delay-100` hingga `animate-delay-600`

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Auth/
│   │       ├── AuthPageController.php
│   │       ├── DonaturAuthController.php
│   │       ├── InstansiAuthController.php
│   │       └── AdminAuthController.php
│   └── Middleware/
│       ├── EnsureDonatur.php
│       ├── EnsureInstansi.php
│       └── EnsureAdmin.php
├── Models/
│   ├── User.php (updated)
│   └── Admin.php (new)
config/
├── auth.php (updated)
└── services.php (updated)
database/
├── migrations/
│   ├── 2026_10_06_063503_add_auth_fields_to_users_table.php
│   └── 2026_10_06_063527_create_admins_table.php
└── seeders/
    ├── AdminSeeder.php (new)
    └── DatabaseSeeder.php (updated)
resources/
└── views/
    ├── layouts/
    │   └── guest.blade.php (replaced)
    └── auth/
        ├── login.blade.php (replaced)
        └── verify-email.blade.php (new)
routes/
├── auth.php (replaced)
└── web.php (updated)
```

## Migration Commands

```bash
# Jalankan migrations
php artisan migrate

# Jalankan seeder
php artisan db:seed --class=AdminSeeder

# Atau jalankan semua seeder
php artisan db:seed

# Format code
vendor/bin/pint --dirty --format agent
```

## Troubleshooting

### Issue: "Class 'Socialite' not found"
```bash
composer require laravel/socialite
php artisan config:clear
```

### Issue: Email verification tidak jalan
Pastikan `.env` sudah diset dengan benar untuk `MAIL_*` variables.

### Issue: Admin tidak bisa login
Pastikan sudah menjalankan:
```bash
php artisan db:seed --class=AdminSeeder
```

### Issue: Google OAuth redirect error
Pastikan `GOOGLE_REDIRECT_URI` di `.env` sama persis dengan yang didaftarkan di Google Cloud Console.

## Next Steps

1. ✅ Setup Google OAuth credentials di Google Cloud Console
2. ✅ Siapkan hero image untuk halaman login: `public/images/hero-login.jpg`
3. ✅ Test semua flow autentikasi
4. ⏳ Implementasi email notification template custom
5. ⏳ Implementasi forgot password feature
6. ⏳ Implementasi profile management

## Notes

- Kolom `peran` (lama) tetap dipertahankan untuk backward compatibility
- Kolom `role` (baru) digunakan untuk authentication baru
- Model `Institution` menggunakan nama `Instansi` sesuai konvensi project
- Guard `admin` terpisah dari guard `web` untuk keamanan
- Middleware baru `auth.donatur`, `auth.instansi`, `auth.admin` untuk role-based access

---

**Created**: 2026-10-06  
**Author**: Kiro Agent  
**Project**: SI-PEDULI Tulungagung
