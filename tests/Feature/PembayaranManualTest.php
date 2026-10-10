<?php

namespace Tests\Feature;

use App\Models\Donasi;
use App\Models\Instansi;
use App\Models\KategoriKausa;
use App\Models\Kausa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PembayaranManualTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $donatur;

    protected User $instansiUser;

    protected Kausa $kausa;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Storage::fake('local');

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'peran' => 'admin',
        ]);

        $this->instansiUser = User::factory()->create([
            'role' => 'instansi',
            'peran' => 'institution_user',
        ]);

        $instansi = Instansi::create([
            'user_id' => $this->instansiUser->id,
            'nama' => 'Dinas Pendidikan Tulungagung',
            'jenis' => 'OPD',
            'status_verifikasi' => 'terverifikasi',
        ]);

        $kategori = KategoriKausa::create([
            'nama' => 'Pendidikan Inklusif',
            'slug' => 'pendidikan-inklusif',
            'aktif' => true,
        ]);

        $this->kausa = Kausa::create([
            'instansi_id' => $instansi->id,
            'kategori_kausa_id' => $kategori->id,
            'judul' => 'Beasiswa Anak Yatim Piatu',
            'slug' => 'beasiswa-anak-yatim-piatu',
            'deskripsi' => 'Bantuan perlengkapan dan SPP sekolah',
            'lokasi' => 'Kabupaten Tulungagung',
            'target_dana' => 20000000,
            'dana_terkumpul' => 0,
            'status' => 'disetujui',
        ]);

        $this->donatur = User::factory()->create([
            'role' => 'donatur',
            'peran' => 'donatur',
        ]);
    }

    public function test_donor_can_upload_manual_payment_proof(): void
    {
        $donasi = Donasi::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->donatur->id,
            'pesanan_pembayaran' => 'INV-202610-MAN01',
            'nominal' => 250000,
            'nama_donatur' => $this->donatur->name,
            'metode_pembayaran' => 'transfer',
            'status' => Donasi::STATUS_PENDING,
        ]);

        $file = UploadedFile::fake()->image('bukti_transfer.jpg', 600, 600);

        $response = $this->actingAs($this->donatur)->post(route('donasi.uploadBukti', $donasi->pesanan_pembayaran), [
            'bukti_transfer' => $file,
        ]);

        $response->assertRedirect(route('donasi.bayar', $donasi->pesanan_pembayaran));

        $donasi->refresh();
        $this->assertEquals(Donasi::STATUS_MENUNGGU_VERIFIKASI_MANUAL, $donasi->status);
        $this->assertNotNull($donasi->path_bukti_manual);
        Storage::disk('local')->assertExists($donasi->path_bukti_manual);
    }

    public function test_admin_can_approve_manual_payment_and_funds_are_incremented(): void
    {
        $donasi = Donasi::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->donatur->id,
            'pesanan_pembayaran' => 'INV-202610-MAN02',
            'nominal' => 500000,
            'nama_donatur' => $this->donatur->name,
            'path_bukti_manual' => 'bukti-manual/test.jpg',
            'status' => Donasi::STATUS_MENUNGGU_VERIFIKASI_MANUAL,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.donasi.approveManual', $donasi), [
            'catatan_verifikasi_manual' => 'Transfer mutasi rekening koran Bank Jatim cocok.',
        ]);

        $response->assertRedirect(route('admin.donasi'));

        $donasi->refresh();
        $this->assertEquals(Donasi::STATUS_SUCCESS, $donasi->status);
        $this->assertNotNull($donasi->dibayar_pada);

        $this->kausa->refresh();
        $this->assertEquals(500000, (float) $this->kausa->dana_terkumpul);

        // Notifikasi donatur
        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $this->donatur->id,
            'jenis' => 'donasi_berhasil',
        ]);

        // Notifikasi instansi
        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $this->instansiUser->id,
            'jenis' => 'donasi_masuk',
        ]);
    }

    public function test_admin_approve_is_idempotent_and_does_not_double_count_funds(): void
    {
        $donasi = Donasi::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->donatur->id,
            'pesanan_pembayaran' => 'INV-202610-MAN03',
            'nominal' => 300000,
            'nama_donatur' => $this->donatur->name,
            'path_bukti_manual' => 'bukti-manual/test.jpg',
            'status' => Donasi::STATUS_MENUNGGU_VERIFIKASI_MANUAL,
        ]);

        // First approval
        $this->actingAs($this->admin)->post(route('admin.donasi.approveManual', $donasi));

        $this->kausa->refresh();
        $this->assertEquals(300000, (float) $this->kausa->dana_terkumpul);

        // Second duplicate approval
        $this->actingAs($this->admin)->post(route('admin.donasi.approveManual', $donasi));

        $this->kausa->refresh();
        $this->assertEquals(300000, (float) $this->kausa->dana_terkumpul);
    }

    public function test_admin_can_reject_manual_payment_with_mandatory_note(): void
    {
        $donasi = Donasi::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->donatur->id,
            'pesanan_pembayaran' => 'INV-202610-MAN04',
            'nominal' => 150000,
            'nama_donatur' => $this->donatur->name,
            'path_bukti_manual' => 'bukti-manual/test.jpg',
            'status' => Donasi::STATUS_MENUNGGU_VERIFIKASI_MANUAL,
        ]);

        // Rejection without note should fail validation
        $failResponse = $this->actingAs($this->admin)->post(route('admin.donasi.rejectManual', $donasi), [
            'catatan_verifikasi_manual' => '',
        ]);
        $failResponse->assertSessionHasErrors('catatan_verifikasi_manual');

        // Rejection with valid note
        $successResponse = $this->actingAs($this->admin)->post(route('admin.donasi.rejectManual', $donasi), [
            'catatan_verifikasi_manual' => 'Foto struk buram dan tanggal transfer tidak terbaca.',
        ]);
        $successResponse->assertRedirect(route('admin.donasi'));

        $donasi->refresh();
        $this->assertEquals(Donasi::STATUS_DITOLAK_MANUAL, $donasi->status);
        $this->assertEquals('Foto struk buram dan tanggal transfer tidak terbaca.', $donasi->catatan_verifikasi_manual);

        // Notifikasi donatur
        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $this->donatur->id,
            'jenis' => 'donasi_ditolak',
        ]);
    }

    public function test_non_admin_cannot_approve_or_reject_manual_payment(): void
    {
        $donasi = Donasi::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->donatur->id,
            'pesanan_pembayaran' => 'INV-202610-MAN05',
            'nominal' => 100000,
            'status' => Donasi::STATUS_MENUNGGU_VERIFIKASI_MANUAL,
        ]);

        // Guest rejected
        $guestResp = $this->post(route('admin.donasi.approveManual', $donasi));
        $guestResp->assertRedirect();

        // Donatur rejected (403 or redirect depending on middleware)
        $donaturResp = $this->actingAs($this->donatur)->post(route('admin.donasi.approveManual', $donasi));
        $this->assertTrue(in_array($donaturResp->status(), [403, 302], true));
    }

    public function test_cannot_upload_proof_if_donation_already_success(): void
    {
        $donasi = Donasi::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->donatur->id,
            'pesanan_pembayaran' => 'INV-202610-MAN06',
            'nominal' => 200000,
            'nama_donatur' => $this->donatur->name,
            'metode_pembayaran' => 'transfer',
            'status' => Donasi::STATUS_SUCCESS,
        ]);

        $file = UploadedFile::fake()->image('bukti_transfer.jpg', 600, 600);

        $response = $this->actingAs($this->donatur)->post(route('donasi.uploadBukti', $donasi->pesanan_pembayaran), [
            'bukti_transfer' => $file,
        ]);

        $response->assertStatus(422);
    }

    public function test_admin_cannot_reject_already_successful_donation(): void
    {
        $donasi = Donasi::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->donatur->id,
            'pesanan_pembayaran' => 'INV-202610-MAN07',
            'nominal' => 200000,
            'nama_donatur' => $this->donatur->name,
            'path_bukti_manual' => 'bukti-manual/test.jpg',
            'status' => Donasi::STATUS_SUCCESS,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.donasi.rejectManual', $donasi), [
            'catatan_verifikasi_manual' => 'Penolakan tidak sah atas donasi sukses.',
        ]);

        $response->assertStatus(422);
    }

    public function test_admin_can_view_proof_file_via_protected_route(): void
    {
        Storage::disk('local')->put('bukti-manual/secret_proof.jpg', 'fake-image-binary-data');

        $donasi = Donasi::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->donatur->id,
            'pesanan_pembayaran' => 'INV-202610-MAN08',
            'nominal' => 200000,
            'nama_donatur' => $this->donatur->name,
            'path_bukti_manual' => 'bukti-manual/secret_proof.jpg',
            'status' => Donasi::STATUS_MENUNGGU_VERIFIKASI_MANUAL,
        ]);

        // Admin can access
        $adminResp = $this->actingAs($this->admin)->get(route('admin.donasi.bukti', $donasi));
        $adminResp->assertOk();

        // Non-admin cannot access admin endpoint
        $donorResp = $this->actingAs($this->donatur)->get(route('admin.donasi.bukti', $donasi));
        $this->assertTrue(in_array($donorResp->status(), [403, 302], true));
    }

    public function test_donor_can_view_own_proof_file_and_others_are_forbidden(): void
    {
        Storage::disk('local')->put('bukti-manual/donor_proof.jpg', 'fake-image-binary-data');

        $donasi = Donasi::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->donatur->id,
            'pesanan_pembayaran' => 'INV-202610-MAN09',
            'nominal' => 350000,
            'nama_donatur' => $this->donatur->name,
            'path_bukti_manual' => 'bukti-manual/donor_proof.jpg',
            'status' => Donasi::STATUS_MENUNGGU_VERIFIKASI_MANUAL,
        ]);

        // Owner donor can access
        $ownerResp = $this->actingAs($this->donatur)->get(route('donasi.bukti', $donasi->pesanan_pembayaran));
        $ownerResp->assertOk();

        // Another donor is forbidden
        $otherDonor = User::factory()->create([
            'role' => 'donatur',
            'peran' => 'donatur',
        ]);
        $otherResp = $this->actingAs($otherDonor)->get(route('donasi.bukti', $donasi->pesanan_pembayaran));
        $otherResp->assertStatus(403);
    }
}
