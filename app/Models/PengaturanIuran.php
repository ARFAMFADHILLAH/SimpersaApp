<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PengaturanIuran extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_iuran';

    protected $fillable = [
        'tarif_dasar_bulanan',
        'persentase_denda_per_bulan',
        'nominal_denda_flat',
        'tgl_jatuh_tempo',
    ];

    /**
     * Hitung denda keterlambatan berdasarkan jatuh tempo tagihan.
     *
     * Menggunakan nilai terbesar antara denda persentase (jumlah_tagihan
     * x persentase_denda_per_bulan/100 x bulan terlambat) dengan denda flat,
     * agar kedua pengaturan tarif dapat dipakai.
     */
    public static function hitungDenda(string $bulanTagihan, float $jumlahTagihan, ?string $tanggalBayar = null): int
    {
        $pengaturan = self::first() ?: new self([
            'tarif_dasar_bulanan' => 20000,
            'persentase_denda_per_bulan' => 5,
            'nominal_denda_flat' => 5000,
            'tgl_jatuh_tempo' => 10,
        ]);

        $tglJatuhTempo = Carbon::parse($bulanTagihan . '-' . sprintf('%02d', $pengaturan->tgl_jatuh_tempo ?? 10));

        if ($tanggalBayar) {
            $tanggalBayar = Carbon::parse($tanggalBayar);
        } else {
            $tanggalBayar = Carbon::now();
        }

        if (!$tanggalBayar->gt($tglJatuhTempo)) {
            return 0;
        }

        $selisihBulan = max(1, (int) ceil($tanggalBayar->diffInMonths($tglJatuhTempo)));

        $dendaPersen = (int) round($jumlahTagihan * ($pengaturan->persentase_denda_per_bulan ?? 0) / 100 * $selisihBulan);
        $dendaFlat = (int) (($pengaturan->nominal_denda_flat ?? 0) * $selisihBulan);

        return max($dendaPersen, $dendaFlat);
    }
}