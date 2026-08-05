<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Warga;
use App\Models\Pengangkutan;

class ProfileController extends Controller
{
    public function index()
    {
        $warga = Warga::with('rute', 'wilayahPelayanan')->where('user_id', Auth::id())->firstOrFail();
        return view('warga.profile.index', compact('warga'));
    }

    public function riwayat()
    {
        $warga = Warga::where('user_id', Auth::id())->firstOrFail();
        $riwayatPengangkutan = Pengangkutan::where('warga_id', $warga->id)
            ->with('armada', 'jenisSampah')
            ->latest('tanggal_tugas')
            ->paginate(15);
        return view('warga.profile.riwayat', compact('warga', 'riwayatPengangkutan'));
    }
}
