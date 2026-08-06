<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\SetoranSampah;
use App\Models\Warga;

class SetoranController extends Controller
{
    public function index()
    {
        $warga = Warga::where('user_id', Auth::id())->firstOrFail();

        $riwayatSetoran = SetoranSampah::with('jenisSampah', 'mitra')
            ->where('warga_id', $warga->id)
            ->latest()
            ->paginate(15);

        $totalKg = SetoranSampah::where('warga_id', $warga->id)->sum('berat_kg');
        $totalDiterima = SetoranSampah::where('warga_id', $warga->id)->sum('total_bayar');

        return view('warga.bank_sampah.index', compact('warga', 'riwayatSetoran', 'totalKg', 'totalDiterima'));
    }
}