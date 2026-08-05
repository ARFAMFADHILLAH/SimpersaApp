<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ManagerDSSController extends Controller
{
    public function index()
    {
        // 1. Ambil Kriteria DSS
        $kriteria = DB::table('kriteria_dss')->get();

        // 2. Ambil Skor Alternatif DSS
        $querySkor = DB::table('skor_alternatif_dss');
        if (Schema::hasColumn('skor_alternatif_dss', 'wilayah_pelayanan_id')) {
            $querySkor->leftJoin('wilayah_pelayanan', 'skor_alternatif_dss.wilayah_pelayanan_id', '=', 'wilayah_pelayanan.id')
                      ->select('skor_alternatif_dss.*', 'wilayah_pelayanan.nama_wilayah');
        } elseif (Schema::hasColumn('skor_alternatif_dss', 'alternatif_id')) {
            $querySkor->leftJoin('alternatif_dss', 'skor_alternatif_dss.alternatif_id', '=', 'alternatif_dss.id')
                      ->leftJoin('wilayah_pelayanan', 'alternatif_dss.wilayah_pelayanan_id', '=', 'wilayah_pelayanan.id')
                      ->select('skor_alternatif_dss.*', 'wilayah_pelayanan.nama_wilayah', 'alternatif_dss.nama_alternatif');
        } else {
            $querySkor->select('skor_alternatif_dss.*');
        }
        $skorAlternatif = $querySkor->get();

        // 3. Rekap Evaluasi Wilayah & Jumlah Warga
        $evaluasiWilayah = DB::table('wilayah_pelayanan')
            ->leftJoin('warga', 'wilayah_pelayanan.id', '=', 'warga.wilayah_pelayanan_id')
            ->select(
                'wilayah_pelayanan.id',
                'wilayah_pelayanan.nama_wilayah',
                DB::raw('COUNT(DISTINCT warga.id) as total_warga'),
                DB::raw('0 as total_kendala')
            )
            ->groupBy('wilayah_pelayanan.id', 'wilayah_pelayanan.nama_wilayah')
            ->get();

        return view('owner.dss.index', compact('kriteria', 'skorAlternatif', 'evaluasiWilayah'));
    }
}