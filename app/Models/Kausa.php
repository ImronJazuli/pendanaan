<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    /**
     * Satu-satunya method resmi untuk menambah dana terkumpul saat donasi sukses.
     * Menggunakan operasi increment tingkat database secara atomik.
     */
    public function tambahDanaTerkumpul(float $nominal): void
    {
        $this->increment('dana_terkumpul', $nominal);
        $this->refresh();
    }

    /**
     * Rekonsiliasi nilai dana_terkumpul dari tabel donasi.
     */
    public function recalculateDanaTerkumpul(): float
    {
        $total = (float) $this->donasi()->where('status', Donasi::STATUS_SUCCESS)->sum('nominal');
        $this->dana_terkumpul = $total;
        $this->save();

        return $total;
    }

    /**
     * Accessor total donasi terkumpul (membaca dari kolom tersimpan dana_terkumpul).
     */
    public function getTotalTerkumpulAttribute(): float
    {
        return (float) $this->dana_terkumpul;
    }

    /**
     * Accessor persentase progress donasi (maksimal 100% untuk progress bar).
     */
    public function getPersentaseProgressAttribute(): int
    {
        $target = (float) $this->target_dana;
        if ($target <= 0) {
            return 0;
        }

        return (int) min(100, round(($this->total_terkumpul / $target) * 100));
    }

    /**
     * Accessor jumlah donatur (dihitung per transaksi donasi berstatus success).
     */
    public function getJumlahDonaturAttribute(): int
    {
        return $this->donasi()->where('status', Donasi::STATUS_SUCCESS)->count();
    }
}
