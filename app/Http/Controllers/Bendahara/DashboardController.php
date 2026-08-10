<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\SetoranSampah;
use App\Models\PenjualanSampah;
use App\Models\PenarikanSaldo;
use App\Models\Penggajian;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        // Pemasukan kas = penjualan ke pengepul
        $totalPemasukanBulanIni = (int) PenjualanSampah::whereMonth('tanggal_penjualan', $bulanIni)
            ->whereYear('tanggal_penjualan', $tahunIni)
            ->sum('total_harga');

        // Pengeluaran kas bulan ini: belanja warga + penarikan ditarik + gaji dibayar
        $totalBelanjaBulanIni = (int) SetoranSampah::whereMonth('tanggal_setoran', $bulanIni)
            ->whereYear('tanggal_setoran', $tahunIni)
            ->sum('total_bayar');

        $totalPenarikanBulanIni = (int) PenarikanSaldo::where('status', 'Ditarik')
            ->whereMonth('tanggal_penarikan', $bulanIni)
            ->whereYear('tanggal_penarikan', $tahunIni)
            ->sum('nominal');

        $totalGajiBulanIni = (int) Penggajian::where('status_pembayaran', 'Dibayar')
            ->whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->sum('total_gaji_bersih');

        $totalPengeluaranBulanIni = $totalBelanjaBulanIni + $totalPenarikanBulanIni + $totalGajiBulanIni;
        $sisaKasBulanIni = $totalPemasukanBulanIni - $totalPengeluaranBulanIni;

        // Penarikan yang belum dikonfirmasi
        $penarikanMenunggu = PenarikanSaldo::with('warga.user')
            ->where('status', 'Diproses')
            ->orderByDesc('tanggal_penarikan')
            ->get();

        // Penggajian belum dibayar bulan ini
        $gajiBelumBayar = Penggajian::with('petugas')
            ->where('status_pembayaran', 'Belum Dibayar')
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        // Transaksi terbaru (gabungan belanja & jual)
        $transaksiTerbaru = collect();

        SetoranSampah::with('warga.user')->latest('tanggal_setoran')->take(5)->get()->each(function ($t) use (&$transaksiTerbaru) {
            $transaksiTerbaru->push((object) [
                'tanggal' => $t->tanggal_setoran,
                'keterangan' => 'Belanja: ' . ($t->warga->user->name ?? 'Warga'),
                'tipe' => 'Keluar',
                'jumlah' => $t->total_bayar,
            ]);
        });

        PenjualanSampah::with('jenisSampah')->latest('tanggal_penjualan')->take(5)->get()->each(function ($t) use (&$transaksiTerbaru) {
            $transaksiTerbaru->push((object) [
                'tanggal' => $t->tanggal_penjualan,
                'keterangan' => 'Penjualan: ' . ($t->jenisSampah->nama_jenis ?? '-') . ' → ' . ($t->nama_pengepul ?? 'pengepul'),
                'tipe' => 'Masuk',
                'jumlah' => $t->total_harga,
            ]);
        });

        $transaksiTerbaru = $transaksiTerbaru->sortByDesc('tanggal')->take(8)->values();

        // Grafik 6 bulan pemasukan vs pengeluaran
        $grafikBulan = [];
        $grafikMasuk = [];
        $grafikKeluar = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulanDate = now()->subMonths($i);

            $masuk = (int) PenjualanSampah::whereYear('tanggal_penjualan', $bulanDate->year)
                ->whereMonth('tanggal_penjualan', $bulanDate->month)
                ->sum('total_harga');

            $keluar = (int) SetoranSampah::whereYear('tanggal_setoran', $bulanDate->year)
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

            $grafikBulan[] = $bulanDate->format('M Y');
            $grafikMasuk[] = $masuk;
            $grafikKeluar[] = $keluar;
        }

        return view('bendahara.dashboard', compact(
            'totalPemasukanBulanIni',
            'totalBelanjaBulanIni',
            'totalPenarikanBulanIni',
            'totalGajiBulanIni',
            'totalPengeluaranBulanIni',
            'sisaKasBulanIni',
            'penarikanMenunggu',
            'gajiBelumBayar',
            'transaksiTerbaru',
            'grafikBulan',
            'grafikMasuk',
            'grafikKeluar'
        ));
    }
}