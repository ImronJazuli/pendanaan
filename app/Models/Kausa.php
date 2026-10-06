<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kausa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kausa';

    protected $fillable = [
        'instansi_id', 'kategori_kausa_id', 'judul', 'slug', 'ringkasan',
        'deskripsi', 'lokasi', 'target_dana', 'dana_terkumpul',
        'tanggal_mulai', 'tanggal_berakhir', 'status', 'catatan_admin',
        'dipublikasikan_pada',
    ];

    protected function casts(): array
    {
        return [
            'target_dana' => 'decimal:2',
            'dana_terkumpul' => 'decimal:2',
            'tanggal_mulai' => 'date',
            'tanggal_berakhir' => 'date',
            'dipublikasikan_pada' => 'datetime',
        ];
    }

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriKausa::class, 'kategori_kausa_id');
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(DokumenKausa::class);
    }

    public function riwayatStatus(): HasMany
    {
        return $this->hasMany(RiwayatStatusKausa::class);
    }

    public function donasi(): HasMany
    {
        return $this->hasMany(Donasi::class);
    }

    public function laporanDana(): HasMany
    {
        return $this->hasMany(LaporanDana::class);
    }

    public function logTransparansi(): HasMany
    {
        return $this->hasMany(LogTransparansi::class);
    }
}
