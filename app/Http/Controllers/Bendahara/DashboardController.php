<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Iuran;
use App\Models\GajiPetugas;
use App\Models\Pengeluaran;
use App\Models\Pelanggan;
use App\Models\Penggajian;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        // 1. KEUANGAN (Ringkasan Kas)
        $totalPemasukanBulanIni = Iuran::where('status_pembayaran', 'Lunas')
            ->whereMonth('tanggal_bayar', $bulanIni)
            ->whereYear('tanggal_bayar', $tahunIni)
            ->sum('jumlah_tagihan');

        $totalGajiBulanIni = Penggajian::whereMonth('created_at', $bulanIni)
          ->whereYear('created_at', $tahunIni)
          ->sum('total_gaji_bersih');

        $totalOperasionalBulanIni = Pengeluaran::whereMonth('tanggal_pengeluaran', $bulanIni)
            ->whereYear('tanggal_pengeluaran', $tahunIni)
            ->sum('jumlah_biaya');

        $totalPengeluaranBulanIni = $totalGajiBulanIni + $totalOperasionalBulanIni;
        $sisaLabaRugiBersih = $totalPemasukanBulanIni - $totalPengeluaranBulanIni;

        // 2. MONITORING TUNGGAKAN IURAN
        $totalPelangganMenunggak = Iuran::where('status_pembayaran', 'Belum Lunas')->count();
        $totalNominalTunggakan = Iuran::where('status_pembayaran', 'Belum Lunas')->sum('jumlah_tagihan');

        // Data 5 Transaksi Pembayaran Iuran Terbaru
        $transaksiTerbaru = Iuran::with('pelanggan.user')
            ->where('status_pembayaran', 'Lunas')
            ->latest('tanggal_bayar')
            ->take(5)
            ->get();

        // 3. PENGELUARAN OPERASIONAL TERBARU (BBM, Servis, dll)
        $operasionalTerbaru = Pengeluaran::latest('tanggal_pengeluaran')
            ->take(5)
            ->get();

        return view('bendahara.dashboard', compact(
            'totalPemasukanBulanIni',
            'totalGajiBulanIni',
            'totalOperasionalBulanIni',
            'totalPengeluaranBulanIni',
            'sisaLabaRugiBersih',
            'totalPelangganMenunggak',
            'totalNominalTunggakan',
            'transaksiTerbaru',
            'operasionalTerbaru'
        ));
    }
}