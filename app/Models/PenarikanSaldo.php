<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenarikanSaldo extends Model
{
    use HasFactory;

    protected $table = 'penarikan_saldo';

    protected $fillable = [
        'warga_id',
        'nominal',
        'tanggal_penarikan',
        'status',
        'catatan',
    ];

    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }
}