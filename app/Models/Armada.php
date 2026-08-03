<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Armada extends Model
{
    // Ini untuk memberi tahu Laravel agar memakai tabel database 'armada' yang sudah Anda punya
    protected $table = 'armada';

    protected $fillable = [
        'nama_kendaraan',
        'nomor_plat',
        'jenis_kendaraan',
        'kapasitas_m3',
        'status_kondisi'
    ];
}
