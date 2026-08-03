<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = "pelanggan";

    protected $fillable = [
        'user_id',
        'rute_id',
        'wilayah_pelayanan_id',
        'no_pelanggan',
        'no_hp',
        'alamat_lengkap',
        'latitude',
        'longitude',
    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke tabel rute
    public function rute()
    {
        return $this->belongsTo(Rute::class, 'rute_id');
    }

    // Relasi ke tabel wilayah_pelayanan
    public function wilayahPelayanan()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_pelayanan_id');
    }

    public function wilayah()
    {
        return $this->wilayahPelayanan();
    }
}