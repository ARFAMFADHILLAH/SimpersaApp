<?php

namespace App\Http\Controllers\Petugas_lapangan;

use App\Http\Controllers\Controller;
use App\Models\JenisSampah;
use App\Models\KategoriSampah;
use App\Models\PenjualanSampah;
use App\Models\SetoranSampah;
use App\Models\Warga;
use App\Support\KodeTransaksi;
use App\Support\StokSampah;
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
     * Simpan transaksi pembelian sampah dari warga (multi-item, dikredit ke saldo tabungan warga).
     */
    public function storePembelian(Request $request)
    {
        $validated = $request->validate([
            'warga_id'          => 'required|exists:warga,id',
            'tanggal_setoran'   => 'required|date',
            'keterangan'        => 'nullable|string|max:255',
            'items'             => 'required|array|min:1',
            'items.*.jenis_sampah_id' => 'required|exists:jenis_sampah_dan_tarif,id',
            'items.*.berat_kg'  => 'required|numeric|min:0.01',
        ], [
            'items.required'             => 'Minimal satu item sampah harus diisi.',
            'items.*.jenis_sampah_id.required' => 'Pilih jenis sampah pada setiap item.',
            'items.*.berat_kg.min'       => 'Berat sampah setiap item minimal 0.01 kg.',
        ]);

        $hasil = DB::transaction(function () use ($validated) {
            $kode = KodeTransaksi::buat('STR', 'setoran_sampahs', 'tanggal_setoran', $validated['tanggal_setoran']);
            $totalKeseluruhan = 0;

            foreach ($validated['items'] as $item) {
                $jenis = JenisSampah::findOrFail($item['jenis_sampah_id']);
                $hargaPerKg = (int) $jenis->tarif_per_kg;
                $totalBayar = (int) round($item['berat_kg'] * $hargaPerKg);

                SetoranSampah::create([
                    'kode_transaksi'    => $kode,
                    'warga_id'          => $validated['warga_id'],
                    'jenis_sampah_id'   => $jenis->id,
                    'berat_kg'          => $item['berat_kg'],
                    'harga_per_kg'      => $hargaPerKg,
                    'total_bayar'       => $totalBayar,
                    'tanggal_setoran'   => $validated['tanggal_setoran'],
                    'keterangan'        => $validated['keterangan'] ?? null,
                ]);

                $totalKeseluruhan += $totalBayar;
            }

            Warga::where('id', $validated['warga_id'])->increment('saldo_tabungan', $totalKeseluruhan);

            return $kode;
        });

        $setoran = SetoranSampah::where('kode_transaksi', $hasil)->first();

        return redirect()->route('petugas.pembelian.nota', $setoran->id);
    }

    /**
     * Cetak Nota hasil penimbangan untuk warga (satu transaksi bisa berisi banyak item).
     */
    public function nota($id)
    {
        $setoran = SetoranSampah::with('warga.user', 'jenisSampah.kategoriSampah')->findOrFail($id);

        $items = $setoran->kode_transaksi
            ? SetoranSampah::with('jenisSampah.kategoriSampah')
                ->where('kode_transaksi', $setoran->kode_transaksi)
                ->orderBy('id')
                ->get()
            : collect([$setoran]);

        $totalKeseluruhan = $items->sum('total_bayar');

        return view('petugas_lapangan.kasir.nota', compact('setoran', 'items', 'totalKeseluruhan'));
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

        $stokPerJenis = StokSampah::perJenis()->mapWithKeys(fn ($s) => [$s->jenis_id => $s->stok_kg]);

        return view('petugas_lapangan.kasir.penjualan', compact(
            'dataKategori',
            'daftarJenis',
            'riwayat',
            'totalHariIni',
            'totalBeratHariIni',
            'tanggal',
            'stokPerJenis'
        ));
    }

    /**
     * Simpan penjualan sampah ke pengepul (multi-item).
     */
    public function storePenjualan(Request $request)
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

        $errorStok = $this->cekStok($validated['items']);
        if ($errorStok) {
            return back()->withInput()->withErrors(['items' => $errorStok]);
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

        return redirect()->route('petugas.penjualan.index')
                         ->with('success', 'Penjualan sampah ke pengepul berhasil dicatat!');
    }

    /**
     * Pastikan total berat terjual untuk tiap jenis tidak melebihi stok tersedia.
     * Gabungkan permintaan per jenis terlebih dahulu agar tidak bisa diakali dengan item ganda.
     */
    private function cekStok(array $items): ?string
    {
        $permintaan = [];
        foreach ($items as $item) {
            $id = (int) $item['jenis_sampah_id'];
            $permintaan[$id] = ($permintaan[$id] ?? 0) + (float) $item['berat_kg'];
        }

        foreach ($permintaan as $id => $berat) {
            $stok = StokSampah::stokTersedia($id);
            if ($berat > $stok + 0.0001) {
                $jenis = JenisSampah::find($id);

                return "Stok sampah \"{$jenis->nama_jenis}\" tidak mencukupi: tersedia {$stok} kg, diminta {$berat} kg.";
            }
        }

        return null;
    }
}