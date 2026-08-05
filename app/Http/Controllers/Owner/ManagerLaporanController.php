<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagerLaporanController extends Controller
{
    public function index()
    {
        return view('owner.laporan.index');
    }

    public function warga()
    {
        $warga = DB::table('warga')
            ->leftJoin('users', 'warga.user_id', '=', 'users.id')
            ->leftJoin('wilayah_pelayanan', 'warga.wilayah_pelayanan_id', '=', 'wilayah_pelayanan.id')
            ->select('warga.*', 'users.name', 'users.email', 'users.status as status_user', 'wilayah_pelayanan.nama_wilayah')
            ->get();

        return view('owner.laporan.warga', compact('warga'));
    }

    public function iuran()
    {
        $iuran = DB::table('iuran')
            ->leftJoin('warga', 'iuran.warga_id', '=', 'warga.id')
            ->leftJoin('users', 'warga.user_id', '=', 'users.id')
            ->select('iuran.*', 'users.name as nama_warga', 'warga.no_warga')
            ->latest('iuran.created_at')
            ->get();

        return view('owner.laporan.iuran', compact('iuran'));
    }

    public function volume()
    {
        $pengangkutan = DB::table('pengangkutan')
            ->leftJoin('armada', 'pengangkutan.armada_id', '=', 'armada.id')
            ->leftJoin('warga', 'pengangkutan.warga_id', '=', 'warga.id')
            ->select('pengangkutan.*', 'armada.nama_kendaraan as nama_armada', 'warga.alamat_lengkap as lokasi')
            ->latest('pengangkutan.created_at')
            ->get();

        return view('owner.laporan.volume', compact('pengangkutan'));
    }

    public function keuangan()
    {
        $pemasukan = DB::table('iuran')->where('status_pembayaran', 'Lunas')->sum('jumlah_tagihan');
        $pengeluaran = DB::table('pengeluaran_operasional')->sum('jumlah_biaya');
        $gaji = DB::table('penggajian')->sum('total_gaji_bersih');

        return view('owner.laporan.keuangan', compact('pemasukan', 'pengeluaran', 'gaji'));
    }

    public function gaji()
    {
        $gaji = DB::table('penggajian')
            ->leftJoin('users', 'penggajian.petugas_id', '=', 'users.id')
            ->select('penggajian.*', 'users.name as nama_petugas')
            ->latest('penggajian.created_at')
            ->get();

        return view('owner.laporan.gaji', compact('gaji'));
    }

    public function armada()
    {
        $armada = DB::table('armada')->get();
        return view('owner.laporan.armada', compact('armada'));
    }

    // =========================================================
    // MODUL 10: LAPORAN TUNGGAKAN
    // =========================================================
    public function tunggakan()
    {
        $tunggakan = DB::table('iuran')
            ->leftJoin('warga', 'iuran.warga_id', '=', 'warga.id')
            ->leftJoin('users', 'warga.user_id', '=', 'users.id')
            ->leftJoin('wilayah_pelayanan', 'warga.wilayah_pelayanan_id', '=', 'wilayah_pelayanan.id')
            ->where('iuran.status_pembayaran', 'Belum Bayar')
            ->select(
                'warga.id as warga_id',
                'warga.no_warga',
                'users.name as nama_warga',
                'wilayah_pelayanan.nama_wilayah',
                DB::raw('COUNT(iuran.id) as jumlah_bln'),
                DB::raw('SUM(iuran.jumlah_tagihan) as total_tunggakan'),
                DB::raw('SUM(iuran.denda) as total_denda'),
                DB::raw('MIN(iuran.bulan_tagihan) as mulai_tunggakan')
            )
            ->groupBy('warga.id', 'warga.no_warga', 'users.name', 'wilayah_pelayanan.nama_wilayah')
            ->orderByDesc('total_tunggakan')
            ->get();

        $totalTunggakan = $tunggakan->sum('total_tunggakan');
        $totalDenda = $tunggakan->sum('total_denda');
        $jumlahWargaTunggak = $tunggakan->count();

        return view('owner.laporan.tunggakan', compact(
            'tunggakan',
            'totalTunggakan',
            'totalDenda',
            'jumlahWargaTunggak'
        ));
    }

    // =========================================================
    // MODUL 10: LAPORAN PETUGAS (KINERJA)
    // =========================================================
    public function petugas()
    {
        $petugas = DB::table('users')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->leftJoin('pengangkutan', 'users.id', '=', 'pengangkutan.petugas_id')
            ->whereIn('roles.name', ['petugas', 'petugas_lapangan'])
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'roles.name as nama_role',
                'users.status',
                DB::raw('COUNT(pengangkutan.id) as total_tugas'),
                DB::raw("SUM(CASE WHEN pengangkutan.status_tugas = 'Selesai' THEN 1 ELSE 0 END) as tugas_selesai"),
                DB::raw("SUM(CASE WHEN pengangkutan.status_tugas = 'Belum dikerjakan' THEN 1 ELSE 0 END) as tugas_belum"),
                DB::raw("SUM(CASE WHEN pengangkutan.status_tugas = 'Sedang dikerjakan' THEN 1 ELSE 0 END) as tugas_proses")
            )
            ->groupBy('users.id', 'users.name', 'users.email', 'roles.name', 'users.status')
            ->orderByDesc('total_tugas')
            ->get()
            ->map(function ($item) {
                $item->persentase = $item->total_tugas > 0
                    ? round(($item->tugas_selesai / $item->total_tugas) * 100, 1)
                    : 0;
                return $item;
            });

        return view('owner.laporan.petugas', compact('petugas'));
    }

    // =========================================================
    // MODUL 10: REKAP TAHUNAN
    // =========================================================
    public function rekapTahunan(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        $rekap = [];

        for ($i = 1; $i <= 12; $i++) {
            $bulanStr = sprintf('%02d', $i);
            $bulanTagih = "{$tahun}-{$bulanStr}";

            $pendapatan = DB::table('iuran')
                ->where('status_pembayaran', 'Lunas')
                ->whereYear('tanggal_bayar', $tahun)
                ->whereMonth('tanggal_bayar', $i)
                ->sum('jumlah_tagihan');

            $gaji = DB::table('penggajian')
                ->where('bulan_gaji', $bulanTagih)
                ->sum('total_gaji_bersih');

            $operasional = DB::table('pengeluaran_operasional')
                ->whereYear('tanggal_pengeluaran', $tahun)
                ->whereMonth('tanggal_pengeluaran', $i)
                ->sum('jumlah_biaya');

            $volume = DB::table('pengangkutan')
                ->whereYear('tanggal_tugas', $tahun)
                ->whereMonth('tanggal_tugas', $i)
                ->sum('volume_m3');

            $rekap[] = [
                'bulan' => \Carbon\Carbon::create()->month($i)->format('F'),
                'pendapatan' => $pendapatan,
                'gaji' => $gaji,
                'operasional' => $operasional,
                'pengeluaran' => $gaji + $operasional,
                'laba' => $pendapatan - ($gaji + $operasional),
                'volume' => $volume,
            ];
        }

        $totalPendapatan = array_sum(array_column($rekap, 'pendapatan'));
        $totalPengeluaran = array_sum(array_column($rekap, 'pengeluaran'));
        $totalLaba = $totalPendapatan - $totalPengeluaran;

        $daftarTahun = DB::table('iuran')
            ->selectRaw('DISTINCT YEAR(tanggal_bayar) as tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->map(fn ($t) => (string) $t);

        return view('owner.laporan.rekap-tahunan', compact(
            'tahun',
            'rekap',
            'totalPendapatan',
            'totalPengeluaran',
            'totalLaba',
            'daftarTahun'
        ));
    }

    public function kendala()
    {
        $kendala = DB::table('laporan_kendalas')
            ->leftJoin('users', 'laporan_kendalas.petugas_id', '=', 'users.id')
            ->select('laporan_kendalas.*', 'users.name as nama_petugas')
            ->orderBy('laporan_kendalas.created_at', 'desc')
            ->get();

        return view('owner.laporan.kendala', compact('kendala'));
    }

    public function cetak(Request $request)
    {
        // Fungsi pencetakan / ekspor
        return back()->with('success', 'Laporan berhasil diproses untuk dicetak!');
    }
}