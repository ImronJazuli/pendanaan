<?php

namespace Tests\Feature;

use App\Models\Instansi;
use App\Models\KategoriKausa;
use App\Models\Kausa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class KausaPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected User $userA;

    protected User $userB;

    protected Instansi $instansiA;

    protected Instansi $instansiB;

    protected Kausa $kausaA;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userA = User::factory()->create(['role' => 'instansi', 'peran' => 'institution_user']);
        $this->instansiA = Instansi::create([
            'user_id' => $this->userA->id,
            'nama' => 'Instansi Lembaga A',
            'jenis' => 'Yayasan',
            'status_verifikasi' => 'terverifikasi',
        ]);

        $this->userB = User::factory()->create(['role' => 'instansi', 'peran' => 'institution_user']);
        $this->instansiB = Instansi::create([
            'user_id' => $this->userB->id,
            'nama' => 'Instansi Lembaga B',
            'jenis' => 'Yayasan',
            'status_verifikasi' => 'terverifikasi',
        ]);

        $kategori = KategoriKausa::create(['nama' => 'Kesehatan', 'slug' => 'kesehatan', 'aktif' => true]);

        $this->kausaA = Kausa::create([
            'instansi_id' => $this->instansiA->id,
            'kategori_kausa_id' => $kategori->id,
            'judul' => 'Program Bantuan Lembaga A',
            'slug' => 'program-bantuan-lembaga-a',
            'deskripsi' => 'Deskripsi program lembaga A',
            'lokasi' => 'Tulungagung',
            'target_dana' => 10000000,
            'dana_terkumpul' => 0,
            'status' => 'draf',
        ]);
    }

    public function test_institution_b_cannot_update_or_delete_kausa_of_institution_a(): void
    {
        $this->assertFalse(Gate::forUser($this->userB)->allows('update', $this->kausaA));
        $this->assertFalse(Gate::forUser($this->userB)->allows('delete', $this->kausaA));

        // HTTP request test: User B tries to access edit page of User A's kausa
        $response = $this->actingAs($this->userB)->get(route('dashboard.instansi.edit', $this->kausaA));
        $response->assertStatus(403);
    }

    public function test_institution_a_can_update_own_kausa_when_draft_or_revision(): void
    {
        // Status draft
        $this->assertTrue(Gate::forUser($this->userA)->allows('update', $this->kausaA));
        $this->assertTrue(Gate::forUser($this->userA)->allows('delete', $this->kausaA));

        // Status perlu_diperbaiki
        $this->kausaA->update(['status' => 'perlu_diperbaiki']);
        $this->assertTrue(Gate::forUser($this->userA)->allows('update', $this->kausaA));
        // Status perlu_diperbaiki cannot be deleted
        $this->assertFalse(Gate::forUser($this->userA)->allows('delete', $this->kausaA));
    }

    public function test_institution_a_cannot_update_own_kausa_when_approved_or_completed(): void
    {
        $this->kausaA->update(['status' => 'disetujui']);
        $this->assertFalse(Gate::forUser($this->userA)->allows('update', $this->kausaA));

        $this->kausaA->update(['status' => 'selesai']);
        $this->assertFalse(Gate::forUser($this->userA)->allows('update', $this->kausaA));
    }
}
