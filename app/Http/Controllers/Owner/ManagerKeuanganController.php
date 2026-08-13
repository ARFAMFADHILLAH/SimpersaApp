<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ManagerKeuanganController extends Controller
{
    public function index()
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        // 1. Data Penerimaan Penjualan Sampah ke Pengepul (Pemasukan)
        $pemasukanPenjualan = DB::table('penjualan_sampah')
            ->leftJoin('kategori_sampah', 'penjualan_sampah.kategori_sampah_id', '=', 'kategori_sampah.id')
            ->leftJoin('jenis_sampah_dan_tarif', 'penjualan_sampah.jenis_sampah_id', '=', 'jenis_sampah_dan_tarif.id')
            ->select('penjualan_sampah.*', 'kategori_sampah.nama_kategori', 'jenis_sampah_dan_tarif.nama_jenis')
            ->latest('penjualan_sampah.created_at')
            ->paginate(10, ['*'], 'pemasukan_page');

        $totalPemasukanBulanIni = DB::table('penjualan_sampah')
            ->whereMonth('tanggal_penjualan', $bulanIni)
            ->whereYear('tanggal_penjualan', $tahunIni)
            ->sum('total_harga');

        // 2. Data Pengeluaran Operasional (Pengeluaran)
        $pengeluaranOperasional = DB::table('pengeluaran_operasional')
            ->latest('created_at')
            ->paginate(10, ['*'], 'pengeluaran_page');

        $totalPengeluaranBulanIni = DB::table('pengeluaran_operasional')
            ->whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->sum('jumlah_biaya');

        // 3. Data Penggajian Petugas
        $dataPenggajian = DB::table('penggajian')
            ->leftJoin('users', 'penggajian.petugas_id', '=', 'users.id')
            ->select('penggajian.*', 'users.name as nama_petugas')
            ->latest('penggajian.created_at')
            ->paginate(10, ['*'], 'gaji_page');

        $totalGajiBulanIni = DB::table('penggajian')
            ->whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->sum('total_gaji_bersih');

        // 4. Kalkulasi Saldo Arus Kas
        $saldoNetoBulanIni = $totalPemasukanBulanIni - ($totalPengeluaranBulanIni + $totalGajiBulanIni);

        return view('owner.keuangan.index', compact(
            'pemasukanPenjualan',
            'pengeluaranOperasional',
            'dataPenggajian',
            'totalPemasukanBulanIni',
            'totalPengeluaranBulanIni',
            'totalGajiBulanIni',
            'saldoNetoBulanIni'
        ));
    }
}