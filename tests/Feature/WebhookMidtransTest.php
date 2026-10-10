<?php

namespace Tests\Feature;

use App\Models\Donasi;
use App\Models\Instansi;
use App\Models\KategoriKausa;
use App\Models\Kausa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class WebhookMidtransTest extends TestCase
{
    use RefreshDatabase;

    protected User $instansiUser;

    protected User $donatur;

    protected Kausa $kausa;

    protected string $serverKey = 'test-midtrans-server-key-tulungagung';

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('services.midtrans.server_key', $this->serverKey);

        $this->instansiUser = User::factory()->create([
            'role' => 'instansi',
            'peran' => 'institution_user',
        ]);

        $instansi = Instansi::create([
            'user_id' => $this->instansiUser->id,
            'nama' => 'BPBD Kabupaten Tulungagung',
            'jenis' => 'OPD',
            'status_verifikasi' => 'terverifikasi',
        ]);

        $kategori = KategoriKausa::create([
            'nama' => 'Tanggap Darurat',
            'slug' => 'tanggap-darurat',
            'aktif' => true,
        ]);

        $this->kausa = Kausa::create([
            'instansi_id' => $instansi->id,
            'kategori_kausa_id' => $kategori->id,
            'judul' => 'Bantuan Bencana Longsor Sendang',
            'slug' => 'bantuan-bencana-longsor-sendang',
            'deskripsi' => 'Evakuasi dan bantuan darurat korban tanah longsor',
            'lokasi' => 'Kecamatan Sendang',
            'target_dana' => 50000000,
            'dana_terkumpul' => 1000000,
            'status' => 'disetujui',
        ]);

        $this->donatur = User::factory()->create([
            'role' => 'donatur',
            'peran' => 'donatur',
        ]);
    }

    protected function generateSignature(string $orderId, string $statusCode, string $grossAmount): string
    {
        return hash('sha512', $orderId.$statusCode.$grossAmount.$this->serverKey);
    }

    public function test_valid_settlement_webhook_updates_status_and_increments_funds(): void
    {
        $donasi = Donasi::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->donatur->id,
            'pesanan_pembayaran' => 'INV-202610-WH001',
            'nominal' => 500000,
            'nama_donatur' => 'Budi Santoso',
            'status' => Donasi::STATUS_PENDING,
        ]);

        $orderId = $donasi->pesanan_pembayaran;
        $statusCode = '200';
        $grossAmount = '500000.00';
        $signature = $this->generateSignature($orderId, $statusCode, $grossAmount);

        $payload = [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
            'transaction_id' => 'MID-TRX-12345',
            'payment_type' => 'qris',
        ];

        $response = $this->postJson(route('webhook.midtrans'), $payload);
        $response->assertOk();
        $response->assertJson(['status' => 'success']);

        $donasi->refresh();
        $this->assertEquals(Donasi::STATUS_SUCCESS, $donasi->status);
        $this->assertNotNull($donasi->dibayar_pada);

        $this->kausa->refresh();
        $this->assertEquals(1500000, (float) $this->kausa->dana_terkumpul);

        // Notifikasi donatur dan instansi
        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $this->donatur->id,
            'jenis' => 'donasi_berhasil',
        ]);
        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $this->instansiUser->id,
            'jenis' => 'donasi_masuk',
        ]);
    }

    public function test_invalid_signature_returns_403_and_status_remains_unchanged(): void
    {
        $donasi = Donasi::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->donatur->id,
            'pesanan_pembayaran' => 'INV-202610-WH002',
            'nominal' => 300000,
            'nama_donatur' => 'Siti Nurhaliza',
            'status' => Donasi::STATUS_PENDING,
        ]);

        $payload = [
            'order_id' => $donasi->pesanan_pembayaran,
            'status_code' => '200',
            'gross_amount' => '300000.00',
            'signature_key' => 'invalid-fake-signature-hash',
            'transaction_status' => 'settlement',
        ];

        $response = $this->postJson(route('webhook.midtrans'), $payload);
        $response->assertStatus(403);

        $donasi->refresh();
        $this->assertEquals(Donasi::STATUS_PENDING, $donasi->status);

        $this->kausa->refresh();
        $this->assertEquals(1000000, (float) $this->kausa->dana_terkumpul);
    }

    public function test_duplicate_webhook_is_idempotent_and_does_not_double_increment(): void
    {
        $donasi = Donasi::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->donatur->id,
            'pesanan_pembayaran' => 'INV-202610-WH003',
            'nominal' => 200000,
            'nama_donatur' => 'Rahmat Hidayat',
            'status' => Donasi::STATUS_PENDING,
        ]);

        $orderId = $donasi->pesanan_pembayaran;
        $statusCode = '200';
        $grossAmount = '200000.00';
        $signature = $this->generateSignature($orderId, $statusCode, $grossAmount);

        $payload = [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
        ];

        // First call
        $firstResponse = $this->postJson(route('webhook.midtrans'), $payload);
        $firstResponse->assertOk();

        $this->kausa->refresh();
        $this->assertEquals(1200000, (float) $this->kausa->dana_terkumpul);

        // Second duplicate call
        $secondResponse = $this->postJson(route('webhook.midtrans'), $payload);
        $secondResponse->assertOk();

        $this->kausa->refresh();
        $this->assertEquals(1200000, (float) $this->kausa->dana_terkumpul);
    }

    public function test_expire_webhook_marks_donation_as_expired(): void
    {
        $donasi = Donasi::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->donatur->id,
            'pesanan_pembayaran' => 'INV-202610-WH004',
            'nominal' => 100000,
            'nama_donatur' => 'Dewi Sartika',
            'status' => Donasi::STATUS_PENDING,
        ]);

        $orderId = $donasi->pesanan_pembayaran;
        $statusCode = '200';
        $grossAmount = '100000.00';
        $signature = $this->generateSignature($orderId, $statusCode, $grossAmount);

        $payload = [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_status' => 'expire',
        ];

        $response = $this->postJson(route('webhook.midtrans'), $payload);
        $response->assertOk();

        $donasi->refresh();
        $this->assertEquals(Donasi::STATUS_EXPIRED, $donasi->status);
    }
}
