<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Iuran;
use App\Models\PengeluaranOperasional;
use App\Models\GajiPetugas;
use Carbon\Carbon;

class BendaharaController extends Controller
{
    public function dashboard()
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        // 1. MODUL 5 & 8: KAS MASUK (Iuran Lunas)
        $totalPemasukanBulanIni = Iuran::where('status_pembayaran', 'Lunas')
            ->whereMonth('tanggal_bayar', $bulanIni)
            ->whereYear('tanggal_bayar', $tahunIni)
            ->sum('jumlah_tagihan');

        // 2. MODUL 6 & 7: KAS KELUAR (Penggajian + Biaya Operasional BBM/Servis)
        $totalGajiBulanIni = GajiPetugas::whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->sum('total_gaji');

        $totalOperasionalBulanIni = PengeluaranOperasional::whereMonth('tanggal_pengeluaran', $bulanIni)
            ->whereYear('tanggal_pengeluaran', $tahunIni)
            ->sum('biaya');

        $totalPengeluaranBulanIni = $totalGajiBulanIni + $totalOperasionalBulanIni;
        $sisaKasBersih = $totalPemasukanBulanIni - $totalPengeluaranBulanIni;

        // 3. MONITORING TUNGGAKAN IURAN
        $totalPelangganMenunggak = Iuran::where('status_pembayaran', 'Belum Lunas')->count();
        $totalNominalTunggakan = Iuran::where('status_pembayaran', 'Belum Lunas')->sum('jumlah_tagihan');

        // Transaksi Pemasukan & Pengeluaran Terbaru
        $transaksiIuranTerbaru = Iuran::with('pelanggan.user')
            ->where('status_pembayaran', 'Lunas')
            ->latest('tanggal_bayar')
            ->take(5)
            ->get();

        $operasionalTerbaru = PengeluaranOperasional::latest('tanggal_pengeluaran')
            ->take(5)
            ->get();

        return view('bendahara.dashboard', compact(
            'totalPemasukanBulanIni',
            'totalPengeluaranBulanIni',
            'totalGajiBulanIni',
            'totalOperasionalBulanIni',
            'sisaKasBersih',
            'totalPelangganMenunggak',
            'totalNominalTunggakan',
            'transaksiIuran',
            'operasionalTerbaru'
        ));
    }
}