<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\PenjualanSampah;
use App\Models\KategoriSampah;
use App\Models\JenisSampah;
use App\Support\KodeTransaksi;
use App\Support\StokSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $daftarJenis = $dataKategori->flatMap(fn ($k) => $k->jenisSampah->map(fn ($j) => [
            'id' => $j->id,
            'kategori_id' => $k->id,
            'nama' => $j->nama_jenis,
            'harga' => (float) $j->tarif_jual_per_kg,
        ]));

        $totalRupiah = $riwayat->sum('total_harga');
        $totalKg = $riwayat->sum('berat_kg');
        $totalTransaksi = $riwayat->count();

        $stokPerJenis = StokSampah::perJenis()->mapWithKeys(fn ($s) => [$s->jenis_id => $s->stok_kg]);

        return view('bendahara.penjualan.index', compact(
            'riwayat',
            'dataKategori',
            'daftarJenis',
            'totalRupiah',
            'totalKg',
            'totalTransaksi',
            'tanggal',
            'namaPengepul',
            'stokPerJenis'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pengepul'     => 'nullable|string|max:255',
            'tanggal_penjualan' => 'required|date',
            'catatan'           => 'nullable|string|max:255',
            'items'             => 'required|array|min:1',
            'items.*.jenis_sampah_id'   => 'required|exists:jenis_sampah_dan_tarif,id',
            'items.*.berat_kg'          => 'required|numeric|min:0.01',
            'items.*.harga_jual_per_kg' => 'required|numeric|min:0',
        ], [
            'items.required'             => 'Minimal satu item sampah harus diisi.',
            'items.*.berat_kg.min'       => 'Berat sampah setiap item minimal 0.01 kg.',
        ]);

        $permintaan = [];
        foreach ($validated['items'] as $item) {
            $id = (int) $item['jenis_sampah_id'];
            $permintaan[$id] = ($permintaan[$id] ?? 0) + (float) $item['berat_kg'];
        }

        foreach ($permintaan as $id => $berat) {
            $stok = StokSampah::stokTersedia($id);
            if ($berat > $stok + 0.0001) {
                $jenis = JenisSampah::find($id);

                return back()->withInput()->withErrors([
                    'items' => "Stok sampah \"{$jenis->nama_jenis}\" tidak mencukupi: tersedia {$stok} kg, diminta {$berat} kg.",
                ]);
            }
        }

        DB::transaction(function () use ($validated) {
            $kode = KodeTransaksi::buat('JUAL', 'penjualan_sampah', 'tanggal_penjualan', $validated['tanggal_penjualan']);

            foreach ($validated['items'] as $item) {
                $jenis = JenisSampah::findOrFail($item['jenis_sampah_id']);
                $totalHarga = (int) round($item['berat_kg'] * $item['harga_jual_per_kg']);

                PenjualanSampah::create([
                    'kode_transaksi'     => $kode,
                    'kategori_sampah_id' => $jenis->kategori_sampah_id,
                    'jenis_sampah_id'    => $jenis->id,
                    'nama_pengepul'      => $validated['nama_pengepul'] ?? null,
                    'berat_kg'           => $item['berat_kg'],
                    'harga_jual_per_kg'  => (int) $item['harga_jual_per_kg'],
                    'total_harga'        => $totalHarga,
                    'tanggal_penjualan'  => $validated['tanggal_penjualan'],
                    'catatan'            => $validated['catatan'] ?? null,
                ]);
            }
        });

        return redirect()->route('bendahara.penjualan.index')
                         ->with('success', 'Penjualan sampah ke pengepul berhasil dicatat!');
    }
}