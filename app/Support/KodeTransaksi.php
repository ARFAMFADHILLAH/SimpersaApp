<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class KodeTransaksi
{
    /**
     * Buat kode transaksi berurutan per hari, mis. STR-20260814-0001.
     * Dipanggil di dalam transaksi DB agar nomor urut aman dari race condition.
     */
    public static function buat(string $prefix, string $table, string $columnTanggal, string $tanggal): string
    {
        $urutan = DB::table($table)
            ->whereDate($columnTanggal, $tanggal)
            ->whereNotNull('kode_transaksi')
            ->count() + 1;

        return sprintf('%s-%s-%04d', strtoupper($prefix), str_replace('-', '', $tanggal), $urutan);
    }
}
