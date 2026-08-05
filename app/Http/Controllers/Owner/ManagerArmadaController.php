<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagerArmadaController extends Controller
{
    public function index()
    {
        // 1. Ambil Semua Data Armada Kendaraan
        $dataArmada = DB::table('armada')->get();

        // Ringkasan Status Armada
        $armadaAktif = DB::table('armada')->whereIn('status_kondisi', ['aktif', 'baik', 'siap', 'Beroperasi'])->count();
        $armadaRusak = DB::table('armada')->whereIn('status_kondisi', ['rusak', 'servis', 'perbaikan', 'Mogok'])->count();

        // 2. Ambil Monitoring Rute Pengangkatan Sampah
        $dataRute = DB::table('rute')
        ->select('rute.*')
        ->orderBy('rute.created_at', 'desc')
        ->get();

        return view('owner.armada.index', compact(
            'dataArmada',
            'armadaAktif',
            'armadaRusak',
            'dataRute'
        ));
    }
}