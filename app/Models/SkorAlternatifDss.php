<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkorAlternatifDss extends Model
{
    protected $table = 'skor_alternatif_dss';

    protected $fillable = [
        'tps_id',
        'kriteria_id',
        'nilai',
    ];

    public function tps()
    {
        return $this->belongsTo(Tps::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(KriteriaDss::class);
    }
}
