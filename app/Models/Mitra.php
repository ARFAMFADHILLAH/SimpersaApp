<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    protected $table = 'mitras';

    protected $fillable = [
        'nama_mitra',
        'no_hp',
        'alamat_kontak',
    ];

    public static function current(): self
    {
        return self::first() ?? self::create(['nama_mitra' => 'KISUCI']);
    }

    public function setoranSampahs()
    {
        return $this->hasMany(SetoranSampah::class);
    }
}