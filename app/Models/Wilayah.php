<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;

    protected $table = 'wilayah_pelayanan';

    protected $fillable = [
        'nama_wilayah',
        'cakupan_area',
    ];

    // Relasi ke Pelanggan (Satu wilayah punya banyak pelanggan)
    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class, 'wilayah_pelayanan_id');
    }
}

