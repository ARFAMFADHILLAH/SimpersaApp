<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Penggajian;
use App\Models\PenjualanSampah;
use App\Models\SetoranSampah;
use App\Models\Warga;
use App\Models\PenarikanSaldo;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Ringkasan monitoring (read-only) seluruh transaksi keuangan POS.
     */
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', now()->year);

        $masuk = PenjualanSampah::whereYear('tanggal_penjualan', $tahun)->sum('total_harga');
        $keluarBeli = SetoranSampah::whereYear('tanggal_setoran', $tahun)->sum('total_bayar');
        $keluarTarik = PenarikanSaldo::where('status', 'Ditarik')->whereYear('tanggal_penarikan', $tahun)->sum('nominal');
        $keluarGaji = Penggajian::where('status_pembayaran', 'Dibayar')->whereYear('created_at', $tahun)->sum('total_gaji_bersih');
        $totalKeluar = $keluarBeli + $keluarTarik + $keluarGaji;

        $totalNasabah = Warga::count();
        $totalSaldoTabungan = (float) Warga::sum('saldo_tabungan');
        $totalKgBeli = SetoranSampah::whereYear('tanggal_setoran', $tahun)->sum('berat_kg');
        $totalKgJual = PenjualanSampah::whereYear('tanggal_penjualan', $tahun)->sum('berat_kg');

        // Grafik 12 bulan kas masuk vs keluar
        $labels = [];
        $grafikMasuk = [];
        $grafikKeluar = [];

        for ($i = 1; $i <= 12; $i++) {
            $bulanStr = sprintf('%02d', $i);
            $gMasuk = PenjualanSampah::whereYear('tanggal_penjualan', $tahun)->whereMonth('tanggal_penjualan', $i)->sum('total_harga');
            $gKeluar = SetoranSampah::whereYear('tanggal_setoran', $tahun)->whereMonth('tanggal_setoran', $i)->sum('total_bayar')
                + PenarikanSaldo::where('status', 'Ditarik')->whereYear('tanggal_penarikan', $tahun)->whereMonth('tanggal_penarikan', $i)->sum('nominal')
                + Penggajian::where('status_pembayaran', 'Dibayar')->whereYear('created_at', $tahun)->whereMonth('created_at', $i)->sum('total_gaji_bersih');

            $grafikMasuk[] = (int) $gMasuk;
            $grafikKeluar[] = (int) $gKeluar;
            $labels[] = \Carbon\Carbon::create()->month($i)->format('M');
        }

        return view('owner.laporan.index', compact(
            'tahun',
            'masuk',
            'keluarBeli',
            'keluarTarik',
            'keluarGaji',
            'totalKeluar',
            'totalNasabah',
            'totalSaldoTabungan',
            'totalKgBeli',
            'totalKgJual',
            'labels',
            'grafikMasuk',
            'grafikKeluar'
        ));
    }

    /**
     * Arus kas masuk (penjualan) vs keluar (pembelian, penarikan, gaji) — filter fleksibel sampai tahunan.
     */
    public function kas(Request $request)
    {
        $tahun = $request->get('tahun', now()->year);
        $bulan = $request->get('bulan', now()->format('Y-m'));

        $queryMasuk = PenjualanSampah::whereYear('tanggal_penjualan', substr($bulan, 0, 4));
        if ($bulan) $queryMasuk->whereMonth('tanggal_penjualan', substr($bulan, 5, 2));

        $masuk = $queryMasuk->sum('total_harga');

        $keluarBeli = SetoranSampah::whereYear('tanggal_setoran', substr($bulan, 0, 4))->whereMonth('tanggal_setoran', substr($bulan, 5, 2))->sum('total_bayar');
        $keluarTarik = PenarikanSaldo::where('status', 'Ditarik')->whereYear('tanggal_penarikan', substr($bulan, 0, 4))->whereMonth('tanggal_penarikan', substr($bulan, 5, 2))->sum('nominal');
        $keluarGaji = Penggajian::where('status_pembayaran', 'Dibayar')->whereYear('created_at', substr($bulan, 0, 4))->whereMonth('created_at', substr($bulan, 5, 2))->sum('total_gaji_bersih');

        $totalKeluar = $keluarBeli + $keluarTarik + $keluarGaji;
        $sisaKas = $masuk - $totalKeluar;

        // Riwayat transaksi kas gabungan
        $transaksi = collect();
        PenjualanSampah::with('jenisSampah')->whereYear('tanggal_penjualan', substr($bulan, 0, 4))->whereMonth('tanggal_penjualan', substr($bulan, 5, 2))->get()->each(function ($t) use (&$transaksi) {
            $transaksi->push((object) [
                'tanggal' => $t->tanggal_penjualan,
                'keterangan' => 'Penjualan: ' . ($t->jenisSampah->nama_jenis ?? '-') . ' ke ' . ($t->nama_pengepul ?? 'pengepul'),
                'kategori' => 'Masuk',
                'jumlah' => $t->total_harga,
            ]);
        });
        SetoranSampah::with('warga.user')->whereYear('tanggal_setoran', substr($bulan, 0, 4))->whereMonth('tanggal_setoran', substr($bulan, 5, 2))->get()->each(function ($t) use (&$transaksi) {
            $transaksi->push((object) [
                'tanggal' => $t->tanggal_setoran,
                'keterangan' => 'Pembelian: ' . ($t->warga->user->name ?? 'Warga'),
                'kategori' => 'Keluar',
                'jumlah' => $t->total_bayar,
            ]);
        });
        Penggajian::with('petugas')->where('status_pembayaran', 'Dibayar')->whereMonth('created_at', substr($bulan, 5, 2))->get()->each(function ($t) use (&$transaksi) {
            $transaksi->push((object) [
                'tanggal' => $t->created_at->toDateString(),
                'keterangan' => 'Gaji: ' . ($t->petugas->name ?? 'petugas'),
                'kategori' => 'Keluar',
                'jumlah' => $t->total_gaji_bersih,
            ]);
        });
        $transaksi = $transaksi->sortByDesc('tanggal')->values();

        return view('owner.laporan.kas', compact(
            'bulan',
            'tahun',
            'masuk',
            'keluarBeli',
            'keluarTarik',
            'keluarGaji',
            'totalKeluar',
            'sisaKas',
            'transaksi'
        ));
    }

    /** Laporan pembelian sampah warga (read-only). */
    public function pembelian(Request $request)
    {
        $query = SetoranSampah::with('warga.user', 'jenisSampah.kategoriSampah');
        $tanggal = $request->get('tanggal', '');
        $wargaId = $request->get('warga_id', '');
        if ($tanggal) $query->whereDate('tanggal_setoran', $tanggal);
        if ($wargaId) $query->where('warga_id', $wargaId);

        $riwayat = $query->latest('tanggal_setoran')->get();
        $dataWarga = Warga::with('user')->orderBy('id')->get();

        return view('owner.laporan.pembelian', compact('riwayat', 'dataWarga', 'tanggal', 'wargaId'));
    }

    /** Laporan penjualan sampah ke pengepul (read-only). */
    public function penjualan(Request $request)
    {
        $query = PenjualanSampah::with('jenisSampah', 'kategoriSampah');
        $tanggal = $request->get('tanggal', '');
        if ($tanggal) $query->whereDate('tanggal_penjualan', $tanggal);

        $riwayat = $query->latest('tanggal_penjualan')->get();
        $totalRupiah = $riwayat->sum('total_harga');
        $totalKg = $riwayat->sum('berat_kg');

        return view('owner.laporan.penjualan', compact('riwayat', 'totalRupiah', 'totalKg', 'tanggal'));
    }

    /** Laporan penggajian (read-only). */
    public function gaji(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));
        $rekap = Penggajian::with('petugas')->where('bulan_gaji', $bulan)->get();

        return view('owner.laporan.gaji', compact('rekap', 'bulan'));
    }

    /** Laporan saldo tabungan warga (read-only). */
    public function tabungan()
    {
        $dataWarga = Warga::with('user')->orderByDesc('saldo_tabungan')->get();
        $totalSaldo = (float) $dataWarga->sum('saldo_tabungan');

        return view('owner.laporan.tabungan', compact('dataWarga', 'totalSaldo'));
    }
}