<?php

namespace Tests\Feature;

use App\Models\Instansi;
use App\Models\KategoriKausa;
use App\Models\Kausa;
use App\Models\LaporanDana;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InstansiLaporanDanaTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Instansi $instansi;

    protected Kausa $kausa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'peran' => 'institution_user',
            'role' => 'instansi',
        ]);

        $this->instansi = Instansi::create([
            'user_id' => $this->user->id,
            'nama' => 'Yayasan Peduli Tulungagung',
            'jenis' => 'Yayasan',
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
            'judul' => 'Santunan Anak Yatim dan Dhuafa Pagerwojo',
            'slug' => 'santunan-anak-yatim-dan-dhuafa-pagerwojo',
            'lokasi' => 'Kecamatan Pagerwojo',
            'target_dana' => 30000000,
            'dana_terkumpul' => 30000000,
            'deskripsi' => 'Program santunan santri yatim dan dhuafa',
            'status' => 'disetujui',
        ]);
    }

    public function test_instansi_can_view_laporan_index_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('instansi.laporan'));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.instansi.laporan');
        $response->assertSee('Laporan Pertanggungjawaban (LPJ) Dana');
        $response->assertSee('Santunan Anak Yatim dan Dhuafa Pagerwojo');
    }

    public function test_instansi_can_view_laporan_create_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('instansi.laporan.create', ['kausa_id' => $this->kausa->id]));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.instansi.laporan-create');
        $response->assertSee('Formulir Laporan Pertanggungjawaban (LPJ)');
    }

    public function test_instansi_can_store_laporan_as_draft_with_itemized_expenses(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)->post(route('instansi.laporan.store'), [
            'kausa_id' => $this->kausa->id,
            'judul' => 'Draf LPJ Penyaluran Tahap 1',
            'ringkasan' => 'Realisasi pembelian paket sembako untuk 50 anak yatim.',
            'periode_mulai' => now()->subDays(5)->toDateString(),
            'periode_selesai' => now()->toDateString(),
            'action' => 'draft',
            'rincian' => [
                [
                    'uraian' => 'Paket sembako 50 paket @ Rp 200.000',
                    'nominal' => '10000000',
                    'tanggal_pengeluaran' => now()->subDays(3)->toDateString(),
                    'penerima_manfaat' => '50 Anak Yatim Desa Pagerwojo',
                    'keterangan' => 'Nota toko grosir Berkah',
                ],
                [
                    'uraian' => 'Sewa armada distribusi sembako',
                    'nominal' => '1500000',
                    'tanggal_pengeluaran' => now()->subDays(2)->toDateString(),
                    'penerima_manfaat' => 'Transportasi logistik',
                ],
            ],
        ]);

        $this->assertDatabaseHas('laporan_dana', [
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->user->id,
            'judul' => 'Draf LPJ Penyaluran Tahap 1',
            'status' => 'draf',
            'total_digunakan' => '11500000.00',
        ]);

        $this->assertDatabaseHas('rincian_laporan_dana', [
            'uraian' => 'Paket sembako 50 paket @ Rp 200.000',
            'nominal' => '10000000.00',
        ]);

        $laporan = LaporanDana::where('judul', 'Draf LPJ Penyaluran Tahap 1')->first();
        $response->assertRedirect(route('instansi.laporan.show', $laporan->id));
    }

    public function test_instansi_can_submit_laporan_and_notifies_admin(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'peran' => 'admin',
            'role' => 'admin',
        ]);

        $fileBukti = UploadedFile::fake()->create('nota-belanja.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->user)->post(route('instansi.laporan.store'), [
            'kausa_id' => $this->kausa->id,
            'judul' => 'LPJ Final Penyaluran Bansos',
            'ringkasan' => 'Laporan lengkap seluruh dana telah disalurkan.',
            'periode_mulai' => now()->subDays(7)->toDateString(),
            'periode_selesai' => now()->toDateString(),
            'action' => 'submit',
            'rincian' => [
                [
                    'uraian' => 'Pengadaan sembako lengkap',
                    'nominal' => '25000000',
                    'tanggal_pengeluaran' => now()->subDays(4)->toDateString(),
                    'penerima_manfaat' => 'Warga penerima bansos',
                    'bukti' => $fileBukti,
                ],
            ],
        ]);

        $this->assertDatabaseHas('laporan_dana', [
            'kausa_id' => $this->kausa->id,
            'judul' => 'LPJ Final Penyaluran Bansos',
            'status' => 'menunggu_verifikasi',
            'total_digunakan' => '25000000.00',
        ]);

        // Verifikasi notifikasi terkirim ke Admin
        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $admin->id,
            'jenis' => 'lpj_diajukan',
        ]);
    }

    public function test_instansi_cannot_create_laporan_for_kausa_of_other_instansi(): void
    {
        $otherUser = User::factory()->create(['peran' => 'institution_user', 'role' => 'instansi']);
        $otherInstansi = Instansi::create([
            'user_id' => $otherUser->id,
            'nama' => 'Lembaga Lain',
            'jenis' => 'Yayasan',
        ]);

        $otherKausa = Kausa::create([
            'instansi_id' => $otherInstansi->id,
            'judul' => 'Kausa Pihak Lain',
            'slug' => 'kausa-pihak-lain',
            'target_dana' => 10000000,
            'deskripsi' => 'Deskripsi',
            'status' => 'disetujui',
        ]);

        $response = $this->actingAs($this->user)->post(route('instansi.laporan.store'), [
            'kausa_id' => $otherKausa->id,
            'judul' => 'LPJ Ilegal',
            'periode_mulai' => now()->toDateString(),
            'periode_selesai' => now()->toDateString(),
            'rincian' => [
                ['uraian' => 'Belanja palsu', 'nominal' => 1000000],
            ],
        ]);

        $response->assertStatus(403);
    }

    public function test_instansi_can_view_laporan_detail(): void
    {
        $laporan = LaporanDana::create([
            'kausa_id' => $this->kausa->id,
            'user_id' => $this->user->id,
            'judul' => 'LPJ Santunan Tahap 1',
            'periode_mulai' => now()->subDays(3),
            'periode_selesai' => now(),
            'total_digunakan' => 5000000,
            'status' => 'disetujui',
        ]);

        $laporan->rincian()->create([
            'uraian' => 'Sembako anak yatim',
            'nominal' => 5000000,
            'penerima_manfaat' => 'Anak yatim',
        ]);

        $response = $this->actingAs($this->user)->get(route('instansi.laporan.show', $laporan->id));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.instansi.laporan-detail');
        $response->assertSee('LPJ Santunan Tahap 1');
        $response->assertSee('Rp 5.000.000');
    }

    public function test_instansi_can_view_panduan_spj_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('instansi.panduan'));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.instansi.panduan');
        $response->assertSee('Pedoman Penyusunan SPJ & Kuitansi Sah');
        $response->assertSee('UU No. 10 Tahun 2020');
        $response->assertSee('Berita Acara Serah Terima');
    }
}
