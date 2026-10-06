<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Kausa;
use App\Models\Instansi;
use App\Models\KategoriKausa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectPortalPendanaanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    // ===== TESTING ROUTE/ENDPOINT =====
    
    public function test_landing_page_accessible()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('landing');
    }

    public function test_katalog_kausa_accessible()
    {
        $response = $this->get('/kausa');
        $response->assertStatus(200);
        $response->assertViewIs('kausa.index');
    }

    public function test_transparansi_page_accessible()
    {
        $response = $this->get('/transparansi');
        $response->assertStatus(200);
        $response->assertViewIs('transparansi.index');
    }

    public function test_static_pages_accessible()
    {
        $pages = ['/tentang', '/faq', '/kebijakan-privasi', '/syarat-ketentuan', '/kontak'];
        
        foreach ($pages as $page) {
            $response = $this->get($page);
            $response->assertStatus(200);
        }
    }

    // ===== TESTING AUTENTIKASI =====

    public function test_login_page_shows()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_register_page_shows()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::where('email', 'instansi@example.test')->first();
        
        $response = $this->post('/login', [
            'email' => 'instansi@example.test',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
    }

    public function test_user_cannot_login_with_invalid_password()
    {
        $response = $this->post('/login', [
            'email' => 'instansi@example.test',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_authenticated_user_can_logout()
    {
        $user = User::where('peran', 'instansi@example.test')->first() ?? 
                User::where('email', 'instansi@example.test')->first();
        
        $response = $this->actingAs($user)->post('/logout');
        
        $this->assertGuest();
        $response->assertRedirect('/');
    }

    // ===== TESTING HALAMAN PUBLIK =====

    public function test_katalog_menampilkan_kausa_disetujui()
    {
        $response = $this->get('/kausa');
        
        $kausaDisetujui = Kausa::where('status', 'disetujui')->count();
        $this->assertGreaterThan(0, $kausaDisetujui);
    }

    public function test_detail_kausa_dapat_diakses()
    {
        $kausa = Kausa::where('status', 'disetujui')->first();
        
        $response = $this->get("/kausa/{$kausa->slug}");
        $response->assertStatus(200);
        $response->assertViewIs('kausa.show');
        $response->assertViewHas('kausa');
    }

    public function test_detail_kausa_tidak_tersedia_jika_belum_disetujui()
    {
        $kausa = Kausa::where('status', 'draf')->first();
        
        if ($kausa) {
            $response = $this->get("/kausa/{$kausa->slug}");
            $response->assertStatus(404);
        }
    }

    // ===== TESTING DASHBOARD INSTANSI =====

    public function test_instansi_user_can_access_dashboard_instansi()
    {
        $user = User::where('peran', 'institution_user')->first();
        
        $response = $this->actingAs($user)->withoutMiddleware()->get('/dashboard/instansi');
        $response->assertStatus(200);
        $response->assertViewIs('dashboard.instansi.index');
    }

    public function test_non_instansi_user_cannot_access_dashboard_instansi()
    {
        $user = User::where('peran', 'donatur')->first();
        
        $response = $this->actingAs($user)->withoutMiddleware()->get('/dashboard/instansi');
        $response->assertStatus(403);
    }

    public function test_instansi_dapat_melihat_kausa_yang_diajukan()
    {
        $user = User::where('peran', 'institution_user')->first();
        $response = $this->actingAs($user)->withoutMiddleware()->get('/dashboard/instansi');
        
        $response->assertViewHas('statusCounts');
    }

    // ===== TESTING FORM PENGAJUAN KAUSA =====

    public function test_instansi_dapat_akses_form_ajukan_kausa()
    {
        $user = User::where('peran', 'institution_user')->first();
        
        $response = $this->actingAs($user)->withoutMiddleware()->get('/kausa/ajukan');
        $response->assertStatus(200);
        $response->assertViewIs('kausa.create');
    }

    public function test_kausa_dapat_disimpan_sebagai_draf()
    {
        $user = User::where('peran', 'institution_user')->first();
        $kategori = KategoriKausa::first();

        $response = $this->actingAs($user)->withoutMiddleware()->post('/kausa', [
            'judul' => 'Test Kausa Draft',
            'kategori_kausa_id' => $kategori->id,
            'lokasi' => 'Desa Test',
            'ringkasan' => 'Ringkasan test',
            'deskripsi' => 'Deskripsi lengkap untuk test',
            'target_dana' => 50000000,
            'tanggal_berakhir' => now()->addDays(30)->toDateString(),
            'action' => 'draft',
        ]);

        $this->assertDatabaseHas('kausa', [
            'judul' => 'Test Kausa Draft',
            'status' => 'draf',
        ]);
    }

    public function test_kausa_dapat_dikirim_untuk_verifikasi()
    {
        $user = User::where('peran', 'institution_user')->first();
        $kategori = KategoriKausa::first();

        $response = $this->actingAs($user)->withoutMiddleware()->post('/kausa', [
            'judul' => 'Test Kausa Submit',
            'kategori_kausa_id' => $kategori->id,
            'lokasi' => 'Desa Test',
            'ringkasan' => 'Ringkasan test',
            'deskripsi' => 'Deskripsi lengkap untuk test',
            'target_dana' => 75000000,
            'tanggal_berakhir' => now()->addDays(30)->toDateString(),
            'action' => 'submit',
        ]);

        $this->assertDatabaseHas('kausa', [
            'judul' => 'Test Kausa Submit',
            'status' => 'menunggu_verifikasi',
        ]);
    }

    // ===== TESTING DASHBOARD DONATUR =====

    public function test_donatur_dapat_akses_dashboard_donatur()
    {
        $user = User::where('peran', 'donatur')->first();
        
        $response = $this->actingAs($user)->withoutMiddleware()->get('/dashboard/donatur');
        $response->assertStatus(200);
        $response->assertViewIs('dashboard.donatur.index');
    }

    public function test_admin_tidak_dapat_akses_dashboard_donatur()
    {
        $user = User::where('peran', 'admin')->first();
        
        $response = $this->actingAs($user)->withoutMiddleware()->get('/dashboard/donatur');
        $response->assertStatus(403);
    }

    // ===== TESTING DASHBOARD ADMIN =====

    public function test_admin_dapat_akses_dashboard_admin()
    {
        $user = User::where('peran', 'admin')->first();
        
        $response = $this->actingAs($user)->withoutMiddleware()->get('/dashboard/admin');
        $response->assertStatus(200);
        $response->assertViewIs('dashboard.admin.index');
    }

    public function test_admin_dapat_lihat_kausa_menunggu_verifikasi()
    {
        $user = User::where('peran', 'admin')->first();
        
        $response = $this->actingAs($user)->withoutMiddleware()->get('/dashboard/admin');
        $response->assertViewHas('statusCounts');
    }

    public function test_admin_dapat_setujui_kausa()
    {
        $admin = User::where('peran', 'admin')->first();
        $kausa = Kausa::where('status', 'menunggu_verifikasi')->first();

        if (!$kausa) {
            $instansi = Instansi::first();
            $kategori = KategoriKausa::first();
            $kausa = Kausa::create([
                'instansi_id' => $instansi->id,
                'kategori_kausa_id' => $kategori->id,
                'judul' => 'Test Kausa Verifikasi',
                'ringkasan' => 'Test',
                'deskripsi' => 'Test deskripsi',
                'lokasi' => 'Test Lokasi',
                'target_dana' => 50000000,
                'slug' => 'test-kausa-' . time(),
                'status' => 'menunggu_verifikasi',
            ]);
        }

        $response = $this->actingAs($admin)->withoutMiddleware()
            ->post("/dashboard/admin/kausa/{$kausa->id}/verify", [
                'catatan' => 'Disetujui oleh admin',
            ]);

        $this->assertDatabaseHas('kausa', [
            'id' => $kausa->id,
            'status' => 'disetujui',
        ]);
    }

    public function test_admin_dapat_tolak_kausa()
    {
        $admin = User::where('peran', 'admin')->first();
        $instansi = Instansi::first();
        $kategori = KategoriKausa::first();
        
        $kausa = Kausa::create([
            'instansi_id' => $instansi->id,
            'kategori_kausa_id' => $kategori->id,
            'judul' => 'Test Kausa Tolak',
            'ringkasan' => 'Test',
            'deskripsi' => 'Test deskripsi',
            'lokasi' => 'Test Lokasi',
            'target_dana' => 50000000,
            'slug' => 'test-kausa-tolak-' . time(),
            'status' => 'menunggu_verifikasi',
        ]);

        $response = $this->actingAs($admin)->withoutMiddleware()
            ->post("/dashboard/admin/kausa/{$kausa->id}/reject", [
                'alasan_penolakan' => 'Data tidak lengkap',
            ]);

        $this->assertDatabaseHas('kausa', [
            'id' => $kausa->id,
            'status' => 'ditolak',
        ]);
    }

    // ===== TESTING DATA & RELASI DATABASE =====

    public function test_data_test_tersedia()
    {
        $admin = User::where('peran', 'admin')->first();
        $instansi = User::where('peran', 'institution_user')->first();
        $donatur = User::where('peran', 'donatur')->first();

        $this->assertNotNull($admin);
        $this->assertNotNull($instansi);
        $this->assertNotNull($donatur);
    }

    public function test_instansi_user_memiliki_relasi_instansi()
    {
        $user = User::where('peran', 'institution_user')->first();
        
        $this->assertNotNull($user->instansi);
        $this->assertEquals('Dinas Sosial Pemkab Tulungagung', $user->instansi->nama);
    }

    public function test_kausa_memiliki_relasi_kategori()
    {
        $kausa = Kausa::first();
        
        $this->assertNotNull($kausa->kategori);
        $this->assertNotNull($kausa->kategori->nama);
    }

    public function test_kausa_memiliki_relasi_instansi()
    {
        $kausa = Kausa::first();
        
        $this->assertNotNull($kausa->instansi);
    }

    // ===== TESTING ROLE-BASED ACCESS CONTROL =====

    public function test_unauthenticated_user_redirected_to_login()
    {
        $response = $this->get('/dashboard/instansi');
        $response->assertRedirect('/login');
    }

    public function test_wrong_role_cannot_access_dashboard()
    {
        $donatur = User::where('peran', 'donatur')->first();
        
        $response = $this->actingAs($donatur)->withoutMiddleware()->get('/dashboard/instansi');
        $response->assertStatus(403);
    }

    // ===== TESTING HALAMAN PROFIL =====

    public function test_authenticated_user_can_access_profile()
    {
        $user = User::where('peran', 'instansi@example.test')->first() ?? 
                User::where('peran', 'institution_user')->first();
        
        $response = $this->actingAs($user)->withoutMiddleware()->get('/profile');
        $response->assertStatus(200);
    }
}
