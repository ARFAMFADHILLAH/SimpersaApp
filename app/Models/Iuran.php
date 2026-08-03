<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iuran extends Model
{
    protected $table = 'iuran';

    protected $fillable = [
        'pelanggan_id',
        'bulan_tagihan',
        'jumlah_tagihan',
        'denda',
        'status_pembayaran',
        'tanggal_bayar',
        'metode_pembayaran'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}
