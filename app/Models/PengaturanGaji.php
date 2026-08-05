<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanGaji extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_gaji';

    protected $fillable = [
        'gaji_pokok',
        'insentif_per_hadir',
        'bonus_amount',
        'minimal_hadir_bonus',
        'potongan_alpha_per_hari',
        'potongan_izin_per_hari',
    ];

    public static function ambil(): self
    {
        return self::firstOrCreate([], [
            'gaji_pokok' => 1500000,
            'insentif_per_hadir' => 25000,
            'bonus_amount' => 200000,
            'minimal_hadir_bonus' => 20,
            'potongan_alpha_per_hari' => 50000,
            'potongan_izin_per_hari' => 20000,
        ]);
    }
}
