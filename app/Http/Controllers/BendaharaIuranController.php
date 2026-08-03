<?php

namespace App\Http\Controllers;

use App\Models\Iuran;
use App\Models\Pelanggan;
use App\Models\PengaturanIuran;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BendaharaIuranController extends Controller
{
    public function index(Request $request)
    {
        $bulanFilter = $request->get('bulan', Carbon::now()->format('Y-m'));
        $statusFilter = $request->get('status', '');

        $pengaturan = PengaturanIuran::firstOrCreate(
            ['id' => 1],
            [
                'tarif_dasar_bulanan' => 20000,
                'persentase_denda_per_bulan' => 5,
                'nominal_denda_flat' => 5000,
                'tgl_jatuh_tempo' => 10,
            ]
        );

        $query = Iuran::with('pelanggan.user');

        if ($bulanFilter) {
            $query->where('bulan_tagihan', $bulanFilter);
        }

        if ($statusFilter) {
            $query->where('status_pembayaran', $statusFilter);
        }

        $dataIuran = $query->latest('bulan_tagihan')->get();

        $totalTagihan = $dataIuran->sum('jumlah_tagihan');
        $totalDenda = $dataIuran->sum('denda');
        $totalLunas = $dataIuran->where('status_pembayaran', 'Lunas')->sum('jumlah_tagihan');
        $totalTunggakan = $dataIuran->where('status_pembayaran', 'Belum Bayar')->sum('jumlah_tagihan');
        $jumlahMenunggak = $dataIuran->where('status_pembayaran', 'Belum Bayar')->count();

        $daftarBulan = Iuran::select('bulan_tagihan')
            ->distinct()
            ->orderBy('bulan_tagihan', 'desc')
            ->pluck('bulan_tagihan');

        return view('bendahara.iuran.index', compact(
            'dataIuran',
            'pengaturan',
            'bulanFilter',
            'statusFilter',
            'totalTagihan',
            'totalDenda',
            'totalLunas',
            'totalTunggakan',
            'jumlahMenunggak',
            'daftarBulan'
        ));
    }

    public function generate()
    {
        $pengaturan = PengaturanIuran::firstOrFail();
        $bulanIni = Carbon::now()->format('Y-m');
        $pelangganAktif = Pelanggan::with('user')->whereHas('user', function ($q) {
            $q->where('status', 'aktif');
        })->get();

        $countGenerated = 0;

        foreach ($pelangganAktif as $pelanggan) {
            $exists = Iuran::where('pelanggan_id', $pelanggan->id)
                ->where('bulan_tagihan', $bulanIni)
                ->exists();

            if (!$exists) {
                Iuran::create([
                    'pelanggan_id' => $pelanggan->id,
                    'bulan_tagihan' => $bulanIni,
                    'jumlah_tagihan' => $pengaturan->tarif_dasar_bulanan,
                    'denda' => 0,
                    'status_pembayaran' => 'Belum Bayar',
                ]);
                $countGenerated++;
            }
        }

        return redirect()->route('bendahara.iuran.index')
            ->with('success', "Berhasil generate {$countGenerated} tagihan baru untuk bulan {$bulanIni}.");
    }

    public function bayar(Request $request, $id)
    {
        $iuran = Iuran::findOrFail($id);

        if ($iuran->status_pembayaran === 'Lunas') {
            return redirect()->back()->with('error', 'Tagihan ini sudah lunas.');
        }

        $request->validate([
            'metode_pembayaran' => 'required|in:Tunai,Non-Tunai',
        ]);

        $denda = PengaturanIuran::hitungDenda($iuran->bulan_tagihan, $iuran->jumlah_tagihan);

        $iuran->update([
            'status_pembayaran' => 'Lunas',
            'tanggal_bayar' => Carbon::now()->toDateString(),
            'metode_pembayaran' => $request->metode_pembayaran,
            'denda' => $denda,
        ]);

        // Notifikasi ke pelanggan bahwa iuran telah dikonfirmasi lunas
        $iuran->load('pelanggan.user');
        if ($iuran->pelanggan?->user_id) {
            Notification::kirim(
                $iuran->pelanggan->user_id,
                'Iuran Sampah Lunas',
                "Pembayaran iuran periode {$iuran->bulan_tagihan} sebesar Rp " . number_format($iuran->jumlah_tagihan + $iuran->denda, 0, ',', '.') . " telah dikonfirmasi lunas.",
                'iuran_lunas',
                route('pelanggan.iuran.index')
            );
        }

        return redirect()->route('bendahara.iuran.index')
            ->with('success', 'Pembayaran iuran berhasil dikonfirmasi.');
    }

    public function cetakKwitansi($id)
    {
        $iuran = Iuran::with('pelanggan.user', 'pelanggan.wilayahPelayanan')->findOrFail($id);

        return view('bendahara.iuran.kwitansi', compact('iuran'));
    }

    public function tunggakan()
    {
        $dataTunggakan = Iuran::with('pelanggan.user')
            ->where('status_pembayaran', 'Belum Bayar')
            ->orderBy('bulan_tagihan', 'asc')
            ->get()
            ->groupBy('pelanggan_id');

        return view('bendahara.iuran.tunggakan', compact('dataTunggakan'));
    }
}
