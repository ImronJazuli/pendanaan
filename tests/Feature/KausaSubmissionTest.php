<?php

namespace Tests\Feature;

use App\Models\Instansi;
use App\Models\KategoriKausa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KausaSubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_institution_user_can_access_form(): void
    {
        $user = User::where('peran', 'institution_user')->first();
        $response = $this->actingAs($user)
            ->withoutMiddleware()
            ->get('/kausa/ajukan');
        
        $response->assertStatus(200);
        $response->assertSeeText('Formulir Pengajuan Kausa Baru');
    }

    public function test_form_shows_categories(): void
    {
        $user = User::where('peran', 'institution_user')->first();
        $response = $this->actingAs($user)->get('/kausa/ajukan');
        
        $response->assertSeeText('Bencana alam');
        $response->assertSeeText('Panti asuhan & lembaga kesejahteraan');
    }

    public function test_institution_user_can_save_draft(): void
    {
        $user = User::where('peran', 'institution_user')->first();
        $kategori = KategoriKausa::first();

        $response = $this->actingAs($user)
            ->withoutMiddleware()
            ->post('/kausa', [
                'judul' => 'Test Kausa Draft',
                'kategori_kausa_id' => $kategori->id,
                'lokasi' => 'Desa Test',
                'ringkasan' => 'Ringkasan test',
                'deskripsi' => 'Deskripsi lengkap untuk test pengajuan kausa',
                'target_dana' => 50000000,
                'tanggal_berakhir' => now()->addDays(30)->toDateString(),
                'action' => 'draft',
            ]);

        $response->assertRedirect('/kausa/ajukan');
        $this->assertDatabaseHas('kausa', [
            'judul' => 'Test Kausa Draft',
            'status' => 'draf',
        ]);
    }

    public function test_institution_user_can_submit_kausa(): void
    {
        $user = User::where('peran', 'institution_user')->first();
        $kategori = KategoriKausa::first();

        $response = $this->actingAs($user)
            ->withoutMiddleware()
            ->post('/kausa', [
                'judul' => 'Test Kausa Submit',
                'kategori_kausa_id' => $kategori->id,
                'lokasi' => 'Desa Test',
                'ringkasan' => 'Ringkasan test',
                'deskripsi' => 'Deskripsi lengkap untuk test pengajuan kausa',
                'target_dana' => 75000000,
                'tanggal_berakhir' => now()->addDays(30)->toDateString(),
                'action' => 'submit',
            ]);

        $response->assertRedirect('/kausa/ajukan');
        $this->assertDatabaseHas('kausa', [
            'judul' => 'Test Kausa Submit',
            'status' => 'menunggu_verifikasi',
        ]);
    }

    public function test_non_institution_user_cannot_access_form(): void
    {
        $donatur = User::where('peran', 'donatur')->first();
        $response = $this->actingAs($donatur)->get('/kausa/ajukan');
        
        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/kausa/ajukan');
        $response->assertRedirect('/login');
    }
}
