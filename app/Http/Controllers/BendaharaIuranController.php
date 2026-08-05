<?php

namespace App\Http\Controllers;

use App\Models\Iuran;
use App\Models\Warga;
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

        $query = Iuran::with('warga.user');

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
        $totalDiproses = $dataIuran->where('status_pembayaran', 'Sedang Diproses')->sum('jumlah_tagihan');
        $jumlahDiproses = $dataIuran->where('status_pembayaran', 'Sedang Diproses')->count();

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
            'totalDiproses',
            'jumlahDiproses',
            'daftarBulan'
        ));
    }

    public function generate()
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

        return redirect()->route('bendahara.iuran.index')
            ->with('success', "Berhasil generate {$countGenerated} tagihan baru untuk bulan {$bulanIni}.");
    }

    public function bayar(Request $request, $id)
    {
        $iuran = Iuran::findOrFail($id);

        if ($iuran->status_pembayaran === 'Lunas') {
            return redirect()->back()->with('error', 'Tagihan ini sudah lunas.');
        }

        // Metode pembayaran: wajib diisi hanya jika belum pernah dipilih (walk-in di kantor)
        $rules = [];
        if (!$iuran->metode_pembayaran) {
            $rules['metode_pembayaran'] = 'required|in:Tunai,Non-Tunai';
        }
        $request->validate($rules);

        $denda = PengaturanIuran::hitungDenda($iuran->bulan_tagihan, $iuran->jumlah_tagihan);

        $iuran->update([
            'status_pembayaran' => 'Lunas',
            'tanggal_bayar' => Carbon::now()->toDateString(),
            'metode_pembayaran' => $iuran->metode_pembayaran ?: $request->metode_pembayaran,
            'denda' => $denda,
        ]);

        // Notifikasi ke warga bahwa iuran telah dikonfirmasi lunas
        $iuran->load('warga.user');
        if ($iuran->warga?->user_id) {
            Notification::kirim(
                $iuran->warga->user_id,
                'Iuran Sampah Lunas',
                "Pembayaran iuran periode {$iuran->bulan_tagihan} sebesar Rp " . number_format($iuran->jumlah_tagihan + $iuran->denda, 0, ',', '.') . " telah dikonfirmasi lunas.",
                'iuran_lunas',
                route('warga.iuran.index')
            );
        }

        return redirect()->route('bendahara.iuran.index')
            ->with('success', 'Pembayaran iuran berhasil dikonfirmasi.');
    }

    public function cetakKwitansi($id)
    {
        $iuran = Iuran::with('warga.user', 'warga.wilayahPelayanan')->findOrFail($id);

        return view('bendahara.iuran.kwitansi', compact('iuran'));
    }

    public function tunggakan()
    {
        $dataTunggakan = Iuran::with('warga.user')
            ->whereIn('status_pembayaran', ['Belum Bayar', 'Sedang Diproses'])
            ->orderBy('bulan_tagihan', 'asc')
            ->get()
            ->groupBy('warga_id');

        return view('bendahara.iuran.tunggakan', compact('dataTunggakan'));
    }
}
