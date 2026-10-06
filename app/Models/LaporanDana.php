<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaporanDana extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'laporan_dana';
    protected $fillable = ['kausa_id', 'user_id', 'judul', 'ringkasan', 'periode_mulai', 'periode_selesai', 'total_digunakan', 'status', 'catatan_admin', 'dikirim_pada', 'disetujui_pada', 'dipublikasikan_pada'];

    protected function casts(): array
    {
        return ['periode_mulai' => 'date', 'periode_selesai' => 'date', 'total_digunakan' => 'decimal:2', 'dikirim_pada' => 'datetime', 'disetujui_pada' => 'datetime', 'dipublikasikan_pada' => 'datetime'];
    }

    public function kausa(): BelongsTo { return $this->belongsTo(Kausa::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function rincian(): HasMany { return $this->hasMany(RincianLaporanDana::class); }
}
