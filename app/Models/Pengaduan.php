<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan';

    protected $fillable = [
        'warga_id',
        'tipe_kendala',
        'catatan_lokasi',
        'foto_bukti',
        'petugas_id',
        'status_respon', // Belum Dikerjakan, Sedang Dikerjakan, Selesai
        'catatan_petugas'
    ];

    /**
     * Relasi balik ke Warga
     */
    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
