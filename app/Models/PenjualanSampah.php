<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenjualanSampah extends Model
{
    use HasFactory;

    protected $table = 'penjualan_sampah';

    protected $fillable = [
        'kode_transaksi',
        'kategori_sampah_id',
        'jenis_sampah_id',
        'nama_pengepul',
        'berat_kg',
        'harga_jual_per_kg',
        'total_harga',
        'tanggal_penjualan',
        'catatan',
    ];

    public function kategoriSampah(): BelongsTo
    {
        return $this->belongsTo(KategoriSampah::class, 'kategori_sampah_id');
    }

    public function jenisSampah(): BelongsTo
    {
        return $this->belongsTo(JenisSampah::class, 'jenis_sampah_id');
    }
}