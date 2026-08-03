<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Iuran;
use App\Models\Pengaduan;
use App\Models\Pelanggan;
use App\Models\Pengangkutan;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pelanggan = Pelanggan::with('rute', 'wilayahPelayanan')->where('user_id', $user->id)->first();

        if (!$pelanggan) {
            return abort(404, 'Data profil pelanggan Anda belum dikonfigurasi oleh admin.');
        }

        $riwayatIuran = Iuran::where('pelanggan_id', $pelanggan->id)
            ->latest()
            ->take(5)
            ->get();

        $tagihanBulanIni = Iuran::where('pelanggan_id', $pelanggan->id)
            ->where('status_pembayaran', 'Belum Bayar')
            ->latest()
            ->first();

        $riwayatPengangkutan = Pengangkutan::where('pelanggan_id', $pelanggan->id)
            ->with('armada', 'jenisSampah')
            ->latest('tanggal_tugas')
            ->take(5)
            ->get();

        $pengaduanTerbaru = Pengaduan::where('pelanggan_id', $pelanggan->id)
            ->latest()
            ->take(3)
            ->get();

        return view('pelanggan.dashboard', compact(
            'pelanggan', 'riwayatIuran', 'tagihanBulanIni',
            'riwayatPengangkutan', 'pengaduanTerbaru'
        ));
    }
}
