<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iuran extends Model
{
    protected $table = 'iuran';

    protected $fillable = [
        'warga_id',
        'bulan_tagihan',
        'jumlah_tagihan',
        'denda',
        'status_pembayaran',
        'tanggal_bayar',
        'metode_pembayaran',
        'bukti_pembayaran'
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }
}
