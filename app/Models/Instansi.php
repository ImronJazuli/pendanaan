<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    /**
     * Alias untuk relasi dokumen instansi.
     */
    public function dokumenInstansi(): HasMany
    {
        return $this->dokumen();
    }

    public function kausa(): HasMany
    {
        return $this->hasMany(Kausa::class);
    }

    /**
     * Accessor untuk nama penanggung jawab (PIC).
     */
    public function getNamaPjAttribute(): ?string
    {
        return $this->user?->name;
    }

    /**
     * Accessor untuk NIK penanggung jawab (PIC).
     */
    public function getNikPjAttribute(): ?string
    {
        return $this->user?->nik;
    }

    /**
     * Accessor untuk kontak WhatsApp penanggung jawab.
     */
    public function getWaPjAttribute(): ?string
    {
        return $this->nomor_telepon ?? $this->user?->phone_number;
    }

    /**
     * Accessor untuk NPWP lembaga.
     */
    public function getNpwpLembagaAttribute(): ?string
    {
        return $this->user?->npwp;
    }
}
