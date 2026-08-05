<?php

namespace App\Http\Controllers\Owner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Warga;
use App\Models\Iuran;
use App\Models\Pengeluaran;
use App\Models\PengaturanIuran;
use Carbon\Carbon;

class ManagerController extends Controller
{
    public function dashboard()
    {
        $totalWarga = Warga::count();
        $wargaAktif = $totalWarga;
        $totalPemasukan = Iuran::sum('jumlah_tagihan') ?? 0;
        $totalPengeluaran = class_exists(Pengeluaran::class) ? Pengeluaran::sum('jumlah_biaya') : 0;
        $labaRugiBersih = $totalPemasukan - $totalPengeluaran;
        $totalVolumeSampah = \Schema::hasTable('pengangkutan') ? \DB::table('pengangkutan')->sum('volume_m3') : 0;

        return view('owner.dashboard', compact(
            'totalWarga',
            'wargaAktif',
            'labaRugiBersih',
            'totalVolumeSampah'
        ));
    }

    public function iuran()
    {
        $dataIuran = Iuran::with('warga.user')->latest()->paginate(20);
        return view('owner.iuran.index', compact('dataIuran'));
    }

    public function generateIuran()
    {
        $pengaturan = PengaturanIuran::firstOrFail();
        $bulanIni = Carbon::now()->format('Y-m');
        $wargaAktif = Warga::with('user')->whereHas('user', function ($q) {
            $q->where('status', 'aktif');
        })->get();

        $countGenerated = 0;

        foreach ($wargaAktif as $warga) {
            $exists = Iuran::where('warga_id', $warga->id)
                ->where('bulan_tagihan', $bulanIni)
                ->exists();

            if (!$exists) {
                Iuran::create([
                    'warga_id' => $warga->id,
                    'bulan_tagihan' => $bulanIni,
                    'jumlah_tagihan' => $pengaturan->tarif_dasar_bulanan,
                    'denda' => 0,
                    'status_pembayaran' => 'Belum Bayar',
                ]);
                $countGenerated++;
            }
        }

        return redirect()->route('owner.iuran.index')
            ->with('success', "Berhasil generate {$countGenerated} tagihan baru untuk bulan {$bulanIni}.");
    }

    public function bayarIuran(Request $request, $id)
    {
        $iuran = Iuran::findOrFail($id);

        if ($iuran->status_pembayaran === 'Lunas') {
            return redirect()->back()->with('error', 'Tagihan ini sudah lunas.');
        }

        $metode = $request->input('metode', $iuran->metode_pembayaran ?: 'Tunai');

        $denda = PengaturanIuran::hitungDenda($iuran->bulan_tagihan, $iuran->jumlah_tagihan);

        $iuran->update([
            'status_pembayaran' => 'Lunas',
            'tanggal_bayar' => Carbon::now()->toDateString(),
            'metode_pembayaran' => $metode,
            'denda' => $denda,
        ]);

        return redirect()->route('owner.iuran.index')
            ->with('success', 'Pembayaran iuran berhasil dikonfirmasi.');
    }
}
