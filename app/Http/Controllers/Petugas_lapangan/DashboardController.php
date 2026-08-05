<?php

namespace App\Http\Controllers\Petugas_lapangan;

use App\Http\Controllers\Controller;
use App\Models\Pengangkutan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // 1. Tugas pengangkutan milik petugas yang login (hari ini, fallback ke penugasan terakhir)
        $tugas = Pengangkutan::with(['warga.user', 'warga.rute', 'armada'])
            ->where('petugas_id', $userId)
            ->whereDate('tanggal_tugas', today())
            ->get();

        $adaJadwalHariIni = $tugas->isNotEmpty();

        if ($tugas->isEmpty()) {
            $tugas = Pengangkutan::with(['warga.user', 'warga.rute', 'armada'])
                ->where('petugas_id', $userId)
                ->latest('tanggal_tugas')
                ->get();
        }

        // 2. Kelompokkan berdasarkan rute yang ditugaskan, lengkap dengan daftar warga-nya
        $routesHariIni = $tugas->filter(fn ($t) => $t->warga && $t->warga->rute)
            ->groupBy(fn ($t) => $t->warga->rute_id)
            ->map(function ($group) use ($adaJadwalHariIni) {
                $rute = $group->first()->warga->rute;
                $selesai = $group->where('status_tugas', 'Selesai')->count();

                return (object) [
                    'id' => $rute->id,
                    'nama_rute' => $rute->nama_rute,
                    'hari_angkut' => $rute->hari_angkut,
                    'keterangan' => $rute->keterangan,
                    'total' => $group->count(),
                    'selesai' => $selesai,
                    'status' => $selesai === $group->count()
                        ? 'Selesai'
                        : ($selesai > 0 ? 'Sedang dikerjakan' : 'Belum dikerjakan'),
                    'adaJadwalHariIni' => $adaJadwalHariIni,
                    'armada' => $group->first()->armada,
                    'warga' => $group->map(function ($t) {
                        return (object) [
                            'pengangkutan_id' => $t->id,
                            'nama_warga' => $t->warga->user->name ?? 'Warga',
                            'alamat_lengkap' => $t->warga->alamat_lengkap ?? '-',
                            'status_tugas' => $t->status_tugas,
                            'urutan' => (int) ($t->warga->urutan ?? 0),
                        ];
                    })->sortBy('urutan')->values(),
                ];
            })
            ->values();

        // 3. Statistik tugas hari ini (hanya milik petugas ini)
        $sisaTugas = Pengangkutan::where('petugas_id', $userId)
            ->whereDate('tanggal_tugas', today())
            ->where('status_tugas', '!=', 'Selesai')
            ->count();

        $selesaiTugas = Pengangkutan::where('petugas_id', $userId)
            ->whereDate('tanggal_tugas', today())
            ->where('status_tugas', 'Selesai')
            ->count();

        // 4. Laporan kendala yang pernah dikirim petugas ini
        $totalLaporan = DB::table('laporan_kendalas')
            ->where('petugas_id', $userId)
            ->count();

        $laporanTerbaru = DB::table('laporan_kendalas')
            ->where('petugas_id', $userId)
            ->latest('created_at')
            ->take(3)
            ->get();

        // 5. Armada yang ditugaskan (untuk kartu selamat bertugas)
        $armadaSaya = $tugas->first()?->armada;

        return view('petugas_lapangan.dashboard', compact(
            'routesHariIni',
            'sisaTugas',
            'selesaiTugas',
            'totalLaporan',
            'laporanTerbaru',
            'armadaSaya'
        ));
    }
}
