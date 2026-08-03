<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriteriaDss extends Model
{
    use HasFactory;

    protected $table = 'kriteria_dss';
    protected $fillable = ['kode_kriteria', 'nama_kriteria', 'bobot', 'jenis'];
}

