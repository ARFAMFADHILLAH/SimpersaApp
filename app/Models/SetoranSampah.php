<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetoranSampah extends Model
{
    protected $table = 'setoran_sampahs';

    protected $fillable = [
        'warga_id',
        'jenis_sampah_id',
        'berat_kg',
        'harga_per_kg',
        'total_bayar',
        'tanggal_setoran',
        'keterangan',
    ];

    protected $casts = [
        'berat_kg' => 'float',
        'harga_per_kg' => 'float',
        'total_bayar' => 'float',
        'tanggal_setoran' => 'date',
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }

    public function jenisSampah()
    {
        return $this->belongsTo(JenisSampah::class, 'jenis_sampah_id');
    }
}