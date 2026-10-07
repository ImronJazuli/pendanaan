<?php

namespace Tests\Feature;

use App\Models\Instansi;
use App\Models\KategoriKausa;
use App\Models\Kausa;
use App\Models\LaporanDana;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $instansiUser;

    protected Instansi $instansi;

    protected Kausa $kausa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'peran' => 'admin',
        ]);

        $this->instansiUser = User::factory()->create([
            'role' => 'instansi',
            'peran' => 'institution_user',
        ]);

        $this->instansi = Instansi::create([
            'user_id' => $this->instansiUser->id,
            'nama' => 'Yayasan Peduli Pemkab TA',
            'jenis' => 'Yayasan',
            'status_verifikasi' => 'belum_diverifikasi',
        ]);

        $kategori = KategoriKausa::create([
            'nama' => 'Kesehatan Masyarakat',
            'slug' => 'kesehatan-masyarakat',
            'aktif' => true,
        ]);

        $this->kausa = Kausa::create([
            'instansi_id' => $this->instansi->id,
            'kategori_kausa_id' => $kategori->id,
            'judul' => 'Operasi Medis Warga Prasejahtera',
            'slug' => 'operasi-medis-warga-prasejahtera',
            'lokasi' => 'Tulungagung',
            'target_dana' => 50000000,
            'dana_terkumpul' => 50000000,
            'status' => 'disetujui',
            'deskripsi' => 'Program bantuan operasi medis warga tidak mampu.',
        ]);
    }

    public function test_admin_can_access_legalitas_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.legalitas'));

        $response->assertStatus(200);
        $response->assertSee('Yayasan Peduli Pemkab TA');
    }

    public function test_admin_can_verify_instansi_legalitas(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.legalitas.verify', $this->instansi->id));

        $response->assertRedirect(route('admin.legalitas'));

        $this->instansi->refresh();
        $this->assertEquals('terverifikasi', $this->instansi->status_verifikasi);
        $this->assertNotNull($this->instansi->terverifikasi_pada);

        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $this->instansiUser->id,
            'jenis' => 'legalitas_disetujui',
        ]);
    }

    public function test_admin_can_reject_instansi_legalitas(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.legalitas.reject', $this->instansi->id), [
            'alasan_penolakan' => 'Dokumen akta notaris belum dilegalisir Kemenkumham.',
        ]);

        $response->assertRedirect(route('admin.legalitas'));

        $this->instansi->refresh();
        $this->assertEquals('ditolak', $this->instansi->status_verifikasi);

        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $this->instansiUser->id,
            'jenis' => 'legalitas_ditolak',
        ]);
    }

    public function test_admin_can_access_laporan_verifikasi_page(): void
    {
        $laporan = LaporanDana::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->instansiUser->id,
            'judul' => 'LPJ Operasi Medis Tahap 1',
            'periode_mulai' => now()->subDays(5),
            'periode_selesai' => now(),
            'total_digunakan' => 20000000,
            'status' => 'menunggu_verifikasi',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.laporan'));

        $response->assertStatus(200);
        $response->assertSee('LPJ Operasi Medis Tahap 1');
    }

    public function test_admin_can_verify_and_publish_laporan_lpj(): void
    {
        $laporan = LaporanDana::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->instansiUser->id,
            'judul' => 'LPJ Operasi Medis Tahap 1',
            'periode_mulai' => now()->subDays(5),
            'periode_selesai' => now(),
            'total_digunakan' => 20000000,
            'status' => 'menunggu_verifikasi',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.laporan.verify', $laporan->id));

        $response->assertRedirect(route('admin.laporan'));

        $laporan->refresh();
        $this->assertEquals('disetujui', $laporan->status);
        $this->assertNotNull($laporan->dipublikasikan_pada);

        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $this->instansiUser->id,
            'jenis' => 'lpj_disetujui',
        ]);
    }

    public function test_admin_can_request_revision_for_laporan_lpj(): void
    {
        $laporan = LaporanDana::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->instansiUser->id,
            'judul' => 'LPJ Operasi Medis Tahap 1',
            'periode_mulai' => now()->subDays(5),
            'periode_selesai' => now(),
            'total_digunakan' => 20000000,
            'status' => 'menunggu_verifikasi',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.laporan.revise', $laporan->id), [
            'catatan_revisi' => 'Mohon lampirkan kuitansi asli rumah sakit dan rincian obat.',
        ]);

        $response->assertRedirect(route('admin.laporan'));

        $laporan->refresh();
        $this->assertEquals('perlu_revisi', $laporan->status);
        $this->assertEquals('Mohon lampirkan kuitansi asli rumah sakit dan rincian obat.', $laporan->catatan_admin);
    }

    public function test_admin_can_access_kausa_aktif_monitoring(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.kausa.aktif'));

        $response->assertStatus(200);
        $response->assertSee('Operasi Medis Warga Prasejahtera');
    }

    public function test_admin_can_close_or_complete_kausa(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.kausa.selesai', $this->kausa->id), [
            'catatan' => 'Target dana 100% tercapai dan masa program telah usai.',
        ]);

        $response->assertRedirect(route('admin.kausa.aktif'));

        $this->kausa->refresh();
        $this->assertEquals('selesai', $this->kausa->status);
    }

    public function test_admin_can_view_donasi_recap_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.donasi'));

        $response->assertStatus(200);
        $response->assertSee('Rekapitulasi Donasi & Pembayaran');
    }

    public function test_public_transparansi_portal_displays_published_reports(): void
    {
        $laporan = LaporanDana::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->instansiUser->id,
            'judul' => 'Laporan Penyaluran Bantuan Operasi Medis',
            'periode_mulai' => now()->subDays(10),
            'periode_selesai' => now()->subDays(2),
            'total_digunakan' => 35000000,
            'status' => 'disetujui',
            'dipublikasikan_pada' => now(),
        ]);

        $laporan->rincian()->create([
            'uraian' => 'Biaya tindakan bedah dan kamar rawat inap',
            'nominal' => 35000000,
            'penerima_manfaat' => 'Bapak Slamet (Kec. Boyolangu)',
        ]);

        $response = $this->get(route('transparansi.index'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Penyaluran Bantuan Operasi Medis');
        $response->assertSee('35.000.000');
    }
}
