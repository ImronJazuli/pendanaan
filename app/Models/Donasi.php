<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Donasi extends Model
{
    use HasFactory;

    protected $table = 'donasi';

    protected $fillable = [
        'kausa_id', 'user_id', 'pesanan_pembayaran', 'nama_donatur', 'anonim',
        'email_donatur', 'telepon_donatur', 'nominal', 'metode_pembayaran',
        'status', 'dibayar_pada',
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
}
