<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Iuran;
use App\Models\Warga;
use App\Models\Notification;

class IuranController extends Controller
{
    public function index()
    {
        $warga = Warga::where('user_id', Auth::id())->firstOrFail();
        $riwayatIuran = Iuran::where('warga_id', $warga->id)
            ->latest()
            ->paginate(15);
        $tagihanBulanIni = Iuran::where('warga_id', $warga->id)
            ->where('status_pembayaran', 'Belum Bayar')
            ->latest()
            ->first();
        $iuranDiproses = Iuran::where('warga_id', $warga->id)
            ->where('status_pembayaran', 'Sedang Diproses')
            ->latest()
            ->first();
        return view('warga.iuran.index', compact('warga', 'riwayatIuran', 'tagihanBulanIni', 'iuranDiproses'));
    }

    public function bayar(Request $request, $id)
    {
        $warga = Warga::where('user_id', Auth::id())->firstOrFail();
        $iuran = Iuran::where('id', $id)->where('warga_id', $warga->id)->firstOrFail();

        if ($iuran->status_pembayaran !== 'Belum Bayar') {
            return redirect()->route('warga.iuran.index')
                ->with('error', 'Tagihan ini sudah dikonfirmasi dan tidak dapat diproses lagi.');
        }

        $rules = [
            'metode_pembayaran' => 'required|in:Tunai,Non-Tunai',
        ];

        // Bukti pembayaran wajib diunggah apabila pembayaran non-tunai
        if ($request->metode_pembayaran === 'Non-Tunai') {
            $rules['foto_bukti'] = 'required|image|mimes:jpeg,png,jpg|max:3072';
        } else {
            $rules['foto_bukti'] = 'nullable|image|mimes:jpeg,png,jpg|max:3072';
        }

        $request->validate($rules);

        $pathBukti = null;
        if ($request->hasFile('foto_bukti')) {
            $pathBukti = $request->file('foto_bukti')->store('bukti_iuran', 'public');
        }

        $iuran->update([
            'status_pembayaran' => 'Sedang Diproses',
            'tanggal_bayar' => null,
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_pembayaran' => $pathBukti,
            'denda' => $request->denda ?? 0,
        ]);

        // Notifikasi ke bendahara bahwa ada pembayaran menunggu verifikasi
        Notification::kirimKeRole(
            ['bendahara'],
            'Pembayaran Iuran Menunggu Verifikasi',
            "Pembayaran iuran periode {$iuran->bulan_tagihan} oleh {$warga->user->name} (No. {$warga->no_warga}) menunggu pengecekan bukti.",
            'iuran_verifikasi',
            route('bendahara.iuran.index')
        );

        return redirect()->route('warga.iuran.index')
            ->with('success', 'Konfirmasi pembayaran terkirim. Bukti Anda sedang diverifikasi oleh bendahara.');
    }

    public function kwitansi($id)
    {
        $warga = Warga::with('user', 'rute')->where('user_id', Auth::id())->firstOrFail();
        $iuran = Iuran::where('id', $id)->where('warga_id', $warga->id)->firstOrFail();

        return view('warga.iuran.kwitansi', compact('warga', 'iuran'));
    }
}
