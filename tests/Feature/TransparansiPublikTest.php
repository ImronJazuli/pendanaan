<?php

namespace Tests\Feature;

use App\Models\Instansi;
use App\Models\KategoriKausa;
use App\Models\Kausa;
use App\Models\LaporanDana;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransparansiPublikTest extends TestCase
{
    use RefreshDatabase;

    protected User $instansiUser;

    protected Instansi $instansi;

    protected Kausa $kausa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->instansiUser = User::factory()->create([
            'role' => 'instansi',
            'peran' => 'institution_user',
        ]);

        $this->instansi = Instansi::create([
            'user_id' => $this->instansiUser->id,
            'nama' => 'Dinsos Tulungagung',
            'jenis' => 'OPD',
            'status_verifikasi' => 'terverifikasi',
        ]);

        $kategori = KategoriKausa::create([
            'nama' => 'Sosial Lingkungan',
            'slug' => 'sosial-lingkungan',
            'aktif' => true,
        ]);

        $this->kausa = Kausa::create([
            'instansi_id' => $this->instansi->id,
            'kategori_kausa_id' => $kategori->id,
            'judul' => 'Penanaman Pohon Pesisir Pantai Popoh',
            'slug' => 'penanaman-pohon-pesisir-pantai-popoh',
            'deskripsi' => 'Konservasi lingkungan pesisir pantai',
            'lokasi' => 'Kecamatan Besuki',
            'target_dana' => 15000000,
            'dana_terkumpul' => 15000000,
            'status' => 'disetujui',
        ]);
    }

    public function test_only_approved_or_published_reports_appear_on_public_transparency(): void
    {
        // 1. Laporan yang sudah disetujui / dipublikasikan
        $laporanDisetujui = LaporanDana::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->instansiUser->id,
            'judul' => 'LPJ Resmi Penanaman 5000 Bibit Cemara Udang',
            'total_digunakan' => 12500000,
            'status' => 'disetujui',
            'disetujui_pada' => now(),
            'dipublikasikan_pada' => now(),
        ]);

        // 2. Laporan draft / internal
        $laporanDraf = LaporanDana::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->instansiUser->id,
            'judul' => 'Draf Rahasia Belum Diverifikasi Admin',
            'total_digunakan' => 2000000,
            'status' => 'draf',
        ]);

        // 3. Laporan pending
        $laporanPending = LaporanDana::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->instansiUser->id,
            'judul' => 'LPJ Menunggu Verifikasi Pemeriksa',
            'total_digunakan' => 500000,
            'status' => 'menunggu_verifikasi',
        ]);

        $response = $this->get(route('transparansi.index'));
        $response->assertOk();

        // Must see approved report
        $response->assertSee('LPJ Resmi Penanaman 5000 Bibit Cemara Udang');
        $response->assertSee(number_format(12500000, 0, ',', '.'));

        // Must NOT see draft or pending report
        $response->assertDontSee('Draf Rahasia Belum Diverifikasi Admin');
        $response->assertDontSee('LPJ Menunggu Verifikasi Pemeriksa');
    }
}
