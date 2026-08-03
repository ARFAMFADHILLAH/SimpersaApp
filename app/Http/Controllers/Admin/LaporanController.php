<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Iuran;
use App\Models\Penggajian;
use App\Models\Pengeluaran;
use App\Models\Pengangkutan;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $mulai = $request->tanggal_mulai;
        $selesai = $request->tanggal_selesai;

        // Tarik data berdasarkan rentang waktu
        $iuran = Iuran::with('pelanggan.user')
            ->whereBetween('tanggal_bayar', [$mulai, $selesai])
            ->where('status_pembayaran', 'Lunas')
            ->get();

        $gaji = Penggajian::with('petugas')
            ->whereBetween('created_at', [$mulai, $selesai])
            ->get();

        $operasional = Pengeluaran::with('armada')
            ->whereBetween('tanggal_pengeluaran', [$mulai, $selesai])
            ->get();

        $pengangkutan = Pengangkutan::with(['pelanggan.user', 'petugas'])
            ->whereBetween('tanggal_tugas', [$mulai, $selesai])
            ->get();

        // Hitung Total Kalkulasi
        $totalPendapatan = $iuran->sum('jumlah_tagihan');
        $totalGaji = $gaji->sum('total_gaji_bersih');
        $totalOperasional = $operasional->sum('jumlah_biaya');
        $labaBersih = $totalPendapatan - ($totalGaji + $totalOperasional);

        return view('admin.laporan.cetak', compact(
            'iuran', 'gaji', 'operasional', 'pengangkutan',
            'totalPendapatan', 'totalGaji', 'totalOperasional', 'labaBersih',
            'mulai', 'selesai'
        ));
    }
}
