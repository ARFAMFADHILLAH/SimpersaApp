<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSampah extends Model
{
    protected $table = 'jenis_sampah_dan_tarif';

    protected $fillable = [
        'kategori_sampah_id',
        'nama_jenis',
        'tarif_per_kg',
        'tarif_jual_per_kg',
        'tarif_bulanan_flat',
    ];

    public function kategoriSampah(): BelongsTo
    {
        return $this->belongsTo(KategoriSampah::class, 'kategori_sampah_id');
    }

    public function setoranSampah(): HasMany
    {
        return $this->hasMany(SetoranSampah::class, 'jenis_sampah_id');
    }
}