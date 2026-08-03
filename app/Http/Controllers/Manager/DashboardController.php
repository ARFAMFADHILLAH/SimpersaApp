<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // =========================================================
        // 1. MODUL 2: METRIK PELANGGAN & STATUS (TABEL: pelanggan & users)
        // =========================================================
        $totalPelanggan = Pelanggan::count();

        // Pelanggan Aktif (Di mana user terhubung & status user = 'aktif')
        $pelangganAktif = Pelanggan::whereHas('user', function ($q) {
            $q->where('status', 'aktif');
        })->count();

        // Pelanggan Menunggak = Pelanggan yang memiliki tagihan iuran belum bayar
        $pelangganMenunggak = 0;
        if (\Schema::hasTable('iuran')) {
            $pelangganMenunggak = DB::table('iuran')
                ->where('status_pembayaran', 'Belum Bayar')
                ->distinct()
                ->count('pelanggan_id');
        }

        // =========================================================
        // 2. MODUL 5: KEUANGAN - PENDAPATAN IURAN (TABEL: iuran)
        // =========================================================
        $totalPendapatanIuran = 0;
        if (\Schema::hasTable('iuran')) {
            $totalPendapatanIuran = DB::table('iuran')
                ->whereYear('tanggal_bayar', $now->year)
                ->whereMonth('tanggal_bayar', $now->month)
                ->where('status_pembayaran', 'Lunas')
                ->sum('jumlah_tagihan');
        }

        // =========================================================
        // 3. MODUL 7: BIAYA OPERASIONAL (TABEL: pengeluaran_operasional)
        // =========================================================
        $totalBiayaOperasional = 0;
        if (\Schema::hasTable('pengeluaran_operasional')) {
            $totalBiayaOperasional = DB::table('pengeluaran_operasional')
                ->whereYear('tanggal_pengeluaran', $now->year)
                ->whereMonth('tanggal_pengeluaran', $now->month)
                ->sum('jumlah_biaya');
        }

        // =========================================================
        // 4. MODUL 6: TOTAL GAJI PETUGAS (TABEL: penggajian)
        // =========================================================
        $totalGajiPetugas = 0;
        if (\Schema::hasTable('penggajian')) {
            $totalGajiPetugas = DB::table('penggajian')
                ->where('bulan_gaji', $now->format('Y-m'))
                ->sum('total_gaji_bersih');
        }

        // =========================================================
        // 5. MODUL 4: PENGELOLAAN VOLUME SAMPAH (TABEL: pengangkutan)
        // =========================================================
        $volumeSampahHariIni = 0;
        $volumeSampahBulanIni = 0;

        if (\Schema::hasTable('pengangkutan')) {
            // Hitung Hari Ini
            $volumeSampahHariIni = DB::table('pengangkutan')
                ->whereDate('tanggal_tugas', Carbon::today())
                ->sum('volume_m3');

            // Hitung Bulan Ini
            $volumeSampahBulanIni = DB::table('pengangkutan')
                ->whereYear('tanggal_tugas', $now->year)
                ->whereMonth('tanggal_tugas', $now->month)
                ->sum('volume_m3');
        }

        // =========================================================
        // 6. MODUL 1 & 3: KONDISI ARMADA & RUTE (TABEL: armada & rute)
        // =========================================================
        $kendaraanAktif = 0;
        $kendaraanRusak = 0;

        if (\Schema::hasTable('armada')) {
            $kendaraanAktif = DB::table('armada')
                ->where('status_kondisi', 'aktif')
                ->count();

            $kendaraanRusak = DB::table('armada')
                ->where('status_kondisi', 'rusak')
                ->count();
        }

        // Monitoring Rute Tugas Hari Ini (TABEL: rute)
        $totalRuteHariIni = 0;

        if (\Schema::hasTable('rute')) {
            $totalRuteHariIni = DB::table('rute')->count();
        }

        // =========================================================
        // 6b. PRODUKTIVITAS PETUGAS (MODUL 9)
        // Tugas selesai vs total ditugaskan bulan ini, per petugas
        // =========================================================
        $produktivitasPetugas = collect();
        if (\Schema::hasTable('pengangkutan') && \Schema::hasTable('users')) {
            $produktivitasPetugas = DB::table('pengangkutan')
                ->join('users', 'pengangkutan.petugas_id', '=', 'users.id')
                ->select(
                    'users.id as petugas_id',
                    'users.name as nama_petugas',
                    DB::raw('COUNT(*) as total_tugas'),
                    DB::raw("SUM(CASE WHEN status_tugas = 'Selesai' THEN 1 ELSE 0 END) as tugas_selesai")
                )
                ->whereYear('tanggal_tugas', $now->year)
                ->whereMonth('tanggal_tugas', $now->month)
                ->groupBy('users.id', 'users.name')
                ->orderByDesc('tugas_selesai')
                ->get()
                ->map(function ($item) {
                    $item->persentase = $item->total_tugas > 0
                        ? round(($item->tugas_selesai / $item->total_tugas) * 100, 1)
                        : 0;
                    return $item;
                });
        }

        // Absensi / Petugas Hadir Hari Ini
        $petugasHadir = 0;
        if (\Schema::hasTable('absensi_petugas')) {
            $petugasHadir = DB::table('absensi_petugas')
                ->whereDate('tanggal', Carbon::today())
                ->where('status', 'hadir')
                ->distinct()
                ->count('user_id');
        }

        // =========================================================
        // 7. MODUL 11: PENGADUAN / KENDALA (TABEL: pengaduan)
        // =========================================================
        $pengaduanBaru = 0;
        if (\Schema::hasTable('pengaduan')) {
            $pengaduanBaru = DB::table('pengaduan')
                ->where('status_respon', 'Belum Dikerjakan')
                ->count();
        }

        // =========================================================
        // 8. DATA GRAFIK 12 BULAN (MODUL 9)
        // Grafik pembayaran, volume sampah, biaya operasional
        // =========================================================
        $grafikPembayaran = [];
        $grafikVolume = [];
        $grafikBiaya = [];

        for ($i = 11; $i >= 0; $i--) {
            $bulanDate = Carbon::now()->subMonths($i);
            $bulanLabel = $bulanDate->format('M Y');

            $pembayaran = 0;
            if (\Schema::hasTable('iuran')) {
                $pembayaran = DB::table('iuran')
                    ->whereYear('tanggal_bayar', $bulanDate->year)
                    ->whereMonth('tanggal_bayar', $bulanDate->month)
                    ->where('status_pembayaran', 'Lunas')
                    ->sum('jumlah_tagihan');
            }

            $volume = 0;
            if (\Schema::hasTable('pengangkutan')) {
                $volume = DB::table('pengangkutan')
                    ->whereYear('tanggal_tugas', $bulanDate->year)
                    ->whereMonth('tanggal_tugas', $bulanDate->month)
                    ->sum('volume_m3');
            }

            $biaya = 0;
            if (\Schema::hasTable('pengeluaran_operasional')) {
                $biaya = DB::table('pengeluaran_operasional')
                    ->whereYear('tanggal_pengeluaran', $bulanDate->year)
                    ->whereMonth('tanggal_pengeluaran', $bulanDate->month)
                    ->sum('jumlah_biaya');
            }

            $grafikPembayaran[] = ['bulan' => $bulanLabel, 'total' => (float) $pembayaran];
            $grafikVolume[] = ['bulan' => $bulanLabel, 'total' => (float) $volume];
            $grafikBiaya[] = ['bulan' => $bulanLabel, 'total' => (float) $biaya];
        }

        // =========================================================
        // KIRIM DATA KE VIEW DASHBOARD MANAGER
        // =========================================================
        return view('manager.dashboard', compact(
            'totalPelanggan',
            'pelangganAktif',
            'pelangganMenunggak',
            'totalPendapatanIuran',
            'totalBiayaOperasional',
            'totalGajiPetugas',
            'volumeSampahHariIni',
            'volumeSampahBulanIni',
            'kendaraanAktif',
            'kendaraanRusak',
            'totalRuteHariIni',
            'petugasHadir',
            'pengaduanBaru',
            'produktivitasPetugas',
            'grafikPembayaran',
            'grafikVolume',
            'grafikBiaya'
        ));
    }
}
