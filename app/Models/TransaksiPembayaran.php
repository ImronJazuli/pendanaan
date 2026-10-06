<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiPembayaran extends Model
{
    use HasFactory;

    protected $table = 'transaksi_pembayaran';

    protected $fillable = [
        'donasi_id', 'penyedia', 'referensi_penyedia', 'token_pembayaran',
        'status', 'respons_penyedia', 'dibayar_pada', 'kedaluwarsa_pada',
    ];

    protected function casts(): array
    {
        return [
            'respons_penyedia' => 'array',
            'dibayar_pada' => 'datetime',
            'kedaluwarsa_pada' => 'datetime',
        ];
    }

    public function donasi(): BelongsTo
    {
        return $this->belongsTo(Donasi::class);
    }
}
