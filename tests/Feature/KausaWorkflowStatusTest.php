<?php

namespace Tests\Feature;

use App\Models\Instansi;
use App\Models\KategoriKausa;
use App\Models\Kausa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KausaWorkflowStatusTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $instansiUser;

    protected Instansi $instansi;

    protected KategoriKausa $kategori;

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
            'nama' => 'Dinsos Tulungagung',
            'jenis' => 'OPD',
            'status_verifikasi' => 'terverifikasi',
        ]);

        $this->kategori = KategoriKausa::create([
            'nama' => 'Pemberdayaan Masyarakat',
            'slug' => 'pemberdayaan-masyarakat',
            'aktif' => true,
        ]);
    }

    public function test_rejected_kausa_cannot_directly_become_approved_without_resubmission(): void
    {
        $kausa = Kausa::create([
            'instansi_id' => $this->instansi->id,
            'kategori_kausa_id' => $this->kategori->id,
            'judul' => 'Kausa Yang Pernah Ditolak',
            'slug' => 'kausa-yang-pernah-ditolak',
            'deskripsi' => 'Deskripsi kausa',
            'lokasi' => 'Tulungagung',
            'target_dana' => 10000000,
            'dana_terkumpul' => 0,
            'status' => 'ditolak',
        ]);

        // Trying to directly verify a rejected kausa should return 403
        $response = $this->actingAs($this->admin)->post(route('dashboard.admin.verify', $kausa));
        $response->assertStatus(403);

        $this->assertEquals('ditolak', $kausa->fresh()->status);
    }

    public function test_only_approved_kausa_appears_in_public_catalog_and_landing_page(): void
    {
        $approvedKausa = Kausa::create([
            'instansi_id' => $this->instansi->id,
            'kategori_kausa_id' => $this->kategori->id,
            'judul' => 'Kausa Sah Tayang Publik',
            'slug' => 'kausa-sah-tayang-publik',
            'ringkasan' => 'Program resmi yang disetujui',
            'deskripsi' => 'Deskripsi program resmi yang disetujui',
            'lokasi' => 'Tulungagung',
            'target_dana' => 10000000,
            'dana_terkumpul' => 0,
            'status' => 'disetujui',
        ]);

        $draftKausa = Kausa::create([
            'instansi_id' => $this->instansi->id,
            'kategori_kausa_id' => $this->kategori->id,
            'judul' => 'Kausa Draf Rahasia Instansi',
            'slug' => 'kausa-draf-rahasia-instansi',
            'ringkasan' => 'Program draf',
            'deskripsi' => 'Deskripsi program draf',
            'lokasi' => 'Tulungagung',
            'target_dana' => 5000000,
            'dana_terkumpul' => 0,
            'status' => 'draf',
        ]);

        $pendingKausa = Kausa::create([
            'instansi_id' => $this->instansi->id,
            'kategori_kausa_id' => $this->kategori->id,
            'judul' => 'Kausa Menunggu Verifikasi',
            'slug' => 'kausa-menunggu-verifikasi',
            'ringkasan' => 'Program pending',
            'deskripsi' => 'Deskripsi program pending',
            'lokasi' => 'Tulungagung',
            'target_dana' => 5000000,
            'dana_terkumpul' => 0,
            'status' => 'menunggu_verifikasi',
        ]);

        // 1. Katalog Publik (/kausa)
        $catalogResp = $this->get(route('kausa.index'));
        $catalogResp->assertOk();
        $catalogResp->assertSee('Kausa Sah Tayang Publik');
        $catalogResp->assertDontSee('Kausa Draf Rahasia Instansi');
        $catalogResp->assertDontSee('Kausa Menunggu Verifikasi');

        // 2. Landing Page (/)
        $landingResp = $this->get(route('landing'));
        $landingResp->assertOk();
        $landingResp->assertSee('Kausa Sah Tayang Publik');
        $landingResp->assertDontSee('Kausa Draf Rahasia Instansi');
        $landingResp->assertDontSee('Kausa Menunggu Verifikasi');
    }
}
