<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warga extends Model
{
    protected $table = "warga";

    protected $fillable = [
        'user_id',
        'rute_id',
        'wilayah_pelayanan_id',
        'no_warga',
        'no_hp',
        'alamat_lengkap',
        'saldo_tabungan',
        'urutan',
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

    // Bank Sampah POS: riwayat pembelian (setoran) warga
    public function setoranSampah(): HasMany
    {
        return $this->hasMany(SetoranSampah::class, 'warga_id');
    }

    // Bank Sampah POS: penarikan saldo tabungan warga
    public function penarikanSaldo(): HasMany
    {
        return $this->hasMany(PenarikanSaldo::class, 'warga_id');
    }

    public function totalDisimpan(): float
    {
        return (float) $this->setoranSampah()->sum('total_bayar');
    }

    public function totalDitarik(): float
    {
        return (float) $this->penarikanSaldo()->where('status', 'Ditarik')->sum('nominal');
    }
}