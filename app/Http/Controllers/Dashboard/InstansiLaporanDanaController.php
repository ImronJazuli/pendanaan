<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SimpanLaporanDanaRequest;
use App\Models\Kausa;
use App\Models\LaporanDana;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InstansiLaporanDanaController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $instansi = $user->instansi;
        abort_unless($instansi, 403, 'Profil instansi belum tersedia.');

        // Kausa milik instansi ini
        $kausaList = Kausa::where('instansi_id', $instansi->id)
            ->whereIn('status', ['disetujui', 'selesai'])
            ->withCount('laporanDana')
            ->get();

        // Riwayat Laporan Dana milik instansi ini
        $query = LaporanDana::whereHas('kausa', function ($q) use ($instansi) {
            $q->where('instansi_id', $instansi->id);
        })->with(['kausa', 'rincian'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('kausa_id')) {
            $query->where('kausa_id', $request->input('kausa_id'));
        }

        $laporan = $query->paginate(10);

        // Ringkasan KPI
        $allLaporan = LaporanDana::whereHas('kausa', function ($q) use ($instansi) {
            $q->where('instansi_id', $instansi->id);
        })->get();

        $metrics = [
            'total_laporan' => $allLaporan->count(),
            'menunggu_verifikasi' => $allLaporan->where('status', 'menunggu_verifikasi')->count(),
            'disetujui' => $allLaporan->whereIn('status', ['disetujui', 'dipublikasikan'])->count(),
            'perlu_revisi' => $allLaporan->where('status', 'perlu_revisi')->count(),
            'total_realisasi' => (float) $allLaporan->whereIn('status', ['disetujui', 'dipublikasikan'])->sum('total_digunakan'),
        ];

        return view('dashboard.instansi.laporan', compact('laporan', 'kausaList', 'metrics', 'instansi'));
    }

    public function create(Request $request): View
    {
        $user = auth()->user();
        $instansi = $user->instansi;
        abort_unless($instansi, 403, 'Profil instansi belum tersedia.');

        $kausaList = Kausa::where('instansi_id', $instansi->id)
            ->whereIn('status', ['disetujui', 'selesai'])
            ->get();

        $selectedKausaId = $request->input('kausa_id');

        return view('dashboard.instansi.laporan-create', compact('kausaList', 'selectedKausaId', 'instansi'));
    }

    public function store(SimpanLaporanDanaRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $instansi = $user->instansi;
        abort_unless($instansi, 403, 'Profil instansi belum tersedia.');

        $isSubmit = $request->input('action') === 'submit';
        $status = $isSubmit ? 'menunggu_verifikasi' : 'draf';

        $laporan = DB::transaction(function () use ($request, $user, $instansi, $status, $isSubmit) {
            $rincianData = $request->input('rincian', []);
            $totalDigunakan = 0;

            foreach ($rincianData as $item) {
                $totalDigunakan += (float) ($item['nominal'] ?? 0);
            }

            $laporan = LaporanDana::create([
                'kausa_id' => $request->input('kausa_id'),
                'user_id' => $user->id,
                'judul' => $request->input('judul'),
                'ringkasan' => $request->input('ringkasan'),
                'periode_mulai' => $request->input('periode_mulai'),
                'periode_selesai' => $request->input('periode_selesai'),
                'total_digunakan' => $totalDigunakan,
                'status' => $status,
                'dikirim_pada' => $isSubmit ? now() : null,
            ]);

            // Simpan rincian pengeluaran
            if ($request->has('rincian')) {
                foreach ($request->input('rincian') as $index => $item) {
                    $pathBukti = null;
                    if ($request->hasFile("rincian.{$index}.bukti")) {
                        $pathBukti = $request->file("rincian.{$index}.bukti")->store('bukti/laporan_dana');
                    }

                    $laporan->rincian()->create([
                        'uraian' => $item['uraian'],
                        'nominal' => $item['nominal'],
                        'tanggal_pengeluaran' => $item['tanggal_pengeluaran'] ?? null,
                        'penerima_manfaat' => $item['penerima_manfaat'] ?? null,
                        'path_bukti' => $pathBukti,
                        'keterangan' => $item['keterangan'] ?? null,
                    ]);
                }
            }

            // Kirim notifikasi ke Admin jika disubmit
            if ($isSubmit) {
                $kausa = Kausa::find($request->input('kausa_id'));
                $adminUsers = User::where('peran', 'admin')->orWhere('role', 'admin')->get();
                foreach ($adminUsers as $admin) {
                    Notifikasi::create([
                        'user_id' => $admin->id,
                        'jenis' => 'lpj_diajukan',
                        'judul' => 'Laporan Pertanggungjawaban (LPJ) Baru',
                        'isi' => "Instansi {$instansi->nama} telah mengajukan LPJ untuk kausa '{$kausa?->judul}' sebesar Rp ".number_format($totalDigunakan, 0, ',', '.').'.',
                        'tautan' => route('admin.laporan'),
                        'dibaca_pada' => null,
                    ]);
                }
            }

            return $laporan;
        });

        $msg = $isSubmit
            ? 'Laporan pertanggungjawaban (LPJ) berhasil dikirim untuk verifikasi Admin Pemkab.'
            : 'Draf laporan penggunaan dana berhasil disimpan.';

        return redirect()->route('instansi.laporan.show', $laporan->id)->with('status', $msg);
    }

    public function show(LaporanDana $laporan): View
    {
        $user = auth()->user();
        $instansi = $user->instansi;
        abort_unless($instansi && $laporan->kausa->instansi_id === $instansi->id, 403, 'Akses ditolak.');

        $laporan->load(['kausa', 'rincian', 'user']);

        return view('dashboard.instansi.laporan-detail', compact('laporan', 'instansi'));
    }
}
