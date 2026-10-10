<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'email_verified_at', 'password', 'peran', 'status', 'role', 'nik', 'phone_number', 'sso_id', 'google_id', 'avatar', 'npwp', 'address'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function instansi(): HasOne
    {
        return $this->hasOne(Instansi::class);
    }

    public function donasi(): HasMany
    {
        return $this->hasMany(Donasi::class);
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Periksa apakah user adalah donatur.
     */
    public function isDonatur(): bool
    {
        return $this->role === 'donatur';
    }

    /**
     * Periksa apakah user adalah instansi.
     */
    public function isInstansi(): bool
    {
        return $this->role === 'instansi';
    }

    /**
     * Periksa apakah user adalah admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Relasi ke institution.
     */
    public function institution(): HasOne
    {
        return $this->hasOne(Instansi::class);
    }
}
