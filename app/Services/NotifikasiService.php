<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\User;

class NotifikasiService
{
    /**
     * Kirim notifikasi in-app ke pengguna.
     */
    public static function kirim(User|int $user, string $jenis, string $judul, string $isi, ?string $tautan = null): void
    {
        $userId = $user instanceof User ? $user->id : (int) $user;

        if (! $userId) {
            return;
        }

        Notifikasi::create([
            'user_id' => $userId,
            'jenis' => $jenis,
            'judul' => $judul,
            'isi' => $isi,
            'tautan' => $tautan,
            'dibaca_pada' => null,
        ]);
    }
}
