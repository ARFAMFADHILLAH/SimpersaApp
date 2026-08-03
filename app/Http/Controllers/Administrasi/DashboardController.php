<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\Pelanggan;
use App\Models\Armada;
use App\Models\Pengeluaran;
use App\Models\Iuran;
use App\Models\Pengangkutan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        $totalPelanggan = Pelanggan::count();
        $totalArmadaAktif = Armada::where('status_kondisi', 'aktif')->count();
        $pengaduanBaru = Pengaduan::where('status_respon', 'Belum Dikerjakan')->count();
        $totalPengangkutanBulanIni = Pengangkutan::whereMonth('tanggal_tugas', $bulanIni)
            ->whereYear('tanggal_tugas', $tahunIni)
            ->count();

        $totalVolumeBulanIni = Pengangkutan::whereMonth('tanggal_tugas', $bulanIni)
            ->whereYear('tanggal_tugas', $tahunIni)
            ->sum('volume_m3');

        $pengaduanTerbaru = Pengaduan::with('pelanggan.user')
            ->latest()
            ->take(5)
            ->get();

        $pengangkutanTerbaru = Pengangkutan::with('pelanggan.user', 'armada')
            ->latest('tanggal_tugas')
            ->take(5)
            ->get();

        return view('administrasi.dashboard', compact(
            'totalPelanggan',
            'totalArmadaAktif',
            'pengaduanBaru',
            'totalPengangkutanBulanIni',
            'totalVolumeBulanIni',
            'pengaduanTerbaru',
            'pengangkutanTerbaru'
        ));
    }
}
