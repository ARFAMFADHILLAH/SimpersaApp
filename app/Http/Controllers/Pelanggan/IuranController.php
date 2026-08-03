<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Iuran;
use App\Models\Pelanggan;

class IuranController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::where('user_id', Auth::id())->firstOrFail();
        $riwayatIuran = Iuran::where('pelanggan_id', $pelanggan->id)
            ->latest()
            ->paginate(15);
        $tagihanBulanIni = Iuran::where('pelanggan_id', $pelanggan->id)
            ->where('status_pembayaran', 'Belum Bayar')
            ->latest()
            ->first();
        return view('pelanggan.iuran.index', compact('pelanggan', 'riwayatIuran', 'tagihanBulanIni'));
    }

    public function bayar(Request $request, $id)
    {
        $pelanggan = Pelanggan::where('user_id', Auth::id())->firstOrFail();
        $iuran = Iuran::where('id', $id)->where('pelanggan_id', $pelanggan->id)->firstOrFail();

        $request->validate([
            'metode_pembayaran' => 'required|in:Tunai,Non-Tunai',
        ]);

        $iuran->update([
            'status_pembayaran' => 'Lunas',
            'tanggal_bayar' => now()->toDateString(),
            'metode_pembayaran' => $request->metode_pembayaran,
            'denda' => $request->denda ?? 0,
        ]);

        return redirect()->route('pelanggan.iuran.index')
            ->with('success', 'Pembayaran iuran berhasil dikonfirmasi. Kwitansi dapat diunduh.');
    }

    public function kwitansi($id)
    {
        $pelanggan = Pelanggan::with('user', 'rute')->where('user_id', Auth::id())->firstOrFail();
        $iuran = Iuran::where('id', $id)->where('pelanggan_id', $pelanggan->id)->firstOrFail();

        return view('pelanggan.iuran.kwitansi', compact('pelanggan', 'iuran'));
    }
}
