<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfilInstansiRequest;
use App\Models\Instansi;
use App\Models\Kausa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstansiDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $instansi = auth()->user()->instansi;
        abort_unless($instansi, 403, 'Profil instansi belum tersedia.');

        $query = Kausa::where('instansi_id', $instansi->id);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('judul', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->input('sort') === 'tercanggih') {
            $query->orderBy('updated_at', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $kausa = $query->paginate(10);

        $statusCounts = [
            'total' => Kausa::where('instansi_id', $instansi->id)->count(),
            'draf' => Kausa::where('instansi_id', $instansi->id)->where('status', 'draf')->count(),
            'menunggu_verifikasi' => Kausa::where('instansi_id', $instansi->id)->where('status', 'menunggu_verifikasi')->count(),
            'disetujui' => Kausa::where('instansi_id', $instansi->id)->where('status', 'disetujui')->count(),
            'ditolak' => Kausa::where('instansi_id', $instansi->id)->where('status', 'ditolak')->count(),
        ];

        return view('dashboard.instansi.index', compact('kausa', 'statusCounts'));
    }

    public function detail(Kausa $kausa): View
    {
        $instansi = auth()->user()->instansi;
        abort_unless($instansi && $kausa->instansi_id === $instansi->id, 403);

        $kausa->load(['kategori', 'dokumen', 'riwayatStatus', 'laporanDana.dokumen', 'donasi']);

        return view('dashboard.instansi.detail', compact('kausa'));
    }

    public function profil(): View
    {
        $user = auth()->user();

        // Auto-provision record instansi jika belum ada
        $instansi = $user->instansi;
        if (! $instansi) {
            $instansi = Instansi::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nama' => $user->name,
                    'jenis' => 'Yayasan',
                    'status_verifikasi' => 'belum_diverifikasi',
                    'alamat' => $user->address,
                    'nomor_telepon' => $user->phone_number,
                ]
            );
        }

        $instansi->load('dokumen');

        return view('dashboard.instansi.profil', compact('instansi', 'user'));
    }

    public function updateProfil(UpdateProfilInstansiRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $instansi = $user->instansi;

        if (! $instansi) {
            $instansi = Instansi::create([
                'user_id' => $user->id,
                'nama' => $request->input('nama') ?? $request->input('nama_lembaga') ?? $user->name,
                'jenis' => $request->input('jenis') ?? $request->input('jenis_badan_hukum') ?? 'Yayasan',
                'status_verifikasi' => 'belum_diverifikasi',
            ]);
        }

        $instansi->update([
            'nama' => $request->input('nama') ?? $request->input('nama_lembaga') ?? $instansi->nama,
            'jenis' => $request->input('jenis') ?? $request->input('jenis_badan_hukum') ?? $instansi->jenis,
            'nomor_registrasi' => $request->input('nomor_registrasi') ?? $request->input('no_sk_kemenkumham') ?? $instansi->nomor_registrasi,
            'alamat' => $request->input('alamat') ?? $request->input('alamat_kantor') ?? $instansi->alamat,
            'nomor_telepon' => $request->input('nomor_telepon') ?? $request->input('wa_pj') ?? $instansi->nomor_telepon,
        ]);

        $userUpdates = [];
        if ($request->filled('nama_pj')) {
            $userUpdates['name'] = $request->input('nama_pj');
        }
        if ($request->filled('nik_pj')) {
            $userUpdates['nik'] = $request->input('nik_pj');
        }
        if ($request->filled('wa_pj') || $request->filled('nomor_telepon')) {
            $userUpdates['phone_number'] = $request->input('wa_pj') ?? $request->input('nomor_telepon');
        }
        if ($request->filled('npwp_lembaga')) {
            $userUpdates['npwp'] = $request->input('npwp_lembaga');
        }
        if ($request->filled('alamat') || $request->filled('alamat_kantor')) {
            $userUpdates['address'] = $request->input('alamat') ?? $request->input('alamat_kantor');
        }
        if (! empty($userUpdates)) {
            $user->update($userUpdates);
        }

        return redirect()->route('instansi.profil')->with('success', 'Profil instansi berhasil diperbarui.');
    }
}
