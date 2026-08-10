<?php

namespace App\Http\Controllers\Petugas_lapangan;

use App\Http\Controllers\Controller;
use App\Models\JenisSampah;
use App\Models\KategoriSampah;
use App\Models\PenjualanSampah;
use App\Models\SetoranSampah;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    /**
     * Form + riwayat transaksi pembelian sampah dari warga.
     */
    public function pembelian(Request $request)
    {
        $dataWarga = Warga::with('user')->orderBy('id')->get();
        $dataKategori = KategoriSampah::with('jenisSampah')->orderBy('nama_kategori')->get();

        $daftarJenis = $dataKategori->flatMap(fn ($k) => $k->jenisSampah->map(fn ($j) => [
            'id' => $j->id,
            'kategori_id' => $k->id,
            'nama' => $j->nama_jenis,
            'harga' => (float) $j->tarif_per_kg,
        ]));

        $tanggal = $request->get('tanggal', now()->toDateString());

        $riwayat = SetoranSampah::with('warga.user', 'jenisSampah.kategoriSampah')
            ->whereDate('tanggal_setoran', $tanggal)
            ->latest('tanggal_setoran')
            ->get();

        $totalHariIni = $riwayat->sum('total_bayar');
        $totalBeratHariIni = $riwayat->sum('berat_kg');

        return view('petugas_lapangan.kasir.pembelian', compact(
            'dataWarga',
            'dataKategori',
            'daftarJenis',
            'riwayat',
            'totalHariIni',
            'totalBeratHariIni',
            'tanggal'
        ));
    }

    /**
     * Simpan transaksi pembelian sampah dari warga (dikredit ke saldo tabungan warga).
     */
    public function storePembelian(Request $request)
    {
        $validated = $request->validate([
            'warga_id'          => 'required|exists:warga,id',
            'jenis_sampah_id'   => 'required|exists:jenis_sampah_dan_tarif,id',
            'berat_kg'          => 'required|numeric|min:0.01',
            'tanggal_setoran'   => 'required|date',
            'keterangan'        => 'nullable|string|max:255',
        ]);

        $jenis = JenisSampah::findOrFail($validated['jenis_sampah_id']);
        $hargaPerKg = (int) $jenis->tarif_per_kg;
        $totalBayar = (int) round($validated['berat_kg'] * $hargaPerKg);

        $setoran = DB::transaction(function () use ($validated, $jenis, $hargaPerKg, $totalBayar) {
            $setoran = SetoranSampah::create([
                'warga_id'          => $validated['warga_id'],
                'jenis_sampah_id'   => $jenis->id,
                'berat_kg'          => $validated['berat_kg'],
                'harga_per_kg'      => $hargaPerKg,
                'total_bayar'       => $totalBayar,
                'tanggal_setoran'   => $validated['tanggal_setoran'],
                'keterangan'        => $validated['keterangan'] ?? null,
            ]);

            Warga::where('id', $validated['warga_id'])->increment('saldo_tabungan', $totalBayar);

            return $setoran;
        });

        return redirect()->route('petugas.pembelian.nota', $setoran->id);
    }

    /**
     * Cetak Nota hasil penimbangan untuk warga.
     */
    public function nota($id)
    {
        $setoran = SetoranSampah::with('warga.user', 'jenisSampah.kategoriSampah')->findOrFail($id);

        return view('petugas_lapangan.kasir.nota', compact('setoran'));
    }

    /**
     * Form + riwayat pencatatan penjualan sampah ke pengepul.
     */
    public function penjualan(Request $request)
    {
        $dataKategori = KategoriSampah::with('jenisSampah')->orderBy('nama_kategori')->get();

        $daftarJenis = $dataKategori->flatMap(fn ($k) => $k->jenisSampah->map(fn ($j) => [
            'id' => $j->id,
            'kategori_id' => $k->id,
            'nama' => $j->nama_jenis,
            'harga' => (float) $j->tarif_jual_per_kg,
        ]));

        $tanggal = $request->get('tanggal', now()->toDateString());

        $riwayat = PenjualanSampah::with('jenisSampah', 'kategoriSampah')
            ->whereDate('tanggal_penjualan', $tanggal)
            ->latest('tanggal_penjualan')
            ->get();

        $totalHariIni = $riwayat->sum('total_harga');
        $totalBeratHariIni = $riwayat->sum('berat_kg');

        return view('petugas_lapangan.kasir.penjualan', compact(
            'dataKategori',
            'daftarJenis',
            'riwayat',
            'totalHariIni',
            'totalBeratHariIni',
            'tanggal'
        ));
    }

    /**
     * Simpan penjualan sampah ke pengepul.
     */
    public function storePenjualan(Request $request)
    {
        $validated = $request->validate([
            'jenis_sampah_id'   => 'required|exists:jenis_sampah_dan_tarif,id',
            'nama_pengepul'     => 'nullable|string|max:255',
            'berat_kg'          => 'required|numeric|min:0.01',
            'harga_jual_per_kg' => 'required|numeric|min:0',
            'tanggal_penjualan' => 'required|date',
            'catatan'           => 'nullable|string|max:255',
        ]);

        $jenis = JenisSampah::findOrFail($validated['jenis_sampah_id']);
        $totalHarga = (int) round($validated['berat_kg'] * $validated['harga_jual_per_kg']);

        PenjualanSampah::create([
            'kategori_sampah_id' => $jenis->kategori_sampah_id,
            'jenis_sampah_id'    => $jenis->id,
            'nama_pengepul'      => $validated['nama_pengepul'] ?? null,
            'berat_kg'           => $validated['berat_kg'],
            'harga_jual_per_kg'  => (int) $validated['harga_jual_per_kg'],
            'total_harga'        => $totalHarga,
            'tanggal_penjualan'  => $validated['tanggal_penjualan'],
            'catatan'            => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('petugas.penjualan.index')
                         ->with('success', 'Penjualan sampah ke pengepul berhasil dicatat!');
    }
}