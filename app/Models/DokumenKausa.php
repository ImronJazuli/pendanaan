<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenKausa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dokumen_kausa';

    protected $fillable = [
        'kausa_id', 'jenis_dokumen', 'nama_file', 'path_file',
        'mime_type', 'ukuran_file', 'status_verifikasi', 'catatan_admin',
        'diverifikasi_pada',
    ];

    protected function casts(): array
    {
        return ['diverifikasi_pada' => 'datetime'];
    }

    public function kausa(): BelongsTo
    {
        return $this->belongsTo(Kausa::class);
    }
}
