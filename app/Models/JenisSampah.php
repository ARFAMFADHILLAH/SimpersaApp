<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSampah extends Model
{
    protected $table = 'jenis_sampah_dan_tarif';

    protected $fillable = [
        'nama_jenis',
        'tarif_per_kg',
        'tarif_bulanan_flat',
    ];
}
