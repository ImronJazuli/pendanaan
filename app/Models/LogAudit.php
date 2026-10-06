<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogAudit extends Model
{
    use HasFactory;
    protected $table = 'log_audit';
    protected $fillable = ['user_id', 'aksi', 'entitas', 'entitas_id', 'data_lama', 'data_baru', 'alamat_ip', 'user_agent'];
    protected function casts(): array { return ['data_lama' => 'array', 'data_baru' => 'array']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
