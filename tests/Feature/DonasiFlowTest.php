<?php

namespace Tests\Feature;

use App\Models\Donasi;
use App\Models\Instansi;
use App\Models\KategoriKausa;
use App\Models\Kausa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonasiFlowTest extends TestCase
{
    use RefreshDatabase;

    protected User $instansiUser;

    protected Instansi $instansi;

    protected Kausa $kausa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->instansiUser = User::factory()->create([
            'peran' => 'institution_user',
            'role' => 'instansi',
        ]);

        $this->instansi = Instansi::create([
            'user_id' => $this->instansiUser->id,
            'nama' => 'Dinas Sosial Kabupaten Tulungagung',
            'jenis' => 'OPD',
            'status_verifikasi' => 'terverifikasi',
        ]);

        $kategori = KategoriKausa::create([
            'nama' => 'Bencana Alam & Tanggap Darurat',
            'slug' => 'bencana-alam-tanggap-darurat',
            'aktif' => true,
        ]);

        $this->kausa = Kausa::create([
            'instansi_id' => $this->instansi->id,
            'kategori_kausa_id' => $kategori->id,
            'judul' => 'Bantuan Bencana Longsor Sendang Tulungagung',
            'slug' => 'bantuan-bencana-longsor-sendang-tulungagung',
            'lokasi' => 'Kecamatan Sendang',
            'target_dana' => 100000000,
            'dana_terkumpul' => 10000000,
            'deskripsi' => 'Penggalangan dana resmi untuk pemulihan warga terdampak longsor.',
            'status' => 'disetujui',
        ]);
    }

    public function test_guest_can_initiate_donation_for_active_kausa(): void
    {
        $response = $this->post(route('donasi.store', $this->kausa->slug), [
            'nominal' => 50000,
            'nama_donatur' => 'Ahmad Santoso',
            'email_donatur' => 'ahmad@example.test',
            'telepon_donatur' => '081234567890',
            'doa_dukungan' => 'Semoga lekas pulih warga Sendang Tulungagung.',
            'metode_pembayaran' => 'qris',
            'anonim' => 0,
        ]);

        $this->assertDatabaseHas('donasi', [
            'kausa_id' => $this->kausa->id,
            'nominal' => '50000.00',
            'nama_donatur' => 'Ahmad Santoso',
            'doa_dukungan' => 'Semoga lekas pulih warga Sendang Tulungagung.',
            'status' => Donasi::STATUS_PENDING,
        ]);

        $donasi = Donasi::where('kausa_id', $this->kausa->id)->first();
        $this->assertNotNull($donasi);
        $this->assertNotNull($donasi->pesanan_pembayaran);

        $response->assertRedirect(route('donasi.bayar', $donasi->pesanan_pembayaran));
    }

    public function test_authenticated_donatur_is_associated_with_donation(): void
    {
        $donatur = User::factory()->create([
            'peran' => 'donatur',
            'role' => 'donatur',
            'name' => 'Siti Rahmawati',
        ]);

        $response = $this->actingAs($donatur)->post(route('donasi.store', $this->kausa->slug), [
            'nominal' => 100000,
            'anonim' => 1,
            'doa_dukungan' => 'Semoga berkah.',
            'metode_pembayaran' => 'bank',
        ]);

        $donasi = Donasi::where('user_id', $donatur->id)->first();
        $this->assertNotNull($donasi);
        $this->assertEquals($donatur->id, $donasi->user_id);
        $this->assertTrue((bool) $donasi->anonim);

        $response->assertRedirect(route('donasi.bayar', $donasi->pesanan_pembayaran));
    }

    public function test_donation_requires_valid_minimum_nominal(): void
    {
        $response = $this->post(route('donasi.store', $this->kausa->slug), [
            'nominal' => 5000, // Di bawah minimum Rp 10.000
        ]);

        $response->assertSessionHasErrors(['nominal']);
        $this->assertDatabaseMissing('donasi', [
            'kausa_id' => $this->kausa->id,
            'nominal' => '5000.00',
        ]);
    }

    public function test_payment_page_displays_donation_details(): void
    {
        $donasi = Donasi::create([
            'kausa_id' => $this->kausa->id,
            'pesanan_pembayaran' => 'INV-202610-TEST1',
            'nominal' => 250000,
            'nama_donatur' => 'Budi Setiawan',
            'status' => Donasi::STATUS_PENDING,
        ]);

        $response = $this->get(route('donasi.bayar', $donasi->pesanan_pembayaran));

        $response->assertStatus(200);
        $response->assertSee('INV-202610-TEST1');
        $response->assertSee('250.000');
        $response->assertSee('Bantuan Bencana Longsor Sendang Tulungagung');
    }

    public function test_payment_simulation_transitions_status_to_success_and_increments_funds(): void
    {
        $initialFunds = (float) $this->kausa->dana_terkumpul;
        $donationAmount = 500000.0;

        $donasi = Donasi::create([
            'kausa_id' => $this->kausa->id,
            'pesanan_pembayaran' => 'INV-202610-TEST2',
            'nominal' => $donationAmount,
            'nama_donatur' => 'Hartono',
            'status' => Donasi::STATUS_PENDING,
        ]);

        $donasi->transaksiPembayaran()->create([
            'penyedia' => 'simulasi',
            'status' => 'menunggu',
        ]);

        $response = $this->post(route('donasi.simulate', $donasi->pesanan_pembayaran));

        $response->assertRedirect(route('donasi.sukses', $donasi->pesanan_pembayaran));

        $donasi->refresh();
        $this->assertEquals(Donasi::STATUS_SUCCESS, $donasi->status);
        $this->assertNotNull($donasi->dibayar_pada);

        $this->kausa->refresh();
        $this->assertEquals($initialFunds + $donationAmount, (float) $this->kausa->dana_terkumpul);

        // Notifikasi ke instansi pemilik kausa
        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $this->instansiUser->id,
            'jenis' => 'donasi_masuk',
        ]);
    }

    public function test_duplicate_payment_simulation_does_not_double_increment_funds(): void
    {
        $donasi = Donasi::create([
            'kausa_id' => $this->kausa->id,
            'pesanan_pembayaran' => 'INV-202610-TEST3',
            'nominal' => 200000,
            'nama_donatur' => 'Donatur Test',
            'status' => Donasi::STATUS_PENDING,
        ]);

        $donasi->transaksiPembayaran()->create([
            'penyedia' => 'simulasi',
            'status' => 'menunggu',
        ]);

        // Simulasi pertama
        $this->post(route('donasi.simulate', $donasi->pesanan_pembayaran));
        $this->kausa->refresh();
        $expectedFunds = (float) $this->kausa->dana_terkumpul;

        // Simulasi kedua (duplicate request)
        $this->post(route('donasi.simulate', $donasi->pesanan_pembayaran));
        $this->kausa->refresh();

        $this->assertEquals($expectedFunds, (float) $this->kausa->dana_terkumpul);
    }

    public function test_success_page_displays_confirmation(): void
    {
        $donasi = Donasi::create([
            'kausa_id' => $this->kausa->id,
            'pesanan_pembayaran' => 'INV-202610-TEST4',
            'nominal' => 75000,
            'nama_donatur' => 'Rina Wijaya',
            'status' => Donasi::STATUS_SUCCESS,
            'dibayar_pada' => now(),
        ]);

        $response = $this->get(route('donasi.sukses', $donasi->pesanan_pembayaran));

        $response->assertStatus(200);
        $response->assertSee('Donasi Berhasil Diterima');
        $response->assertSee('INV-202610-TEST4');
    }

    public function test_digital_receipt_page_accessible_for_successful_donation(): void
    {
        $donasi = Donasi::create([
            'kausa_id' => $this->kausa->id,
            'pesanan_pembayaran' => 'INV-202610-TEST5',
            'nominal' => 150000,
            'nama_donatur' => 'Bambang Irawan',
            'status' => Donasi::STATUS_SUCCESS,
            'dibayar_pada' => now(),
        ]);

        $response = $this->get(route('donasi.kuitansi', $donasi->pesanan_pembayaran));

        $response->assertStatus(200);
        $response->assertSee('Pemerintah Kabupaten Tulungagung');
        $response->assertSee('Kuitansi Donasi Digital');
        $response->assertSee('INV-202610-TEST5');
        $response->assertSee('150.000');
    }

    public function test_donatur_dashboard_lists_user_donations_with_receipt_link(): void
    {
        $donatur = User::factory()->create([
            'peran' => 'donatur',
            'role' => 'donatur',
        ]);

        $donasi = Donasi::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $donatur->id,
            'pesanan_pembayaran' => 'INV-202610-TEST6',
            'nominal' => 300000,
            'nama_donatur' => $donatur->name,
            'status' => Donasi::STATUS_SUCCESS,
            'dibayar_pada' => now(),
        ]);

        $response = $this->actingAs($donatur)->get(route('donatur.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('300.000');
        $response->assertSee(route('donasi.kuitansi', $donasi->pesanan_pembayaran));
    }
}
