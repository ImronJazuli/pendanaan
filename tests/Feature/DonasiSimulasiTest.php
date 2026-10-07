<?php

namespace Tests\Feature;

use App\Models\Donasi;
use App\Models\Instansi;
use App\Models\KategoriKausa;
use App\Models\Kausa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonasiSimulasiTest extends TestCase
{
    use RefreshDatabase;

    protected function setupKausa(): Kausa
    {
        $instansiUser = User::factory()->create(['role' => 'instansi', 'peran' => 'institution_user']);
        $instansi = Instansi::create([
            'user_id' => $instansiUser->id,
            'nama' => 'Dinas Sosial Kabupaten Tulungagung',
            'jenis' => 'OPD',
            'status_verifikasi' => 'terverifikasi',
        ]);
        $kategori = KategoriKausa::create(['nama' => 'Bencana Alam', 'slug' => 'bencana-alam', 'aktif' => true]);

        return Kausa::create([
            'instansi_id' => $instansi->id,
            'kategori_kausa_id' => $kategori->id,
            'judul' => 'Bantuan Banjir Kali Ngrowo',
            'slug' => 'bantuan-banjir-kali-ngrowo',
            'deskripsi' => 'Bantuan korban banjir Kali Ngrowo',
            'lokasi' => 'Tulungagung',
            'target_dana' => 10000000,
            'dana_terkumpul' => 0,
            'status' => 'disetujui',
        ]);
    }

    public function test_user_can_submit_donation_for_approved_kausa(): void
    {
        $kausa = $this->setupKausa();

        $response = $this->post(route('donasi.store', $kausa->slug), [
            'nominal' => 50000,
            'nama_donatur' => 'Ahmad Donatur',
            'email_donatur' => 'ahmad@example.test',
            'telepon_donatur' => '081234567890',
            'metode_pembayaran' => 'qris',
            'doa_dukungan' => 'Semoga lekas surut airnya.',
        ]);

        $this->assertDatabaseHas('donasi', [
            'kausa_id' => $kausa->id,
            'nominal' => 50000,
            'nama_donatur' => 'Ahmad Donatur',
            'status' => 'pending',
        ]);

        $donasi = Donasi::first();
        $response->assertRedirect(route('donasi.bayar', $donasi->pesanan_pembayaran));
    }

    public function test_payment_page_can_be_rendered(): void
    {
        $kausa = $this->setupKausa();

        $donasi = Donasi::create([
            'kausa_id' => $kausa->id,
            'pesanan_pembayaran' => 'DON-TA-202610-TEST1',
            'nama_donatur' => 'Budi',
            'nominal' => 100000,
            'status' => 'pending',
        ]);

        $response = $this->get(route('donasi.bayar', $donasi->pesanan_pembayaran));

        $response->assertStatus(200);
        $response->assertSee('Selesaikan Penyaluran Donasi');
        $response->assertSee('DON-TA-202610-TEST1');
    }

    public function test_simulation_payment_updates_donation_and_kausa_total(): void
    {
        $kausa = $this->setupKausa();

        $donasi = Donasi::create([
            'kausa_id' => $kausa->id,
            'pesanan_pembayaran' => 'DON-TA-202610-TEST2',
            'nama_donatur' => 'Cahya',
            'nominal' => 75000,
            'status' => 'pending',
        ]);

        $response = $this->post(route('donasi.simulate', $donasi->pesanan_pembayaran));

        $response->assertRedirect(route('donasi.sukses', $donasi->pesanan_pembayaran));
        $this->assertEquals('success', $donasi->fresh()->status);
        $this->assertNotNull($donasi->fresh()->dibayar_pada);
        $this->assertEquals(75000, $kausa->fresh()->dana_terkumpul);
    }

    public function test_success_and_receipt_pages_accessible(): void
    {
        $kausa = $this->setupKausa();

        $donasi = Donasi::create([
            'kausa_id' => $kausa->id,
            'pesanan_pembayaran' => 'DON-TA-202610-TEST3',
            'nama_donatur' => 'Dewi',
            'nominal' => 200000,
            'status' => 'success',
            'dibayar_pada' => now(),
        ]);

        $resSuccess = $this->get(route('donasi.sukses', $donasi->pesanan_pembayaran));
        $resSuccess->assertStatus(200);
        $resSuccess->assertSee('Donasi Berhasil Diterima');

        $resReceipt = $this->get(route('donasi.kuitansi', $donasi->pesanan_pembayaran));
        $resReceipt->assertStatus(200);
        $resReceipt->assertSee('Kuitansi Donasi Digital');
        $resReceipt->assertSee('200.000');
    }

    public function test_donor_dashboard_shows_successful_donation(): void
    {
        $kausa = $this->setupKausa();
        $donaturUser = User::factory()->create(['role' => 'donatur', 'peran' => 'donatur']);

        Donasi::create([
            'kausa_id' => $kausa->id,
            'user_id' => $donaturUser->id,
            'pesanan_pembayaran' => 'DON-TA-202610-TEST4',
            'nama_donatur' => $donaturUser->name,
            'nominal' => 50000,
            'status' => 'success',
            'dibayar_pada' => now(),
        ]);

        $response = $this->actingAs($donaturUser)->get(route('donatur.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('DON-TA-202610-TEST4');
        $response->assertSee('Kuitansi Sah');
    }
}
