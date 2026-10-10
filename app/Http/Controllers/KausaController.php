<?php

namespace App\Http\Controllers;

use App\Http\Requests\SimpanKausaRequest;
use App\Models\Donasi;
use App\Models\KategoriKausa;
use App\Models\Kausa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KausaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Kausa::where('status', 'disetujui')
            ->with(['kategori', 'donasi']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('ringkasan', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_kausa_id', $request->input('kategori'));
        }

        if ($request->input('sort') === 'populer') {
            $query->withCount('donasi')
                ->orderBy('donasi_count', 'desc');
        } elseif ($request->input('sort') === 'target_tinggi') {
            $query->orderBy('target_dana', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $kausa = $query->paginate(12);
        $kategoris = KategoriKausa::where('aktif', true)->get();

        return view('kausa.index', compact('kausa', 'kategoris'));
    }

    public function show(string $slug): View
    {
        $kausa = Kausa::where('slug', $slug)
            ->where('status', 'disetujui')
            ->with([
                'kategori',
                'instansi',
                'donasi' => fn ($q) => $q->whereIn('status', [Donasi::STATUS_SUCCESS, 'berhasil'])->latest(),
                'dokumen',
                'riwayatStatus',
                'logTransparansi' => fn ($q) => $q->where('dipublikasikan', true)->latest(),
            ])
            ->firstOrFail();

        $totalDonasi = $kausa->total_terkumpul;
        $jumlahDonatur = $kausa->donasi->count();

        return view('kausa.show', [
            'kausa' => $kausa,
            'totalDonasi' => $totalDonasi,
            'jumlahDonatur' => $jumlahDonatur,
        ]);
    }

    public function create(): View
    {
        $kategori = KategoriKausa::where('aktif', true)->get();

        return view('kausa.create', compact('kategori'));
    }

    public function store(SimpanKausaRequest $request): RedirectResponse
    {
        $instansi = $request->user()->instansi;
        abort_unless($instansi, 403, 'Profil instansi belum tersedia.');

        $data = $request->validated();
        $data['instansi_id'] = $instansi->id;
        $data['slug'] = Str::slug($data['judul']).'-'.Str::lower(Str::random(6));
        $data['status'] = $request->input('action') === 'submit' ? 'menunggu_verifikasi' : 'draf';

        $kausa = Kausa::create($data);

        foreach ($request->file('foto_kausa', []) as $foto) {
            $kausa->dokumen()->create([
                'jenis_dokumen' => 'foto_galeri',
                'nama_file' => $foto->getClientOriginalName(),
                'path_file' => $foto->store('dokumen/kausa/galeri', 'public'),
                'mime_type' => $foto->getMimeType(),
                'ukuran_file' => $foto->getSize(),
            ]);
        }

        foreach ($request->file('dokumen', []) as $file) {
            $isImage = str_starts_with($file->getMimeType(), 'image/');
            $kausa->dokumen()->create([
                'jenis_dokumen' => $isImage ? 'foto_galeri' : 'dokumen_pendukung',
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $file->store('dokumen/kausa', 'public'),
                'mime_type' => $file->getMimeType(),
                'ukuran_file' => $file->getSize(),
            ]);
        }

        $kausa->riwayatStatus()->create([
            'user_id' => $request->user()->id,
            'status_baru' => $kausa->status,
            'catatan' => $data['status'] === 'menunggu_verifikasi'
                ? 'Pengajuan dikirim oleh instansi untuk verifikasi Admin.'
                : 'Pengajuan disimpan sebagai draf oleh instansi.',
        ]);

        $msg = $data['status'] === 'menunggu_verifikasi'
            ? 'Pengajuan kausa berhasil dikirim untuk verifikasi Admin.'
            : 'Pengajuan kausa disimpan sebagai draf.';

        return redirect()->route('kausa.create')->with('status', $msg);
    }
}
