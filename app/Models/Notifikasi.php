<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    use HasFactory;
    protected $table = 'notifikasi';
    protected $fillable = ['user_id', 'jenis', 'judul', 'isi', 'tautan', 'dibaca_pada'];
    protected function casts(): array { return ['dibaca_pada' => 'datetime']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
