<?php

namespace App\Policies;

use App\Models\Kausa;
use App\Models\User;

class KausaPolicy
{
    /**
     * Tentukan apakah pengguna dapat melihat kausa.
     */
    public function view(?User $user, Kausa $kausa): bool
    {
        if ($kausa->status === 'disetujui') {
            return true;
        }

        if (! $user) {
            return false;
        }

        if ($user->isAdmin() || in_array($user->peran, ['admin'], true)) {
            return true;
        }

        return (bool) ($user->instansi && $user->instansi->id === $kausa->instansi_id);
    }

    /**
     * Tentukan apakah pengguna dapat memperbarui kausa.
     */
    public function update(User $user, Kausa $kausa): bool
    {
        if (! $user->instansi || $user->instansi->id !== $kausa->instansi_id) {
            return false;
        }

        return in_array($kausa->status, ['draf', 'perlu_diperbaiki'], true);
    }

    /**
     * Tentukan apakah pengguna dapat menghapus kausa.
     */
    public function delete(User $user, Kausa $kausa): bool
    {
        if (! $user->instansi || $user->instansi->id !== $kausa->instansi_id) {
            return false;
        }

        return $kausa->status === 'draf';
    }
}
