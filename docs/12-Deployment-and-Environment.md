# Deployment and Environment

## Environment
- `local`: pengembangan dan data simulasi.
- `staging`: pengujian integrasi sebelum produksi.
- `production`: layanan resmi dengan credential dan endpoint resmi.

## Aturan konfigurasi
- `.env` tidak boleh di-commit.
- Gunakan `.env.example` tanpa nilai rahasia.
- Credential ditulis sebagai `[REDACTED]` dalam dokumentasi.
- `APP_DEBUG=true` hanya untuk local.
- `APP_ENV=production` wajib memakai `APP_DEBUG=false`.

## Komponen
- Web server menjalankan aplikasi Laravel melalui PHP 8.3+.
- Database menggunakan PostgreSQL/Supabase sesuai environment.
- File privat menggunakan Laravel Storage.
- Queue, cache, session, dan mail dikonfigurasi per environment.
- Integrasi eksternal wajib memiliki mode sandbox atau adapter simulasi.

## Deployment checklist
- Backup database tersedia.
- Migration diperiksa dan dijalankan secara terukur.
- Tidak menggunakan `migrate:fresh` pada database bersama.
- Storage link dan permission diverifikasi.
- Queue worker dan scheduler diperiksa jika digunakan.
- Health check, log, dan rollback plan tersedia.
- Credential produksi tidak pernah dimasukkan ke source code.
