<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengangkutan;
use App\Models\Rute;
use App\Models\Pelanggan;
use App\Models\User;
use Carbon\Carbon;

class OperasionalController extends Controller
{
    public function index()
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        $totalVolumeBulanIni = Pengangkutan::whereMonth('tanggal_tugas', $bulanIni)
            ->whereYear('tanggal_tugas', $tahunIni)
            ->sum('volume_m3');

        $totalPengangkutanBulanIni = Pengangkutan::whereMonth('tanggal_tugas', $bulanIni)
            ->whereYear('tanggal_tugas', $tahunIni)
            ->count();

        $pengangkutanHariIni = Pengangkutan::whereDate('tanggal_tugas', Carbon::today())
            ->with('pelanggan.user', 'armada')
            ->get();

        $rutes = Rute::withCount('pelanggan')->get();

        return view('administrasi.operasional.index', compact(
            'totalVolumeBulanIni',
            'totalPengangkutanBulanIni',
            'pengangkutanHariIni',
            'rutes'
        ));
    }

    public function rekapVolume()
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        $rekapHarian = Pengangkutan::selectRaw('DATE(tanggal_tugas) as tanggal, SUM(volume_m3) as total_volume, SUM(berat_kg) as total_berat, COUNT(*) as total_angkut')
            ->whereMonth('tanggal_tugas', $bulanIni)
            ->whereYear('tanggal_tugas', $tahunIni)
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->paginate(31);

        $rekapMingguan = Pengangkutan::selectRaw('YEARWEEK(tanggal_tugas) as minggu, YEAR(tanggal_tugas) as tahun, WEEK(tanggal_tugas) as nomor_minggu, MIN(tanggal_tugas) as tanggal_awal, MAX(tanggal_tugas) as tanggal_akhir, SUM(volume_m3) as total_volume, SUM(berat_kg) as total_berat, COUNT(*) as total_angkut')
            ->groupBy('minggu', 'tahun', 'nomor_minggu')
            ->orderBy('minggu', 'desc')
            ->take(12)
            ->get();

        $rekapBulanan = Pengangkutan::selectRaw('MONTH(tanggal_tugas) as bulan, YEAR(tanggal_tugas) as tahun, SUM(volume_m3) as total_volume, SUM(berat_kg) as total_berat, COUNT(*) as total_angkut')
            ->groupBy('bulan', 'tahun')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->take(12)
            ->get();

        return view('administrasi.operasional.rekap-volume', compact('rekapHarian', 'rekapMingguan', 'rekapBulanan'));
    }

    public function jadwalRute()
    {
        $rutes = Rute::with('pelanggan')->get();
        $petugasLapangan = User::whereIn('role_id', function ($q) {
            $q->select('id')->from('roles')->whereIn('name', ['petugas', 'petugas_lapangan', 'supir', 'pengangkut']);
        })->get();

        return view('administrasi.operasional.jadwal-rute', compact('rutes', 'petugasLapangan'));
    }

    public function tugaskanPetugas(Request $request)
    {
        $request->validate([
            'rute_id' => 'required|exists:rute,id',
            'petugas_id' => 'required|exists:users,id',
            'tanggal_tugas' => 'required|date',
            'armada_id' => 'required|exists:armada,id',
        ]);

        $pelangganList = Pelanggan::where('rute_id', $request->rute_id)->get();

        foreach ($pelangganList as $pelanggan) {
            Pengangkutan::create([
                'pelanggan_id' => $pelanggan->id,
                'armada_id' => $request->armada_id,
                'petugas_id' => $request->petugas_id,
                'tanggal_tugas' => $request->tanggal_tugas,
                'status_tugas' => 'Belum dikerjakan',
            ]);
        }

        return redirect()->route('administrasi.operasional.jadwal-rute')
            ->with('success', 'Penugasan berhasil untuk ' . $pelangganList->count() . ' pelanggan.');
    }
}
