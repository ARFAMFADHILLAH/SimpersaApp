<?php

namespace App\Http\Controllers\Manager;

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

        // 1. Data Penerimaan Iuran (Pemasukan)
        $pemasukanIuran = DB::table('iuran')
            ->leftJoin('pelanggan', 'iuran.pelanggan_id', '=', 'pelanggan.id')
            ->leftJoin('users', 'pelanggan.user_id', '=', 'users.id')
            ->select('iuran.*', 'users.name as nama_pelanggan', 'pelanggan.no_pelanggan')
            ->latest('iuran.created_at')
            ->paginate(10, ['*'], 'pemasukan_page');

        $totalPemasukanBulanIni = DB::table('iuran')
            ->whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->whereIn('status_pembayaran', ['lunas', 'paid', 'Selesai', '1'])
            ->sum('jumlah_tagihan');

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

        return view('manager.keuangan.index', compact(
            'pemasukanIuran',
            'pengeluaranOperasional',
            'dataPenggajian',
            'totalPemasukanBulanIni',
            'totalPengeluaranBulanIni',
            'totalGajiBulanIni',
            'saldoNetoBulanIni'
        ));
    }
}