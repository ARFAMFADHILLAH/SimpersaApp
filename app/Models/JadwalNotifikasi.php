<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalNotifikasi extends Model
{
    use HasFactory;

    protected $table = 'jadwal_notifikasi';
    protected $fillable = ['template_id', 'nama_jadwal', 'pemicu', 'waktu_kirim', 'hari_ke', 'is_aktif'];

    public function template()
    {
        return $this->belongsTo(TemplateNotifikasi::class, 'template_id');
    }
}
