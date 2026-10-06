<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatStatusKausa extends Model
{
    use HasFactory;

    protected $table = 'riwayat_status_kausa';

    protected $fillable = [
        'kausa_id', 'user_id', 'status_sebelumnya', 'status_baru', 'catatan',
    ];

    public function kausa(): BelongsTo
    {
        return $this->belongsTo(Kausa::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
