<?php

namespace App\Http\Controllers\Petugas_lapangan;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPetugas;
use App\Models\Penggajian;
use Illuminate\Http\Request;

class GajiController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $riwayatAbsensi = AbsensiPetugas::where('user_id', $userId)
            ->orderBy('tanggal', 'desc')
            ->take(30)
            ->get();

        $riwayatGaji = Penggajian::with('petugas')
            ->where('petugas_id', $userId)
            ->orderBy('bulan_gaji', 'desc')
            ->get();

        return view('petugas_lapangan.gaji.index', compact('riwayatAbsensi', 'riwayatGaji'));
    }
}
