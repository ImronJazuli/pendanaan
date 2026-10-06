<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriKausa extends Model
{
    use HasFactory;

    protected $table = 'kategori_kausa';

    protected $fillable = ['nama', 'slug', 'deskripsi', 'aktif'];

    protected function casts(): array
    {
        return ['aktif' => 'boolean'];
    }

    public function kausa(): HasMany
    {
        return $this->hasMany(Kausa::class);
    }
}
