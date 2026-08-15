<?php

namespace App\Support;

use App\Models\JenisSampah;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StokSampah
{
    /**
     * Stok tersedia per jenis sampah: total kg setoran (masuk) dikurangi total kg terjual (keluar).
     * Stok dibatasi minimal 0 kg agar tidak pernah menampilkan angka negatif.
     */
    public static function perJenis(): Collection
    {
        $masuk = DB::table('setoran_sampahs')
            ->selectRaw('jenis_sampah_id, ROUND(SUM(berat_kg), 2) as kg')
            ->groupBy('jenis_sampah_id')
            ->pluck('kg', 'jenis_sampah_id');

        $keluar = DB::table('penjualan_sampah')
            ->selectRaw('jenis_sampah_id, ROUND(SUM(berat_kg), 2) as kg')
            ->groupBy('jenis_sampah_id')
            ->pluck('kg', 'jenis_sampah_id');

        return JenisSampah::with('kategoriSampah')->orderBy('nama_jenis')->get()->map(function ($jenis) use ($masuk, $keluar) {
            $masukKg = (float) ($masuk[$jenis->id] ?? 0);
            $keluarKg = (float) ($keluar[$jenis->id] ?? 0);

            return (object) [
                'jenis_id'  => $jenis->id,
                'jenis'     => $jenis->nama_jenis,
                'kategori'  => $jenis->kategoriSampah->nama_kategori ?? '-',
                'masuk_kg'  => $masukKg,
                'keluar_kg' => $keluarKg,
                'stok_kg'   => max(0, round($masukKg - $keluarKg, 2)),
            ];
        });
    }

    /** Total seluruh stok sampah yang tersedia (kg). */
    public static function total(): float
    {
        return round(self::perJenis()->sum('stok_kg'), 2);
    }

    /** Stok yang tersedia untuk satu jenis sampah (kg). */
    public static function stokTersedia(int $jenisId): float
    {
        $masuk = (float) DB::table('setoran_sampahs')->where('jenis_sampah_id', $jenisId)->sum('berat_kg');
        $keluar = (float) DB::table('penjualan_sampah')->where('jenis_sampah_id', $jenisId)->sum('berat_kg');

        return max(0, round($masuk - $keluar, 2));
    }
}