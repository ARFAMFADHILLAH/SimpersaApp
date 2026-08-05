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

    // Relasi ke Warga (Satu wilayah punya banyak warga)
    public function warga()
    {
        return $this->hasMany(Warga::class, 'wilayah_pelayanan_id');
    }
}

