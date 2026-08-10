<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\PenjualanSampah;
use App\Models\KategoriSampah;
use App\Models\JenisSampah;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    /**
     * Rekap penjualan sampah ke pengepul (+ input bila diperlukan dari meja bendahara).
     */
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->toDateString());
        $namaPengepul = $request->get('nama_pengepul', '');

        $query = PenjualanSampah::with('jenisSampah', 'kategoriSampah');

        if ($namaPengepul) {
            $query->where('nama_pengepul', 'like', "%{$namaPengepul}%");
        }

        $query->whereDate('tanggal_penjualan', $tanggal);

        $riwayat = $query->latest('tanggal_penjualan')->get();

        $dataKategori = KategoriSampah::with('jenisSampah')->orderBy('nama_kategori')->get();

        $totalRupiah = $riwayat->sum('total_harga');
        $totalKg = $riwayat->sum('berat_kg');
        $totalTransaksi = $riwayat->count();

        return view('bendahara.penjualan.index', compact(
            'riwayat',
            'dataKategori',
            'totalRupiah',
            'totalKg',
            'totalTransaksi',
            'tanggal',
            'namaPengepul'
        ));
    }

    public function store(Request $request)
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

        return redirect()->route('bendahara.penjualan.index')
                         ->with('success', 'Penjualan sampah ke pengepul berhasil dicatat!');
    }
}