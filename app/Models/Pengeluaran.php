<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = 'pengeluaran_operasional';
    protected $fillable = [
        'tanggal_pengeluaran', 'kategori_biaya', 'jumlah_biaya', 'keterangan',
        'status_verifikasi', 'catatan_verifikasi', 'bukti_foto'
    ];
}
