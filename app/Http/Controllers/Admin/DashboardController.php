<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warga;
use App\Models\Iuran;
use App\Models\Penggajian;
use App\Models\Pengeluaran;
use App\Models\Pengangkutan;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Metrik Warga
        $totalWarga = Warga::count();
        $wargaAktif = Warga::whereHas('user', function($q) {
            $q->where('status', 'aktif');
        })->count();
        $wargaMenunggak = Iuran::where('status_pembayaran', 'Belum Bayar')->distinct()->count('warga_id');

        // 2. Keuangan Sederhana (Modul 8)
        $totalPendapatan = Iuran::where('status_pembayaran', 'Lunas')->sum('jumlah_tagihan');
        $totalGaji = Penggajian::sum('total_gaji_bersih');
        
        // Menyesuaikan dengan nama tabel pengeluaran_operasional di database Anda
        $totalOperasional = DB::table('pengeluaran_operasional')->sum('jumlah_biaya'); 
        $totalPengeluaran = $totalGaji + $totalOperasional;
        $labaRugiBersih = $totalPendapatan - $totalPengeluaran;

        // 3. Operasional Sampah & Armada
        $totalVolumeSampah = Pengangkutan::sum('volume_m3');
        $totalBeratSampah = Pengangkutan::sum('berat_kg');
        $armadaAktif = DB::table('armada')->where('status_kondisi', 'aktif')->count();
        $armadaRusak = DB::table('armada')->where('status_kondisi', 'rusak')->count();

        // 4. Data Warga Menunggak Detail
        $daftarMenunggak = DB::table('iuran')
            ->where('status_pembayaran', 'Belum Bayar')
            ->select('warga_id', DB::raw('COUNT(*) as jumlah_blm_bayar'), DB::raw('SUM(jumlah_tagihan) as total_tunggakan'))
            ->groupBy('warga_id')
            ->orderByDesc('total_tunggakan')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $item->warga = Warga::with('user')->find($item->warga_id);
                return $item;
            });

        // 5. Laporan Kendala Lapangan
        $kendalaTerbaru = DB::table('laporan_kendalas')
            ->join('users', 'laporan_kendalas.petugas_id', '=', 'users.id')
            ->select('laporan_kendalas.*', 'users.name as nama_petugas')
            ->orderBy('laporan_kendalas.created_at', 'desc')
            ->limit(5)
            ->get();

        $kendalaHariIni = DB::table('laporan_kendalas')
            ->whereDate('created_at', today())
            ->count();

        // 5. Pengaduan Warga
        $pengaduanTerbaru = Pengaduan::with('warga.user')
            ->latest()
            ->take(5)
            ->get();

        // 6. Data Grafik 12 Bulan (Modul 9)
        $grafikPembayaran = [];
        $grafikVolume = [];
        $grafikBiaya = [];

        for ($i = 11; $i >= 0; $i--) {
            $bulanDate = now()->subMonths($i);
            $bulanLabel = $bulanDate->format('M Y');

            $grafikPembayaran[] = [
                'bulan' => $bulanLabel,
                'total' => (float) DB::table('iuran')
                    ->whereYear('tanggal_bayar', $bulanDate->year)
                    ->whereMonth('tanggal_bayar', $bulanDate->month)
                    ->where('status_pembayaran', 'Lunas')
                    ->sum('jumlah_tagihan'),
            ];

            $grafikVolume[] = [
                'bulan' => $bulanLabel,
                'total' => (float) Pengangkutan::whereYear('tanggal_tugas', $bulanDate->year)
                    ->whereMonth('tanggal_tugas', $bulanDate->month)
                    ->sum('volume_m3'),
            ];

            $grafikBiaya[] = [
                'bulan' => $bulanLabel,
                'total' => (float) DB::table('pengeluaran_operasional')
                    ->whereYear('tanggal_pengeluaran', $bulanDate->year)
                    ->whereMonth('tanggal_pengeluaran', $bulanDate->month)
                    ->sum('jumlah_biaya'),
            ];
        }

        // 7. Metrik Operasional Harian (dari panel Administrasi)
        $bulanIni = now()->month;
        $tahunIni = now()->year;
        $totalArmadaAktif = DB::table('armada')->where('status_kondisi', 'aktif')->count();
        $pengaduanBaru = Pengaduan::where('status_respon', 'Belum Dikerjakan')->count();
        $totalPengangkutanBulanIni = Pengangkutan::whereMonth('tanggal_tugas', $bulanIni)
            ->whereYear('tanggal_tugas', $tahunIni)
            ->count();
        $totalVolumeBulanIni = Pengangkutan::whereMonth('tanggal_tugas', $bulanIni)
            ->whereYear('tanggal_tugas', $tahunIni)
            ->sum('volume_m3');
        $pengangkutanTerbaru = Pengangkutan::with('warga.user', 'armada')
            ->latest('tanggal_tugas')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalWarga', 'wargaAktif', 'wargaMenunggak', 'daftarMenunggak',
            'totalPendapatan', 'totalGaji', 'totalOperasional', 'labaRugiBersih',
            'totalVolumeSampah', 'totalBeratSampah', 'armadaAktif', 'armadaRusak',
            'kendalaTerbaru', 'kendalaHariIni', 'pengaduanTerbaru',
            'grafikPembayaran', 'grafikVolume', 'grafikBiaya',
            'totalArmadaAktif', 'pengaduanBaru', 'totalPengangkutanBulanIni', 'totalVolumeBulanIni', 'pengangkutanTerbaru'
        ));
    }
}