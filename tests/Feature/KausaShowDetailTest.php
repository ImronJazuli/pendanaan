<?php

namespace Tests\Feature;

use App\Models\Donasi;
use App\Models\Instansi;
use App\Models\KategoriKausa;
use App\Models\Kausa;
use App\Models\LogTransparansi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KausaShowDetailTest extends TestCase
{
    use RefreshDatabase;

    protected User $instansiUser;

    protected Instansi $instansi;

    protected KategoriKausa $kategori;

    protected Kausa $kausa;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->instansiUser = User::factory()->create([
            'role' => 'instansi',
            'peran' => 'institution_user',
        ]);

        $this->instansi = Instansi::create([
            'user_id' => $this->instansiUser->id,
            'nama' => 'Dinsos Tulungagung',
            'jenis' => 'OPD',
            'status_verifikasi' => 'terverifikasi',
        ]);

        $this->kategori = KategoriKausa::create([
            'nama' => 'Bencana Sosial',
            'slug' => 'bencana-sosial',
            'aktif' => true,
        ]);

        $this->kausa = Kausa::create([
            'instansi_id' => $this->instansi->id,
            'kategori_kausa_id' => $this->kategori->id,
            'judul' => 'Bantuan Korban Puting Beliung Kalidawir',
            'slug' => 'bantuan-korban-puting-beliung-kalidawir',
            'ringkasan' => 'Bantuan perbaikan atap rumah warga',
            'deskripsi' => 'Deskripsi perbaikan rumah terdampak angin kencang',
            'lokasi' => 'Kecamatan Kalidawir',
            'target_dana' => 10000000,
            'dana_terkumpul' => 500000,
            'status' => 'disetujui',
        ]);
    }

    public function test_transparency_tab_only_shows_published_logs_belonging_to_the_campaign(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'peran' => 'admin']);

        // Published log for this kausa
        $logPublished = LogTransparansi::create([
            'kausa_id' => $this->kausa->id,
            'admin_id' => $admin->id,
            'judul' => 'Pembelian Genteng dan Asbes Tahap 1',
            'deskripsi' => 'Pengadaan material genteng untuk 5 rumah',
            'nominal' => 3500000,
            'dipublikasikan' => true,
            'dipublikasikan_pada' => now(),
        ]);

        // Draft/unpublished log for this kausa (should not be shown)
        $logDraft = LogTransparansi::create([
            'kausa_id' => $this->kausa->id,
            'admin_id' => $admin->id,
            'judul' => 'Draft Pengeluaran Belum Valid',
            'deskripsi' => 'Catatan internal staf pengadaan',
            'nominal' => 1000000,
            'dipublikasikan' => false,
        ]);

        // Published log for another kausa
        $otherKausa = Kausa::create([
            'instansi_id' => $this->instansi->id,
            'kategori_kausa_id' => $this->kategori->id,
            'judul' => 'Kausa Lain di Tulungagung',
            'slug' => 'kausa-lain-di-tulungagung',
            'deskripsi' => 'Deskripsi kausa lain',
            'lokasi' => 'Tulungagung',
            'target_dana' => 5000000,
            'dana_terkumpul' => 0,
            'status' => 'disetujui',
        ]);

        $logOther = LogTransparansi::create([
            'kausa_id' => $otherKausa->id,
            'admin_id' => $admin->id,
            'judul' => 'Realisasi Program Lainnya',
            'deskripsi' => 'Nota program lain',
            'nominal' => 2000000,
            'dipublikasikan' => true,
            'dipublikasikan_pada' => now(),
        ]);

        $response = $this->get(route('kausa.show', $this->kausa->slug));
        $response->assertOk();

        // Must see published log
        $response->assertSee('Pembelian Genteng dan Asbes Tahap 1');
        $response->assertSee(number_format(3500000, 0, ',', '.'));

        // Must NOT see draft log or other campaign's log
        $response->assertDontSee('Draft Pengeluaran Belum Valid');
        $response->assertDontSee('Realisasi Program Lainnya');
    }

    public function test_anonymous_donor_is_displayed_as_hamba_allah_in_donor_history(): void
    {
        $donaturUser = User::factory()->create(['name' => 'John Doe Rahasia']);

        // Donasi anonim
        Donasi::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $donaturUser->id,
            'pesanan_pembayaran' => 'INV-202610-ANON01',
            'nominal' => 150000,
            'nama_donatur' => 'John Doe Rahasia',
            'anonim' => true,
            'doa_dukungan' => 'Semoga berkah untuk semua korban.',
            'status' => Donasi::STATUS_SUCCESS,
        ]);

        // Donasi publik dengan nama terang
        Donasi::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => null,
            'pesanan_pembayaran' => 'INV-202610-PUB01',
            'nominal' => 200000,
            'nama_donatur' => 'Slamet Riyadi',
            'anonim' => false,
            'status' => Donasi::STATUS_SUCCESS,
        ]);

        $response = $this->get(route('kausa.show', $this->kausa->slug));
        $response->assertOk();

        // Anonim: must see 'Hamba Allah' and must NOT see 'John Doe Rahasia'
        $response->assertSee('Hamba Allah');
        $response->assertDontSee('John Doe Rahasia');

        // Non-anonim: must see 'Slamet Riyadi'
        $response->assertSee('Slamet Riyadi');
    }

    public function test_gallery_photo_upload_saves_to_dokumen_kausa(): void
    {
        $file1 = UploadedFile::fake()->image('foto1.jpg', 800, 600);
        $file2 = UploadedFile::fake()->image('foto2.png', 800, 600);

        $response = $this->actingAs($this->instansiUser)->post(route('kausa.store'), [
            'judul' => 'Kausa Baru Dengan Galeri',
            'kategori_kausa_id' => $this->kategori->id,
            'lokasi' => 'Kecamatan Campurdarat',
            'ringkasan' => 'Ringkasan galeri',
            'deskripsi' => 'Deskripsi lengkap pengajuan kausa bergaleri foto',
            'target_dana' => 15000000,
            'action' => 'draft',
            'foto_kausa' => [$file1, $file2],
        ]);

        $response->assertRedirect(route('kausa.create'));

        $kausaBaru = Kausa::where('judul', 'Kausa Baru Dengan Galeri')->first();
        $this->assertNotNull($kausaBaru);

        $this->assertDatabaseHas('dokumen_kausa', [
            'kausa_id' => $kausaBaru->id,
            'jenis_dokumen' => 'foto_galeri',
            'nama_file' => 'foto1.jpg',
        ]);

        $this->assertDatabaseHas('dokumen_kausa', [
            'kausa_id' => $kausaBaru->id,
            'jenis_dokumen' => 'foto_galeri',
            'nama_file' => 'foto2.png',
        ]);
    }
}
