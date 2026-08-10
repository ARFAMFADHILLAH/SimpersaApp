<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPetugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));
        $tahun = (int) substr($bulan, 0, 4);
        $bulanAngka = (int) substr($bulan, 5, 2);

        $rolePetugas = DB::table('roles')
            ->whereIn('name', ['petugas', 'petugas_lapangan'])
            ->pluck('id');

        $dataPetugas = User::whereIn('role_id', $rolePetugas)
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get();

        $dataAbsensi = [];

        foreach ($dataPetugas as $petugas) {
            $riwayat = AbsensiPetugas::where('user_id', $petugas->id)
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulanAngka)
                ->orderBy('tanggal')
                ->get();

            $dataAbsensi[] = (object) [
                'petugas' => $petugas,
                'total_hadir' => $riwayat->where('status', 'hadir')->count(),
                'total_izin' => $riwayat->where('status', 'izin')->count(),
                'total_sakit' => $riwayat->where('status', 'sakit')->count(),
                'total_alpha' => $riwayat->where('status', 'alpha')->count(),
                'riwayat' => $riwayat,
            ];
        }

        return view('bendahara.absensi.index', compact('bulan', 'dataAbsensi'));
    }
}
