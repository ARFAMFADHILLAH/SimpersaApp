<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use App\Models\User;
use App\Models\JenisSampah;
use App\Models\KategoriSampah;
use App\Models\SetoranSampah;
use App\Models\PenjualanSampah;
use App\Models\Penggajian;
use App\Models\PenarikanSaldo;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        // 1. Metrik Warga & Pengguna
        $totalWarga = Warga::count();
        $wargaAktif = Warga::whereHas('user', function ($q) {
            $q->where('status', 'aktif');
        })->count();
        $totalSaldoTabungan = (float) Warga::sum('saldo_tabungan');

        $totalPetugas = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['petugas', 'petugas_lapangan', 'bendahara', 'admin', 'administrator', 'administrasi']);
        })->count();

        // 2. Master Data Sampah
        $totalKategori = \App\Models\KategoriSampah::count();
        $totalJenis = JenisSampah::count();

        // 3. Transaksi POS — Pembelian dari Warga
        $totalSetoranBulanIni = SetoranSampah::whereMonth('tanggal_setoran', $bulanIni)
            ->whereYear('tanggal_setoran', $tahunIni)
            ->count();
        $totalKgBulanIni = (float) SetoranSampah::whereMonth('tanggal_setoran', $bulanIni)
            ->whereYear('tanggal_setoran', $tahunIni)
            ->sum('berat_kg');
        $totalBelanjaBulanIni = (int) SetoranSampah::whereMonth('tanggal_setoran', $bulanIni)
            ->whereYear('tanggal_setoran', $tahunIni)
            ->sum('total_bayar');

        // 4. Penjualan ke Pengepul
        $totalPenjualanBulanIni = (int) PenjualanSampah::whereMonth('tanggal_penjualan', $bulanIni)
            ->whereYear('tanggal_penjualan', $tahunIni)
            ->sum('total_harga');

        // 5. Penggajian
        $gajiPokok = (float) DB::table('pengaturan_gaji')->where('id', 1)->value('gaji_pokok');
        $penggajianBulanIni = (int) Penggajian::whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->where('status_pembayaran', 'Dibayar')
            ->sum('total_gaji_bersih');

        // 6. Transaksi Terbaru untuk Tabel Ringkas
        $setoranTerbaru = SetoranSampah::with('warga.user', 'jenisSampah')
            ->latest('tanggal_setoran')
            ->take(6)
            ->get();

        $penjualanTerbaru = PenjualanSampah::with('jenisSampah')
            ->latest('tanggal_penjualan')
            ->take(6)
            ->get();

        // 7. Grafik 12 Bulan: Belanja Warga vs Penjualan Pengepul
        $grafikBulan = [];
        $grafikBelanja = [];
        $grafikJual = [];

        for ($i = 11; $i >= 0; $i--) {
            $bulanDate = now()->subMonths($i);

            $grafikBulan[] = $bulanDate->format('M Y');
            $grafikBelanja[] = (int) SetoranSampah::whereYear('tanggal_setoran', $bulanDate->year)
                ->whereMonth('tanggal_setoran', $bulanDate->month)
                ->sum('total_bayar');
            $grafikJual[] = (int) PenjualanSampah::whereYear('tanggal_penjualan', $bulanDate->year)
                ->whereMonth('tanggal_penjualan', $bulanDate->month)
                ->sum('total_harga');
        }

        // 8. Penarikan Tabungan (status Diproses perlu dituntaskan bendahara)
        $penarikanMenunggu = (int) PenarikanSaldo::where('status', 'Diproses')->count();
        $nominalPenarikanMenunggu = (int) PenarikanSaldo::where('status', 'Diproses')->sum('nominal');

        return view('admin.dashboard', compact(
            'totalWarga', 'wargaAktif', 'totalSaldoTabungan', 'totalPetugas',
            'totalKategori', 'totalJenis',
            'totalSetoranBulanIni', 'totalKgBulanIni', 'totalBelanjaBulanIni',
            'totalPenjualanBulanIni',
            'gajiPokok', 'penggajianBulanIni',
            'setoranTerbaru', 'penjualanTerbaru',
            'grafikBulan', 'grafikBelanja', 'grafikJual',
            'penarikanMenunggu', 'nominalPenarikanMenunggu'
        ));
    }
}