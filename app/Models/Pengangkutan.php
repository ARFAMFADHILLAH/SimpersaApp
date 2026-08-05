<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengangkutan extends Model
{
    protected $table = 'pengangkutan';

    protected $fillable = [
        'warga_id',
        'armada_id',
        'jenis_sampah_id',
        'petugas_id',
        'tanggal_tugas',
        'volume_m3',
        'berat_kg',
        'status_tugas',
        'catatan'
    ];

    public function warga() { return $this->belongsTo(Warga::class); }
    public function armada() { return $this->belongsTo(Armada::class); }
    public function jenisSampah() { return $this->belongsTo(JenisSampah::class, 'jenis_sampah_id'); }
    public function petugas() { return $this->belongsTo(User::class, 'petugas_id'); }
}
