<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotifikasiController extends Controller
{
    /**
     * Tampilkan daftar notifikasi milik pengguna yang sedang login.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $notifikasis = Notifikasi::where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        $unreadCount = Notifikasi::where('user_id', $user->id)
            ->whereNull('dibaca_pada')
            ->count();

        return view('notifikasi.index', compact('notifikasis', 'unreadCount'));
    }

    /**
     * Tandai satu notifikasi sebagai telah dibaca.
     */
    public function markRead(Request $request, Notifikasi $notifikasi): RedirectResponse
    {
        abort_unless($notifikasi->user_id === $request->user()->id, 403, 'Anda tidak memiliki hak akses untuk notifikasi ini.');

        if (is_null($notifikasi->dibaca_pada)) {
            $notifikasi->update([
                'dibaca_pada' => now(),
            ]);
        }

        if ($request->filled('redirect_to')) {
            return redirect($request->input('redirect_to'));
        }

        if ($notifikasi->tautan) {
            return redirect($notifikasi->tautan);
        }

        return back()->with('status', 'Notifikasi berhasil ditandai sebagai dibaca.');
    }

    /**
     * Tandai semua notifikasi pengguna sebagai telah dibaca.
     */
    public function markAllRead(Request $request): RedirectResponse
    {
        Notifikasi::where('user_id', $request->user()->id)
            ->whereNull('dibaca_pada')
            ->update([
                'dibaca_pada' => now(),
            ]);

        return back()->with('status', 'Semua notifikasi berhasil ditandai sebagai dibaca.');
    }
}
