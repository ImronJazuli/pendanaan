<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RincianLaporanDana extends Model
{
    use HasFactory;
    protected $table = 'rincian_laporan_dana';
    protected $fillable = ['laporan_dana_id', 'uraian', 'nominal', 'tanggal_pengeluaran', 'penerima_manfaat', 'path_bukti', 'keterangan'];
    protected function casts(): array { return ['nominal' => 'decimal:2', 'tanggal_pengeluaran' => 'date']; }
    public function laporanDana(): BelongsTo { return $this->belongsTo(LaporanDana::class); }
}
