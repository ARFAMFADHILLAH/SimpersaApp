<?php

namespace App\Http\Controllers\Petugas_lapangan;

use App\Http\Controllers\Controller;
use App\Models\SetoranSampah;
use App\Models\PenjualanSampah;
use App\Models\Warga;
use App\Models\PenarikanSaldo;

class DashboardController extends Controller
{
    public function index()
    {
        // Transaksi hari ini
        $totalSetoranHariIni = SetoranSampah::whereDate('tanggal_setoran', today())->count();
        $totalKgHariIni = (float) SetoranSampah::whereDate('tanggal_setoran', today())->sum('berat_kg');
        $totalRupiahHariIni = (int) SetoranSampah::whereDate('tanggal_setoran', today())->sum('total_bayar');

        $totalPenjualanHariIni = (int) PenjualanSampah::whereDate('tanggal_penjualan', today())->sum('total_harga');
        $totalKgJualHariIni = (float) PenjualanSampah::whereDate('tanggal_penjualan', today())->sum('berat_kg');

        // Transaksi terbaru
        $transaksiTerbaru = SetoranSampah::with('warga.user', 'jenisSampah')
            ->latest('tanggal_setoran')
            ->take(6)
            ->get();

        // Status tabungan warga
        $totalNasabah = Warga::count();
        $saldoTerbesar = Warga::with('user')->orderByDesc('saldo_tabungan')->first();
        $penarikanMenunggu = PenarikanSaldo::where('status', 'Diproses')->count();

        return view('petugas_lapangan.dashboard', compact(
            'totalSetoranHariIni',
            'totalKgHariIni',
            'totalRupiahHariIni',
            'totalPenjualanHariIni',
            'totalKgJualHariIni',
            'transaksiTerbaru',
            'totalNasabah',
            'saldoTerbesar',
            'penarikanMenunggu'
        ));
    }
}