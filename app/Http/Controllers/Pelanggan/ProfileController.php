<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pelanggan;
use App\Models\Pengangkutan;

class ProfileController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::with('rute', 'wilayahPelayanan')->where('user_id', Auth::id())->firstOrFail();
        return view('pelanggan.profile.index', compact('pelanggan'));
    }

    public function riwayat()
    {
        $pelanggan = Pelanggan::where('user_id', Auth::id())->firstOrFail();
        $riwayatPengangkutan = Pengangkutan::where('pelanggan_id', $pelanggan->id)
            ->with('armada', 'jenisSampah')
            ->latest('tanggal_tugas')
            ->paginate(15);
        return view('pelanggan.profile.riwayat', compact('pelanggan', 'riwayatPengangkutan'));
    }
}
