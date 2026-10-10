<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Donasi extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_SUCCESS = 'success';

    public const STATUS_FAILED = 'failed';

    public const STATUS_EXPIRED = 'expired';

    public const STATUS_MENUNGGU_VERIFIKASI_MANUAL = 'menunggu_verifikasi_manual';

    public const STATUS_DITOLAK_MANUAL = 'ditolak_manual';

    protected $table = 'donasi';

    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    protected $fillable = [
        'kausa_id', 'user_id', 'pesanan_pembayaran', 'nama_donatur', 'anonim', 'doa_dukungan',
        'email_donatur', 'telepon_donatur', 'nominal', 'metode_pembayaran',
        'status', 'path_bukti_manual', 'catatan_verifikasi_manual', 'dibayar_pada',
    ];

    protected function casts(): array
    {
        return [
            'anonim' => 'boolean',
            'nominal' => 'decimal:2',
            'dibayar_pada' => 'datetime',
        ];
    }

    public function kausa(): BelongsTo
    {
        return $this->belongsTo(Kausa::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaksiPembayaran(): HasOne
    {
        return $this->hasOne(TransaksiPembayaran::class);
    }

    public function transaksi(): HasOne
    {
        return $this->transaksiPembayaran();
    }

    public function scopeSuccess($query)
    {
        return $query->whereIn('status', [self::STATUS_SUCCESS, 'berhasil']);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
}
