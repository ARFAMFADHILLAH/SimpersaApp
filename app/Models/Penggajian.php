<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    protected $table = 'penggajian';
    protected $fillable = ['petugas_id', 'bulan_gaji', 'gaji_pokok', 'insentif_lembur', 'potongan', 'total_gaji_bersih', 'status_pembayaran'];

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
