<?php

namespace App\Http\Controllers;

use App\Http\Requests\SimpanKausaRequest;
use App\Models\Kausa;
use App\Models\KategoriKausa;
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
            ->with(['kategori', 'instansi', 'donasi', 'dokumen', 'riwayatStatus'])
            ->firstOrFail();

        $totalDonasi = $kausa->donasi()
            ->where('status', 'berhasil')
            ->sum('nominal');

        $jumlahDonatur = $kausa->donasi()
            ->where('status', 'berhasil')
            ->distinct('user_id')
            ->count();

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

        foreach ($request->file('dokumen', []) as $file) {
            $kausa->dokumen()->create([
                'jenis_dokumen' => 'dokumen_pendukung',
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $file->store('dokumen/kausa'),
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
