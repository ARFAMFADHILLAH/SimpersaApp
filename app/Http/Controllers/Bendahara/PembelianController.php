<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\SetoranSampah;
use App\Models\Warga;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    /**
     * Laporan rincian pembelian sampah warga:
     * [Nama Warga] | [Tanggal] | [Jenis Sampah] | [Berat/Volume] | [Harga/Kg] | [Total]
     */
    public function index(Request $request)
    {
        $wargaId = $request->get('warga_id', '');
        $tanggal = $request->get('tanggal', '');

        $query = SetoranSampah::with('warga.user', 'jenisSampah.kategoriSampah');

        if ($wargaId) {
            $query->where('warga_id', $wargaId);
        }

        if ($tanggal) {
            $query->whereDate('tanggal_setoran', $tanggal);
        }

        $riwayat = $query->latest('tanggal_setoran')->get();

        $totalKg = $riwayat->sum('berat_kg');
        $totalRupiah = $riwayat->sum('total_bayar');
        $totalTransaksi = $riwayat->count();

        $dataWarga = Warga::with('user')->orderBy('id')->get();

        return view('bendahara.pembelian.index', compact(
            'riwayat',
            'dataWarga',
            'totalKg',
            'totalRupiah',
            'totalTransaksi',
            'wargaId',
            'tanggal'
        ));
    }
}