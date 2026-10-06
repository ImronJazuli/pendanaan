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

        // Instansi User
        $instansiUser = User::firstOrCreate(
            ['email' => 'instansi@example.test'],
            [
                'name' => 'Dinas Sosial Pemkab',
                'password' => Hash::make('password'),
                'peran' => 'institution_user',
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

        // Donatur User
        User::firstOrCreate(
            ['email' => 'donatur@example.test'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'peran' => 'donatur',
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
