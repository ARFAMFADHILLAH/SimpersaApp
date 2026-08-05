<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Iuran;
use App\Models\Pengaduan;
use App\Models\Warga;
use App\Models\Pengangkutan;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $warga = Warga::with('rute', 'wilayahPelayanan')->where('user_id', $user->id)->first();

        if (!$warga) {
            return abort(404, 'Data profil warga Anda belum dikonfigurasi oleh admin.');
        }

        $riwayatIuran = Iuran::where('warga_id', $warga->id)
            ->latest()
            ->take(5)
            ->get();

        $tagihanBulanIni = Iuran::where('warga_id', $warga->id)
            ->where('status_pembayaran', 'Belum Bayar')
            ->latest()
            ->first();

        $iuranDiproses = Iuran::where('warga_id', $warga->id)
            ->where('status_pembayaran', 'Sedang Diproses')
            ->latest()
            ->first();

        $riwayatPengangkutan = Pengangkutan::where('warga_id', $warga->id)
            ->with('armada', 'jenisSampah')
            ->latest('tanggal_tugas')
            ->take(5)
            ->get();

        $pengaduanTerbaru = Pengaduan::where('warga_id', $warga->id)
            ->latest()
            ->take(3)
            ->get();

        return view('warga.dashboard', compact(
            'warga', 'riwayatIuran', 'tagihanBulanIni', 'iuranDiproses',
            'riwayatPengangkutan', 'pengaduanTerbaru'
        ));
    }
}
