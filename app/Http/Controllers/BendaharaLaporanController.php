<?php

namespace App\Http\Controllers;

use App\Models\Penggajian;
use App\Models\Pengeluaran;
use App\Models\PenjualanSampah;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BendaharaLaporanController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', Carbon::now()->year);
        $bulan = $request->get('bulan', Carbon::now()->format('Y-m'));

        $pemasukanBulanIni = PenjualanSampah::whereYear('tanggal_penjualan', substr($bulan, 0, 4))
            ->whereMonth('tanggal_penjualan', substr($bulan, 5, 2))
            ->sum('total_harga');

        $pengeluaranGaji = Penggajian::where('status_pembayaran', 'Dibayar')
            ->where('bulan_gaji', $bulan)
            ->sum('total_gaji_bersih');

        $pengeluaranOperasional = Pengeluaran::whereYear('tanggal_pengeluaran', substr($bulan, 0, 4))
            ->whereMonth('tanggal_pengeluaran', substr($bulan, 5, 2))
            ->sum('jumlah_biaya');

        $totalPengeluaran = $pengeluaranGaji + $pengeluaranOperasional;
        $labaRugi = $pemasukanBulanIni - $totalPengeluaran;

        $penjualanMasuk = PenjualanSampah::whereYear('tanggal_penjualan', substr($bulan, 0, 4))
            ->whereMonth('tanggal_penjualan', substr($bulan, 5, 2))
            ->get()
            ->map(function ($item) {
                return (object) [
                    'tanggal' => $item->tanggal_penjualan,
                    'keterangan' => 'Penjualan: ' . ($item->nama_pengepul ?: 'Pengepul') . ' (' . $item->berat_kg . ' kg)',
                    'kategori' => 'Pemasukan',
                    'jumlah' => $item->total_harga,
                ];
            });

        $gajiDibayar = Penggajian::with('petugas')
            ->where('status_pembayaran', 'Dibayar')
            ->where('bulan_gaji', $bulan)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'tanggal' => $item->updated_at->toDateString(),
                    'keterangan' => 'Gaji: ' . ($item->petugas->name ?? 'Petugas') . ' (' . $item->bulan_gaji . ')',
                    'kategori' => 'Pengeluaran',
                    'jumlah' => $item->total_gaji_bersih,
                ];
            });

        $pengeluaranBulan = Pengeluaran::whereYear('tanggal_pengeluaran', substr($bulan, 0, 4))
            ->whereMonth('tanggal_pengeluaran', substr($bulan, 5, 2))
            ->get()
            ->map(function ($item) {
                return (object) [
                    'tanggal' => $item->tanggal_pengeluaran,
                    'keterangan' => 'Operasional: ' . $item->kategori_biaya . ' - ' . ($item->keterangan ?? ''),
                    'kategori' => 'Pengeluaran',
                    'jumlah' => $item->jumlah_biaya,
                ];
            });

        $riwayatTransaksi = $penjualanMasuk->concat($gajiDibayar)->concat($pengeluaranBulan)
            ->sortByDesc('tanggal')
            ->values();

        $dataGrafik = $this->getGrafikData($tahun);

        return view('bendahara.laporan.index', compact(
            'pemasukanBulanIni',
            'pengeluaranGaji',
            'pengeluaranOperasional',
            'totalPengeluaran',
            'labaRugi',
            'riwayatTransaksi',
            'bulan',
            'tahun',
            'dataGrafik'
        ));
    }

    public function cetak(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->format('Y-m'));

        $pemasukanBulanIni = PenjualanSampah::whereYear('tanggal_penjualan', substr($bulan, 0, 4))
            ->whereMonth('tanggal_penjualan', substr($bulan, 5, 2))
            ->sum('total_harga');

        $pengeluaranGaji = Penggajian::where('status_pembayaran', 'Dibayar')
            ->where('bulan_gaji', $bulan)
            ->sum('total_gaji_bersih');

        $pengeluaranOperasional = Pengeluaran::whereYear('tanggal_pengeluaran', substr($bulan, 0, 4))
            ->whereMonth('tanggal_pengeluaran', substr($bulan, 5, 2))
            ->sum('jumlah_biaya');

        $totalPengeluaran = $pengeluaranGaji + $pengeluaranOperasional;
        $labaRugi = $pemasukanBulanIni - $totalPengeluaran;

        return view('bendahara.laporan.cetak', compact(
            'pemasukanBulanIni',
            'pengeluaranGaji',
            'pengeluaranOperasional',
            'totalPengeluaran',
            'labaRugi',
            'bulan'
        ));
    }

    public function dataGrafik(Request $request)
    {
        $tahun = $request->get('tahun', Carbon::now()->year);

        $data = $this->getGrafikData($tahun);

        return response()->json($data);
    }

    // =========================================================
    // MODUL 8: NERACA KAS (POSISI KAS)
    // =========================================================
    public function neracaKas(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->format('Y-m'));

        // Kas masuk kumulatif sampai akhir periode terpilih
        $masukSampai = PenjualanSampah::where(function ($q) use ($bulan) {
            $q->whereYear('tanggal_penjualan', '<', substr($bulan, 0, 4))
                ->orWhere(function ($q2) use ($bulan) {
                    $q2->whereYear('tanggal_penjualan', substr($bulan, 0, 4))
                        ->whereMonth('tanggal_penjualan', '<=', substr($bulan, 5, 2));
                });
        })
            ->sum('total_harga');

        // Kas keluar kumulatif sampai akhir periode terpilih
        $keluarSampai = Penggajian::where('status_pembayaran', 'Dibayar')
            ->where('bulan_gaji', '<=', $bulan)
            ->sum('total_gaji_bersih');
        $keluarSampai += Pengeluaran::whereRaw("DATE_FORMAT(tanggal_pengeluaran, '%Y-%m') <= ?", [$bulan])
            ->sum('jumlah_biaya');

        $saldoAwal = $masukSampai - $keluarSampai;

        // Kas masuk pada bulan terpilih
        $masukBulanIni = PenjualanSampah::whereYear('tanggal_penjualan', substr($bulan, 0, 4))
            ->whereMonth('tanggal_penjualan', substr($bulan, 5, 2))
            ->sum('total_harga');

        $keluarGaji = Penggajian::where('status_pembayaran', 'Dibayar')
            ->where('bulan_gaji', $bulan)
            ->sum('total_gaji_bersih');
        $keluarOperasional = Pengeluaran::whereRaw("DATE_FORMAT(tanggal_pengeluaran, '%Y-%m') = ?", [$bulan])
            ->sum('jumlah_biaya');
        $keluarBulanIni = $keluarGaji + $keluarOperasional;

        $saldoAkhir = $saldoAwal + $masukBulanIni - $keluarBulanIni;

        return view('bendahara.laporan.neraca', compact(
            'bulan',
            'masukSampai',
            'keluarSampai',
            'saldoAwal',
            'masukBulanIni',
            'keluarGaji',
            'keluarOperasional',
            'keluarBulanIni',
            'saldoAkhir'
        ));
    }

    // =========================================================
    // MODUL 8: LAPORAN ARUS KAS (12 BULAN)
    // =========================================================
    public function arusKas(Request $request)
    {
        $tahun = $request->get('tahun', Carbon::now()->year);

        $labels = [];
        $arusMasuk = [];
        $arusKeluar = [];
        $saldoAkhir = [];

        $saldoBerjalan = 0;

        for ($i = 1; $i <= 12; $i++) {
            $bulanStr = sprintf('%02d', $i);
            $bulanTagih = "{$tahun}-{$bulanStr}";

            $masuk = PenjualanSampah::whereYear('tanggal_penjualan', $tahun)
                ->whereMonth('tanggal_penjualan', $i)
                ->sum('total_harga');

            $keluar = Penggajian::where('status_pembayaran', 'Dibayar')
                ->where('bulan_gaji', $bulanTagih)
                ->sum('total_gaji_bersih');
            $keluar += Pengeluaran::whereRaw("DATE_FORMAT(tanggal_pengeluaran, '%Y-%m') = ?", [$bulanTagih])
                ->sum('jumlah_biaya');

            $saldoBerjalan += $masuk - $keluar;

            $labels[] = Carbon::create()->month($i)->format('M');
            $arusMasuk[] = (int) $masuk;
            $arusKeluar[] = (int) $keluar;
            $saldoAkhir[] = (int) $saldoBerjalan;
        }

        return view('bendahara.laporan.arus-kas', [
            'tahun' => $tahun,
            'labels' => $labels,
            'arusMasuk' => $arusMasuk,
            'arusKeluar' => $arusKeluar,
            'saldoAkhir' => $saldoAkhir,
        ]);
    }

    private function getGrafikData($tahun)
    {
        $bulanNames = [
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
        ];

        $pemasukan = [];
        $pengeluaran = [];

        for ($i = 1; $i <= 12; $i++) {
            $bulanStr = sprintf('%02d', $i);

            $totalPemasukan = PenjualanSampah::whereYear('tanggal_penjualan', $tahun)
                ->whereMonth('tanggal_penjualan', $i)
                ->sum('total_harga');

            $totalGaji = Penggajian::where('status_pembayaran', 'Dibayar')
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $i)
                ->sum('total_gaji_bersih');

            $totalOperasional = Pengeluaran::whereYear('tanggal_pengeluaran', $tahun)
                ->whereMonth('tanggal_pengeluaran', $i)
                ->sum('jumlah_biaya');

            $pemasukan[] = (int) $totalPemasukan;
            $pengeluaran[] = (int) ($totalGaji + $totalOperasional);
        }

        return [
            'labels' => $bulanNames,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
        ];
    }
}