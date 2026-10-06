<?php

namespace Tests\Feature;

use App\Models\Instansi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstansiProfilTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_instansi_user_can_view_profile_page(): void
    {
        $user = User::factory()->create([
            'peran' => 'institution_user',
            'role' => 'instansi',
        ]);

        $instansi = Instansi::create([
            'user_id' => $user->id,
            'nama' => 'Yayasan Peduli Sesama Tulungagung',
            'jenis' => 'Yayasan',
            'status_verifikasi' => 'menunggu_verifikasi',
            'nomor_registrasi' => 'AHU-0012345.AH.01.04.TA.2023',
            'alamat' => 'Jl. Supriadi No. 42 Tulungagung',
            'nomor_telepon' => '081234567890',
        ]);

        $response = $this->actingAs($user)->get(route('instansi.profil'));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.instansi.profil');
        $response->assertSee('Yayasan Peduli Sesama Tulungagung');
        $response->assertSee('Menunggu Verifikasi');
    }

    public function test_user_with_only_instansi_role_can_view_profile_page(): void
    {
        $user = User::factory()->create([
            'peran' => 'instansi',
            'role' => 'instansi',
        ]);

        $instansi = Instansi::create([
            'user_id' => $user->id,
            'nama' => 'Komunitas Tulungagung Peduli',
            'jenis' => 'Komunitas',
            'status_verifikasi' => 'terverifikasi',
            'alamat' => 'Jl. Basuki Rahmat No. 12 Tulungagung',
            'nomor_telepon' => '081233445566',
        ]);

        $response = $this->actingAs($user)->get(route('instansi.profil'));

        $response->assertStatus(200);
        $response->assertSee('Komunitas Tulungagung Peduli');
        $response->assertSee('Terverifikasi');
    }

    public function test_unauthenticated_user_cannot_access_profile_page(): void
    {
        $response = $this->get(route('instansi.profil'));

        $response->assertRedirect(route('login'));
    }

    public function test_non_instansi_user_is_forbidden_from_accessing_profile_page(): void
    {
        $donatur = User::factory()->create([
            'peran' => 'donatur',
            'role' => 'donatur',
        ]);

        $response = $this->actingAs($donatur)->get(route('instansi.profil'));

        $response->assertStatus(403);
    }

    public function test_auto_provisions_instansi_record_if_not_exists(): void
    {
        $user = User::factory()->create([
            'peran' => 'institution_user',
            'role' => 'instansi',
            'name' => 'Lembaga Amal Tulungagung',
        ]);

        $this->assertDatabaseMissing('instansi', [
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('instansi.profil'));

        $response->assertStatus(200);
        $this->assertDatabaseHas('instansi', [
            'user_id' => $user->id,
            'nama' => 'Lembaga Amal Tulungagung',
            'status_verifikasi' => 'belum_diverifikasi',
        ]);
    }

    public function test_instansi_can_update_profile_with_valid_data(): void
    {
        $user = User::factory()->create([
            'peran' => 'institution_user',
            'role' => 'instansi',
        ]);

        $instansi = Instansi::create([
            'user_id' => $user->id,
            'nama' => 'Nama Lama',
            'jenis' => 'Yayasan',
            'status_verifikasi' => 'belum_diverifikasi',
            'alamat' => 'Alamat Lama',
            'nomor_telepon' => '08111111111',
        ]);

        $updateData = [
            'nama' => 'Yayasan Berkah Tulungagung',
            'jenis' => 'Yayasan',
            'nomor_registrasi' => 'AHU-9988776.TA.2024',
            'alamat' => 'Jl. Pahlawan No. 88 Tulungagung',
            'nomor_telepon' => '081299887766',
            'nama_pj' => 'Drs. H. Ahmad Fauzi',
            'nik_pj' => '3504121908850001',
            'npwp_lembaga' => '03.884.912.4-629.000',
        ];

        $response = $this->actingAs($user)->put(route('instansi.profil.update'), $updateData);

        $response->assertRedirect(route('instansi.profil'));
        $response->assertSessionHas('success', 'Profil instansi berhasil diperbarui.');

        $this->assertDatabaseHas('instansi', [
            'id' => $instansi->id,
            'nama' => 'Yayasan Berkah Tulungagung',
            'jenis' => 'Yayasan',
            'nomor_registrasi' => 'AHU-9988776.TA.2024',
            'alamat' => 'Jl. Pahlawan No. 88 Tulungagung',
            'nomor_telepon' => '081299887766',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Drs. H. Ahmad Fauzi',
            'nik' => '3504121908850001',
            'npwp' => '03.884.912.4-629.000',
        ]);
    }

    public function test_instansi_can_update_profile_using_canvas_field_names(): void
    {
        $user = User::factory()->create([
            'peran' => 'institution_user',
            'role' => 'instansi',
        ]);

        $instansi = Instansi::create([
            'user_id' => $user->id,
            'nama' => 'Nama Sebelum Update',
            'jenis' => 'Yayasan',
            'status_verifikasi' => 'belum_diverifikasi',
            'alamat' => 'Alamat Sebelum Update',
            'nomor_telepon' => '08100000000',
        ]);

        $canvasData = [
            'nama_lembaga' => 'Yayasan Kasih Peduli Sesama Tulungagung',
            'jenis_badan_hukum' => 'Perkumpulan',
            'no_sk_kemenkumham' => 'AHU-0019284.AH.01.04.TA.2021',
            'alamat_kantor' => 'Jl. Supriadi No. 42, Kel. Kepatihan, Tulungagung',
            'wa_pj' => '081335219908',
            'nama_pj' => 'Bambang Suherman',
        ];

        $response = $this->actingAs($user)->put(route('instansi.profil.update'), $canvasData);

        $response->assertRedirect(route('instansi.profil'));
        $response->assertSessionHas('success', 'Profil instansi berhasil diperbarui.');

        $this->assertDatabaseHas('instansi', [
            'id' => $instansi->id,
            'nama' => 'Yayasan Kasih Peduli Sesama Tulungagung',
            'jenis' => 'Perkumpulan',
            'nomor_registrasi' => 'AHU-0019284.AH.01.04.TA.2021',
            'alamat' => 'Jl. Supriadi No. 42, Kel. Kepatihan, Tulungagung',
            'nomor_telepon' => '081335219908',
        ]);
    }

    public function test_update_profile_fails_when_required_fields_are_missing(): void
    {
        $user = User::factory()->create([
            'peran' => 'institution_user',
            'role' => 'instansi',
        ]);

        $instansi = Instansi::create([
            'user_id' => $user->id,
            'nama' => 'Nama Tetap',
            'jenis' => 'Yayasan',
            'status_verifikasi' => 'belum_diverifikasi',
            'alamat' => 'Alamat Tetap',
            'nomor_telepon' => '081234567890',
        ]);

        $response = $this->actingAs($user)->put(route('instansi.profil.update'), [
            'nama' => '',
            'jenis' => '',
            'alamat' => '',
            'nomor_telepon' => '',
        ]);

        $response->assertSessionHasErrors(['nama', 'jenis', 'alamat', 'nomor_telepon']);

        $this->assertDatabaseHas('instansi', [
            'id' => $instansi->id,
            'nama' => 'Nama Tetap',
        ]);
    }
}
