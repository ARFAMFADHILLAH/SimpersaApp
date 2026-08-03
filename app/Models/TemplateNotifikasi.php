<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateNotifikasi extends Model
{
    use HasFactory;

    protected $table = 'template_notifikasi';
    protected $fillable = ['kode_template', 'judul_template', 'saluran', 'subjek', 'isi_pesan', 'is_aktif'];

    public function jadwal()
    {
        return $this->hasMany(JadwalNotifikasi::class, 'template_id');
    }
}
