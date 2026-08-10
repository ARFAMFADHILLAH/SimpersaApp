<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use App\Models\SetoranSampah;
use App\Models\PenjualanSampah;
use App\Models\PenarikanSaldo;
use App\Models\Penggajian;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        // Metrik utama
        $totalNasabah = Warga::count();
        $totalSaldoTabungan = (float) Warga::sum('saldo_tabungan');

        $totalKgBulanIni = (float) SetoranSampah::whereMonth('tanggal_setoran', $bulanIni)
            ->whereYear('tanggal_setoran', $tahunIni)
            ->sum('berat_kg');

        $totalMasukBulanIni = (int) PenjualanSampah::whereMonth('tanggal_penjualan', $bulanIni)
            ->whereYear('tanggal_penjualan', $tahunIni)
            ->sum('total_harga');

        $totalBelanjaBulanIni = (int) SetoranSampah::whereMonth('tanggal_setoran', $bulanIni)
            ->whereYear('tanggal_setoran', $tahunIni)
            ->sum('total_bayar');

        $totalTarikBulanIni = (int) PenarikanSaldo::where('status', 'Ditarik')
            ->whereMonth('tanggal_penarikan', $bulanIni)
            ->whereYear('tanggal_penarikan', $tahunIni)
            ->sum('nominal');

        $totalGajiBulanIni = (int) Penggajian::where('status_pembayaran', 'Dibayar')
            ->whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->sum('total_gaji_bersih');

        $totalKeluarBulanIni = $totalBelanjaBulanIni + $totalTarikBulanIni + $totalGajiBulanIni;
        $labaBulanIni = $totalMasukBulanIni - $totalKeluarBulanIni;

        // Penjualan terbaru
        $penjualanTerbaru = PenjualanSampah::with('jenisSampah')
            ->latest('tanggal_penjualan')
            ->take(6)
            ->get();

        $setoranTerbaru = SetoranSampah::with('warga.user', 'jenisSampah')
            ->latest('tanggal_setoran')
            ->take(6)
            ->get();

        // Grafik 12 bulan
        $grafikBulan = [];
        $grafikMasuk = [];
        $grafikKeluar = [];

        for ($i = 11; $i >= 0; $i--) {
            $bulanDate = now()->subMonths($i);

            $grafikBulan[] = $bulanDate->format('M Y');
            $grafikMasuk[] = (int) PenjualanSampah::whereYear('tanggal_penjualan', $bulanDate->year)
                ->whereMonth('tanggal_penjualan', $bulanDate->month)
                ->sum('total_harga');
            $grafikKeluar[] = (int) SetoranSampah::whereYear('tanggal_setoran', $bulanDate->year)
                ->whereMonth('tanggal_setoran', $bulanDate->month)
                ->sum('total_bayar')
                + (int) PenarikanSaldo::where('status', 'Ditarik')
                    ->whereYear('tanggal_penarikan', $bulanDate->year)
                    ->whereMonth('tanggal_penarikan', $bulanDate->month)
                    ->sum('nominal')
                + (int) Penggajian::where('status_pembayaran', 'Dibayar')
                    ->whereYear('created_at', $bulanDate->year)
                    ->whereMonth('created_at', $bulanDate->month)
                    ->sum('total_gaji_bersih');
        }

        return view('owner.dashboard', compact(
            'totalNasabah',
            'totalSaldoTabungan',
            'totalKgBulanIni',
            'totalMasukBulanIni',
            'totalKeluarBulanIni',
            'totalBelanjaBulanIni',
            'totalTarikBulanIni',
            'totalGajiBulanIni',
            'labaBulanIni',
            'penjualanTerbaru',
            'setoranTerbaru',
            'grafikBulan',
            'grafikMasuk',
            'grafikKeluar'
        ));
    }
}