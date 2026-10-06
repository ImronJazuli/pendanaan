<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogTransparansi extends Model
{
    use HasFactory;
    protected $table = 'log_transparansi';
    protected $fillable = ['kausa_id', 'admin_id', 'laporan_dana_id', 'judul', 'deskripsi', 'nominal', 'path_bukti', 'dipublikasikan', 'dipublikasikan_pada'];
    protected function casts(): array { return ['nominal' => 'decimal:2', 'dipublikasikan' => 'boolean', 'dipublikasikan_pada' => 'datetime']; }
    public function kausa(): BelongsTo { return $this->belongsTo(Kausa::class); }
    public function admin(): BelongsTo { return $this->belongsTo(User::class, 'admin_id'); }
    public function laporanDana(): BelongsTo { return $this->belongsTo(LaporanDana::class); }
}
