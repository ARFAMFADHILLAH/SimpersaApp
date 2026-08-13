<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warga extends Model
{
    protected $table = "warga";

    protected $fillable = [
        'user_id',
        'no_warga',
        'no_hp',
        'alamat_lengkap',
        'saldo_tabungan',
        'urutan',
    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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