<?php

namespace App\Http\Controllers\Petugas_lapangan;

use App\Http\Controllers\Controller;
use App\Models\Penggajian;

class GajiController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $riwayatGaji = Penggajian::with('petugas')
            ->where('petugas_id', $userId)
            ->orderBy('bulan_gaji', 'desc')
            ->get();

        return view('petugas_lapangan.gaji.index', compact('riwayatGaji'));
    }

    /**
     * Tampilkan slip gaji milik petugas yang sedang login.
     */
    public function slip($id)
    {
        $gaji = Penggajian::with('petugas')
            ->where('petugas_id', auth()->id())
            ->findOrFail($id);

        return view('petugas_lapangan.gaji.slip', compact('gaji'));
    }
}
