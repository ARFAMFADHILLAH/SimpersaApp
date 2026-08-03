<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan';

    protected $fillable = [
        'pelanggan_id',
        'tipe_kendala',
        'catatan_lokasi',
        'foto_bukti',
        'petugas_id',
        'status_respon', // Belum Dikerjakan, Sedang Dikerjakan, Selesai
        'catatan_petugas'
    ];

    /**
     * Relasi balik ke Pelanggan
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }
}
