<?php

namespace Database\Seeders;

use App\Models\Instansi;
use App\Models\KategoriKausa;
use App\Models\Kausa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil AdminSeeder terlebih dahulu
        $this->call(AdminSeeder::class);

        // Kategori Kausa
        KategoriKausa::firstOrCreate(
            ['nama' => 'Bencana alam'],
            ['slug' => 'bencana-alam', 'deskripsi' => 'Bantuan untuk korban bencana alam seperti banjir, gempa, longsor.']
        );
        KategoriKausa::firstOrCreate(
            ['nama' => 'Panti asuhan & lembaga kesejahteraan'],
            ['slug' => 'panti-asuhan-lembaga-kesejahteraan', 'deskripsi' => 'Dukungan untuk panti asuhan, rumah jompo, dan lembaga sosial.']
        );
        KategoriKausa::firstOrCreate(
            ['nama' => 'Sarana tempat ibadah'],
            ['slug' => 'sarana-tempat-ibadah', 'deskripsi' => 'Pembangunan atau perbaikan tempat ibadah.']
        );
        KategoriKausa::firstOrCreate(
            ['nama' => 'Lansia dan dhuafa'],
            ['slug' => 'lansia-dhuafa', 'deskripsi' => 'Bantuan langsung untuk lansia dan masyarakat tidak mampu.']
        );

        // Admin Pemkab
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@pemkab.test'],
            [
                'name' => 'Admin Pemkab Tulungagung',
                'password' => Hash::make('password'),
                'peran' => 'admin',
                'status' => 'aktif',
            ]
        );

        // Instansi User Default & Contoh
        $instansiUser = User::firstOrCreate(
            ['email' => 'instansi@example.test'],
            [
                'name' => 'Dinas Sosial Pemkab',
                'password' => Hash::make('password'),
                'role' => 'instansi',
                'peran' => 'institution_user',
                'email_verified_at' => now(),
                'status' => 'aktif',
            ]
        );

        Instansi::firstOrCreate(
            ['user_id' => $instansiUser->id],
            [
                'jenis' => 'OPD',
                'nama' => 'Dinas Sosial Pemkab Tulungagung',
                'nomor_registrasi' => '123.456.789',
                'status_verifikasi' => 'terverifikasi',
                'alamat' => 'Jl. Merdeka No. 1, Tulungagung',
                'nomor_telepon' => '(0355) 321000',
                'terverifikasi_pada' => now(),
            ]
        );

        // Akun-akun Instansi Tambahan
        $institutions = [
            [
                'email' => 'dinsos@tulungagung.go.id',
                'name' => 'Dinas Sosial Kabupaten Tulungagung',
                'jenis' => 'OPD',
                'reg' => 'OPD-TA-001/DINSOS',
                'phone' => '081234567891',
                'alamat' => 'Jl. Pahlawan No. 20, Tulungagung',
            ],
            [
                'email' => 'bpbd@tulungagung.go.id',
                'name' => 'Badan Penanggulangan Bencana Daerah (BPBD) Tulungagung',
                'jenis' => 'OPD',
                'reg' => 'OPD-TA-042/BPBD',
                'phone' => '081234567892',
                'alamat' => 'Jl. Supriadi No. 10, Tulungagung',
            ],
            [
                'email' => 'panti.bunda@gmail.com',
                'name' => 'Panti Asuhan Bunda Kasih',
                'jenis' => 'lembaga_sosial',
                'reg' => 'LKS-TA-015/PABK',
                'phone' => '081234567893',
                'alamat' => 'Jl. Merak No. 5, Tulungagung',
            ],
        ];

        foreach ($institutions as $inst) {
            $user = User::firstOrCreate(
                ['email' => $inst['email']],
                [
                    'name' => $inst['name'],
                    'role' => 'instansi',
                    'peran' => 'institution_user',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'status' => 'aktif',
                    'phone_number' => $inst['phone'],
                    'address' => $inst['alamat'],
                ]
            );

            Instansi::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'jenis' => $inst['jenis'],
                    'nama' => $inst['name'],
                    'nomor_registrasi' => $inst['reg'],
                    'status_verifikasi' => 'terverifikasi',
                    'alamat' => $inst['alamat'],
                    'nomor_telepon' => $inst['phone'],
                    'terverifikasi_pada' => now(),
                ]
            );
        }

        // Donatur User Default & Tambahan
        User::firstOrCreate(
            ['email' => 'donatur@example.test'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'donatur',
                'peran' => 'donatur',
                'email_verified_at' => now(),
                'status' => 'aktif',
            ]
        );

        User::firstOrCreate(
            ['email' => 'donatur@gmail.com'],
            [
                'name' => 'Donatur Masyarakat Tulungagung',
                'password' => Hash::make('password'),
                'role' => 'donatur',
                'peran' => 'donatur',
                'email_verified_at' => now(),
                'status' => 'aktif',
            ]
        );

        // Kausa Test untuk Katalog
        $instansi = $instansiUser->instansi;

        $kausa1 = Kausa::firstOrCreate(
            ['slug' => 'bantuan-korban-banjir-besuki'],
            [
                'instansi_id' => $instansi->id,
                'kategori_kausa_id' => KategoriKausa::where('nama', 'Bencana alam')->first()->id,
                'judul' => 'Bantuan Korban Banjir Besuki',
                'ringkasan' => 'Banjir besar mengakibatkan ratusan rumah terendam dan ribuan korban membutuhkan bantuan mendesak.',
                'deskripsi' => 'Kabupaten Tulungagung terkena dampak bencana banjir besar. Ribuan warga terutama di Kecamatan Besuki terkena dampak serius.',
                'lokasi' => 'Kecamatan Besuki, Kabupaten Tulungagung',
                'target_dana' => 100000000,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addDays(60),
                'status' => 'disetujui',
            ]
        );

        if ($kausa1->riwayatStatus->count() === 0) {
            $kausa1->riwayatStatus()->create([
                'user_id' => $adminUser->id,
                'status_baru' => 'disetujui',
                'catatan' => 'Pengajuan kausa telah diverifikasi dan disetujui oleh Admin untuk dipublikasikan.',
            ]);
        }

        $kausa2 = Kausa::firstOrCreate(
            ['slug' => 'renovasi-panti-asuhan-tunas-harapan'],
            [
                'instansi_id' => $instansi->id,
                'kategori_kausa_id' => KategoriKausa::where('nama', 'Panti asuhan & lembaga kesejahteraan')->first()->id,
                'judul' => 'Renovasi Panti Asuhan Tunas Harapan',
                'ringkasan' => 'Panti asuhan membutuhkan renovasi mendesak untuk meningkatkan standar hidup 50 anak yatim piatu.',
                'deskripsi' => 'Panti Asuhan Tunas Harapan telah melayani lebih dari 50 anak yatim dan piatu selama 15 tahun.',
                'lokasi' => 'Jalan Ahmad Yani No.123, Kota Tulungagung',
                'target_dana' => 75000000,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addDays(90),
                'status' => 'disetujui',
            ]
        );

        if ($kausa2->riwayatStatus->count() === 0) {
            $kausa2->riwayatStatus()->create([
                'user_id' => $adminUser->id,
                'status_baru' => 'disetujui',
                'catatan' => 'Pengajuan kausa telah diverifikasi dan disetujui oleh Admin untuk dipublikasikan.',
            ]);
        }

        $kausa3 = Kausa::firstOrCreate(
            ['slug' => 'pembangunan-masjid-ar-rasyid'],
            [
                'instansi_id' => $instansi->id,
                'kategori_kausa_id' => KategoriKausa::where('nama', 'Sarana tempat ibadah')->first()->id,
                'judul' => 'Pembangunan Masjid Ar-Rasyid',
                'ringkasan' => 'Pembangunan rumah ibadah untuk pelayanan umat muslim di Desa Kalidawir.',
                'deskripsi' => 'Desa Kalidawir memiliki lebih dari 800 penduduk muslim namun belum memiliki tempat ibadah yang layak.',
                'lokasi' => 'Desa Kalidawir, Kecamatan Kalidawir, Kabupaten Tulungagung',
                'target_dana' => 150000000,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addDays(120),
                'status' => 'disetujui',
            ]
        );

        if ($kausa3->riwayatStatus->count() === 0) {
            $kausa3->riwayatStatus()->create([
                'user_id' => $adminUser->id,
                'status_baru' => 'disetujui',
                'catatan' => 'Pengajuan kausa telah diverifikasi dan disetujui oleh Admin untuk dipublikasikan.',
            ]);
        }
    }
}
