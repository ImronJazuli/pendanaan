<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $instansi;

    protected User $donatur;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'peran' => 'admin',
        ]);

        $this->instansi = User::factory()->create([
            'role' => 'instansi',
            'peran' => 'institution_user',
        ]);

        $this->donatur = User::factory()->create([
            'role' => 'donatur',
            'peran' => 'donatur',
        ]);
    }

    public function test_donatur_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->donatur)->get(route('dashboard.admin'));
        $response->assertStatus(403);
    }

    public function test_instansi_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->instansi)->get(route('dashboard.admin'));
        $response->assertStatus(403);
    }

    public function test_admin_cannot_access_instansi_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get(route('dashboard.instansi'));
        // PeranMiddleware returns 403
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_any_dashboard_and_is_redirected_to_login(): void
    {
        $adminResp = $this->get(route('dashboard.admin'));
        $adminResp->assertRedirect();

        $instansiResp = $this->get(route('dashboard.instansi'));
        $instansiResp->assertRedirect(route('login'));

        $donaturResp = $this->get(route('donatur.dashboard'));
        $donaturResp->assertRedirect(route('login'));
    }
}
