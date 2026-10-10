<?php

namespace Tests\Feature;

use App\Models\Donasi;
use App\Models\Instansi;
use App\Models\KategoriKausa;
use App\Models\Kausa;
use App\Models\LaporanDana;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotifikasiTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $instansiUser;

    protected Instansi $instansi;

    protected Kausa $kausa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'peran' => 'admin',
        ]);

        $this->instansiUser = User::factory()->create([
            'role' => 'instansi',
            'peran' => 'institution_user',
        ]);

        $this->instansi = Instansi::create([
            'user_id' => $this->instansiUser->id,
            'nama' => 'Dinas Sosial Kabupaten Tulungagung',
            'jenis' => 'OPD',
            'status_verifikasi' => 'terverifikasi',
        ]);

        $kategori = KategoriKausa::create([
            'nama' => 'Sosial Kemanusiaan',
            'slug' => 'sosial-kemanusiaan',
            'aktif' => true,
        ]);

        $this->kausa = Kausa::create([
            'instansi_id' => $this->instansi->id,
            'kategori_kausa_id' => $kategori->id,
            'judul' => 'Bantuan Rehabilitasi Rumah Lansia',
            'slug' => 'bantuan-rehabilitasi-rumah-lansia',
            'deskripsi' => 'Program perbaikan hunian warga lansia sebatang kara',
            'lokasi' => 'Kecamatan Ngunut',
            'target_dana' => 25000000,
            'dana_terkumpul' => 0,
            'status' => 'menunggu_verifikasi',
        ]);
    }

    public function test_notifikasi_terkirim_saat_kausa_disetujui(): void
    {
        $response = $this->actingAs($this->admin)->post(route('dashboard.admin.verify', $this->kausa));
        $response->assertRedirect(route('dashboard.admin'));

        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $this->instansiUser->id,
            'jenis' => 'kausa_disetujui',
        ]);
    }

    public function test_notifikasi_terkirim_saat_kausa_ditolak(): void
    {
        $response = $this->actingAs($this->admin)->post(route('dashboard.admin.reject', $this->kausa), [
            'alasan_penolakan' => 'Dokumen pendukung SK penetapan tidak lengkap atau tidak terbaca.',
        ]);
        $response->assertRedirect(route('dashboard.admin'));

        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $this->instansiUser->id,
            'jenis' => 'kausa_ditolak',
        ]);
    }

    public function test_notifikasi_terkirim_saat_kausa_diminta_revisi(): void
    {
        $response = $this->actingAs($this->admin)->post(route('dashboard.admin.revise', $this->kausa), [
            'catatan_revisi' => 'Mohon sesuaikan rincian estimasi biaya material dengan standar harga Pemkab.',
        ]);
        $response->assertRedirect(route('dashboard.admin'));

        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $this->instansiUser->id,
            'jenis' => 'kausa_perlu_diperbaiki',
        ]);
    }

    public function test_notifikasi_terkirim_saat_lpj_disetujui_dan_direvisi(): void
    {
        $laporan = LaporanDana::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->instansiUser->id,
            'judul' => 'LPJ Tahap 1 Penyaluran Bahan Bangunan',
            'total_digunakan' => 15000000,
            'status' => 'menunggu_verifikasi',
        ]);

        // Verifikasi LPJ
        $this->actingAs($this->admin)->post(route('admin.laporan.verify', $laporan));

        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $this->instansiUser->id,
            'jenis' => 'lpj_disetujui',
        ]);

        // Revisi LPJ
        $laporan->update(['status' => 'menunggu_verifikasi']);
        $this->actingAs($this->admin)->post(route('admin.laporan.revise', $laporan), [
            'catatan_revisi' => 'Nota toko nomor 45 belum tertera stempel basah penyedia.',
        ]);

        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $this->instansiUser->id,
            'jenis' => 'lpj_perlu_revisi',
        ]);
    }

    public function test_notifikasi_terkirim_ke_donatur_dan_instansi_saat_donasi_berhasil(): void
    {
        $this->kausa->update(['status' => 'disetujui']);

        $donaturUser = User::factory()->create([
            'role' => 'donatur',
            'peran' => 'donatur',
        ]);

        $donasi = Donasi::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $donaturUser->id,
            'pesanan_pembayaran' => 'INV-202610-TEST1',
            'nominal' => 200000,
            'nama_donatur' => $donaturUser->name,
            'status' => Donasi::STATUS_PENDING,
        ]);

        $this->actingAs($donaturUser)->post(route('donasi.simulate', $donasi->pesanan_pembayaran));

        // Donatur notif
        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $donaturUser->id,
            'jenis' => 'donasi_berhasil',
        ]);

        // Instansi owner notif
        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $this->instansiUser->id,
            'jenis' => 'donasi_masuk',
        ]);
    }

    public function test_user_can_view_notifications_and_mark_as_read(): void
    {
        $notif = Notifikasi::create([
            'user_id' => $this->instansiUser->id,
            'jenis' => 'kausa_disetujui',
            'judul' => 'Kausa Anda Disetujui',
            'isi' => 'Kausa telah tayang ke publik.',
            'tautan' => null,
            'dibaca_pada' => null,
        ]);

        $response = $this->actingAs($this->instansiUser)->get(route('notifikasi.index'));
        $response->assertOk();
        $response->assertSee('Kausa Anda Disetujui');

        // Mark single as read
        $markResponse = $this->actingAs($this->instansiUser)->post(route('notifikasi.read', $notif));
        $markResponse->assertRedirect();

        $this->assertNotNull($notif->fresh()->dibaca_pada);
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        Notifikasi::create([
            'user_id' => $this->instansiUser->id,
            'jenis' => 'kausa_disetujui',
            'judul' => 'Notif 1',
            'isi' => 'Isi 1',
            'dibaca_pada' => null,
        ]);

        Notifikasi::create([
            'user_id' => $this->instansiUser->id,
            'jenis' => 'kausa_disetujui',
            'judul' => 'Notif 2',
            'isi' => 'Isi 2',
            'dibaca_pada' => null,
        ]);

        $response = $this->actingAs($this->instansiUser)->post(route('notifikasi.readAll'));
        $response->assertRedirect();

        $unreadCount = Notifikasi::where('user_id', $this->instansiUser->id)->whereNull('dibaca_pada')->count();
        $this->assertEquals(0, $unreadCount);
    }

    public function test_user_cannot_mark_other_users_notification(): void
    {
        $otherUser = User::factory()->create(['role' => 'donatur', 'peran' => 'donatur']);

        $notifOther = Notifikasi::create([
            'user_id' => $otherUser->id,
            'jenis' => 'donasi_berhasil',
            'judul' => 'Donasi Sukses',
            'isi' => 'Terima kasih',
            'dibaca_pada' => null,
        ]);

        $response = $this->actingAs($this->instansiUser)->post(route('notifikasi.read', $notifOther));
        $response->assertStatus(403);
    }

    public function test_mark_read_does_not_redirect_to_external_url(): void
    {
        $notif = Notifikasi::create([
            'user_id' => $this->instansiUser->id,
            'jenis' => 'kausa_disetujui',
            'judul' => 'Kausa Disetujui',
            'isi' => 'Kausa aktif',
            'dibaca_pada' => null,
        ]);

        // Attempt open redirect with external URL
        $response = $this->actingAs($this->instansiUser)->post(route('notifikasi.read', $notif), [
            'redirect_to' => 'https://evil.com/attack',
        ]);

        $this->assertFalse(str_contains($response->headers->get('Location') ?? '', 'evil.com'));

        // Attempt open redirect with protocol relative URL
        $response2 = $this->actingAs($this->instansiUser)->post(route('notifikasi.read', $notif), [
            'redirect_to' => '//evil.com/attack',
        ]);

        $this->assertFalse(str_contains($response2->headers->get('Location') ?? '', 'evil.com'));
    }
}
