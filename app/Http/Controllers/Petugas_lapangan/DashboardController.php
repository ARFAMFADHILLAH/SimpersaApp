<?php

namespace App\Http\Controllers\Petugas_lapangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // 1. Ambil tugas pengangkutan hari ini untuk petugas yang login
        // Menghitung lokasi yang belum selesai diangkut hari ini
        $sisaTugas = DB::table('pengangkutan')
            ->where('petugas_id', $userId)
            ->whereDate('tanggal_tugas', today())
            ->where('status_tugas', '!=', 'selesai')
            ->count();

        // 2. Total lokasi yang sudah selesai diangkut hari ini
        $selesaiTugas = DB::table('pengangkutan')
            ->where('petugas_id', $userId)
            ->whereDate('tanggal_tugas', today())
            ->where('status_tugas', 'selesai')
            ->count();

        // 3. Riwayat laporan kendala yang pernah dikirim oleh petugas ini
        $totalLaporan = DB::table('laporan_kendalas') //
            ->where('petugas_id', $userId)
            ->count();

        return view('petugas_lapangan.dashboard', compact('sisaTugas', 'selesaiTugas', 'totalLaporan'));
    }
}
