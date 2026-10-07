<?php

namespace Tests\Feature;

use App\Models\Instansi;
use App\Models\KategoriKausa;
use App\Models\Kausa;
use App\Models\Notifikasi;
use App\Models\RiwayatStatusKausa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstansiKausaRevisiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Instansi $instansi;

    protected KategoriKausa $kategori;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'peran' => 'institution_user',
            'role' => 'instansi',
        ]);

        $this->instansi = Instansi::create([
            'user_id' => $this->user->id,
            'nama' => 'Yayasan Peduli Sesama Tulungagung',
            'jenis' => 'Yayasan',
            'status_verifikasi' => 'terverifikasi',
        ]);

        $this->kategori = KategoriKausa::create([
            'nama' => 'Bencana Alam',
            'slug' => 'bencana-alam',
            'aktif' => true,
        ]);
    }

    public function test_instansi_can_view_edit_page_for_kausa_perlu_diperbaiki(): void
    {
        $kausa = Kausa::create([
            'instansi_id' => $this->instansi->id,
            'kategori_kausa_id' => $this->kategori->id,
            'judul' => 'Bantuan Banjir Besuki',
            'slug' => 'bantuan-banjir-besuki',
            'lokasi' => 'Kecamatan Besuki',
            'target_dana' => 50000000,
            'deskripsi' => 'Deskripsi kegiatan bantuan banjir',
            'status' => 'perlu_diperbaiki',
            'catatan_admin' => 'Mohon lampirkan estimasi anggaran dan surat pengantar desa.',
        ]);

        $response = $this->actingAs($this->user)->get(route('dashboard.instansi.edit', $kausa->id));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.instansi.edit');
        $response->assertSee('Bantuan Banjir Besuki');
        $response->assertSee('Mohon lampirkan estimasi anggaran dan surat pengantar desa.');
    }

    public function test_instansi_can_view_edit_page_for_draft_kausa(): void
    {
        $kausa = Kausa::create([
            'instansi_id' => $this->instansi->id,
            'kategori_kausa_id' => $this->kategori->id,
            'judul' => 'Draf Kausa Sosial Baru',
            'slug' => 'draf-kausa-sosial-baru',
            'lokasi' => 'Tulungagung',
            'target_dana' => 20000000,
            'deskripsi' => 'Deskripsi draf awal',
            'status' => 'draf',
        ]);

        $response = $this->actingAs($this->user)->get(route('dashboard.instansi.edit', $kausa->id));

        $response->assertStatus(200);
        $response->assertSee('Draf Kausa Sosial Baru');
    }

    public function test_cannot_edit_kausa_with_menunggu_verifikasi_or_disetujui_status(): void
    {
        $kausaMenunggu = Kausa::create([
            'instansi_id' => $this->instansi->id,
            'kategori_kausa_id' => $this->kategori->id,
            'judul' => 'Kausa Menunggu',
            'slug' => 'kausa-menunggu',
            'lokasi' => 'Tulungagung',
            'target_dana' => 10000000,
            'deskripsi' => 'Deskripsi',
            'status' => 'menunggu_verifikasi',
        ]);

        $response = $this->actingAs($this->user)->get(route('dashboard.instansi.edit', $kausaMenunggu->id));
        $response->assertStatus(403);

        $kausaDisetujui = Kausa::create([
            'instansi_id' => $this->instansi->id,
            'kategori_kausa_id' => $this->kategori->id,
            'judul' => 'Kausa Disetujui',
            'slug' => 'kausa-disetujui',
            'lokasi' => 'Tulungagung',
            'target_dana' => 10000000,
            'deskripsi' => 'Deskripsi',
            'status' => 'disetujui',
        ]);

        $response2 = $this->actingAs($this->user)->get(route('dashboard.instansi.edit', $kausaDisetujui->id));
        $response2->assertStatus(403);
    }

    public function test_other_instansi_cannot_edit_kausa(): void
    {
        $otherUser = User::factory()->create(['peran' => 'institution_user', 'role' => 'instansi']);
        $otherInstansi = Instansi::create([
            'user_id' => $otherUser->id,
            'nama' => 'Lembaga Lain',
            'jenis' => 'Yayasan',
        ]);

        $kausa = Kausa::create([
            'instansi_id' => $otherInstansi->id,
            'kategori_kausa_id' => $this->kategori->id,
            'judul' => 'Kausa Milik Orang Lain',
            'slug' => 'kausa-milik-orang-lain',
            'lokasi' => 'Tulungagung',
            'target_dana' => 10000000,
            'deskripsi' => 'Deskripsi',
            'status' => 'perlu_diperbaiki',
        ]);

        $response = $this->actingAs($this->user)->get(route('dashboard.instansi.edit', $kausa->id));
        $response->assertStatus(403);
    }

    public function test_instansi_can_resubmit_kausa_perlu_diperbaiki(): void
    {
        $admin = User::factory()->create([
            'peran' => 'admin',
            'role' => 'admin',
        ]);

        $kausa = Kausa::create([
            'instansi_id' => $this->instansi->id,
            'kategori_kausa_id' => $this->kategori->id,
            'judul' => 'Rehabilitasi Rumah Bencana',
            'slug' => 'rehabilitasi-rumah-bencana',
            'lokasi' => 'Kecamatan Pagerwojo',
            'target_dana' => 40000000,
            'deskripsi' => 'Deskripsi awal kegiatan',
            'status' => 'perlu_diperbaiki',
            'catatan_admin' => 'Perbaiki estimasi biaya belanja material.',
        ]);

        $response = $this->actingAs($this->user)->put(route('dashboard.instansi.update', $kausa->id), [
            'action' => 'submit',
            'judul' => 'Rehabilitasi Rumah Bencana (Revisi Final)',
            'kategori_kausa_id' => $this->kategori->id,
            'lokasi' => 'Kecamatan Pagerwojo, Kab. Tulungagung',
            'target_dana' => '45.000.000',
            'ringkasan' => 'Ringkasan yang telah diperbarui',
            'deskripsi' => 'Deskripsi lengkap yang sudah diperbaiki rinciannya',
            'catatan_perbaikan' => 'RAB dan rincian belanja telah disesuaikan dengan harga toko material setempat.',
        ]);

        $response->assertRedirect(route('dashboard.instansi.detail', $kausa->id));

        $kausa->refresh();
        $this->assertEquals('menunggu_verifikasi', $kausa->status);
        $this->assertEquals('Rehabilitasi Rumah Bencana (Revisi Final)', $kausa->judul);
        $this->assertEquals('45000000.00', $kausa->target_dana);

        // Verifikasi RiwayatStatusKausa
        $this->assertDatabaseHas('riwayat_status_kausa', [
            'kausa_id' => $kausa->id,
            'user_id' => $this->user->id,
            'status_baru' => 'menunggu_verifikasi',
        ]);

        // Verifikasi Notifikasi terkirim ke Admin
        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $admin->id,
            'jenis' => 'kausa_diperbaiki',
        ]);
    }

    public function test_instansi_can_save_kausa_as_draft(): void
    {
        $kausa = Kausa::create([
            'instansi_id' => $this->instansi->id,
            'kategori_kausa_id' => $this->kategori->id,
            'judul' => 'Kausa Draf',
            'slug' => 'kausa-draf',
            'lokasi' => 'Tulungagung',
            'target_dana' => 10000000,
            'deskripsi' => 'Deskripsi',
            'status' => 'draf',
        ]);

        $response = $this->actingAs($this->user)->put(route('dashboard.instansi.update', $kausa->id), [
            'action' => 'draft',
            'judul' => 'Kausa Draf Diperbarui',
            'kategori_kausa_id' => $this->kategori->id,
            'lokasi' => 'Kecamatan Kedungwaru',
            'target_dana' => 15000000,
            'deskripsi' => 'Deskripsi baru',
        ]);

        $response->assertRedirect(route('dashboard.instansi.detail', $kausa->id));

        $kausa->refresh();
        $this->assertEquals('draf', $kausa->status);
        $this->assertEquals('Kausa Draf Diperbarui', $kausa->judul);
    }
}
