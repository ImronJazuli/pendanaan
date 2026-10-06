<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instansi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'instansi';

    protected $fillable = [
        'user_id',
        'jenis',
        'nama',
        'nomor_registrasi',
        'status_verifikasi',
        'alamat',
        'nomor_telepon',
        'terverifikasi_pada',
    ];

    protected function casts(): array
    {
        return [
            'terverifikasi_pada' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(DokumenInstansi::class);
    }

    public function kausa(): HasMany
    {
        return $this->hasMany(Kausa::class);
    }
}
