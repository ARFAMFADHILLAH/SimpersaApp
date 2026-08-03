<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    protected $table = 'rute';
    protected $fillable = ['nama_rute', 'hari_angkut', 'titik_koordinat_pusat', 'keterangan'];

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class);
    }
}
