<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Iuran;
use App\Models\Warga;
use App\Models\PengaturanIuran;
use Illuminate\Http\Request;

class IuranController extends Controller
{
    public function index()
    {
        $dataIuran = Iuran::with('warga.user')->latest()->get();

        // Ambil pengaturan iuran (jika belum ada, buat default)
        $pengaturan = PengaturanIuran::firstOrCreate([], [
            'tarif_dasar_bulanan' => 20000,
            'persentase_denda_per_bulan' => 5,
            'nominal_denda_flat' => 5000,
            'tgl_jatuh_tempo' => 10,
        ]);

        return view('admin.iuran.index', compact('dataIuran', 'pengaturan'));
    }

    // Update Parameter Tarif & Denda
    public function updatePengaturan(Request $request)
    {
        $validated = $request->validate([
            'tarif_dasar_bulanan'        => 'required|numeric|min:0',
            'persentase_denda_per_bulan' => 'required|numeric|min:0|max:100',
            'nominal_denda_flat'         => 'required|numeric|min:0',
            'tgl_jatuh_tempo'            => 'required|numeric|min:1|max:31',
        ]);

        $pengaturan = PengaturanIuran::firstOrCreate([
            'tarif_dasar_bulanan' => 20000,
            'persentase_denda_per_bulan' => 5,
            'nominal_denda_flat' => 5000,
            'tgl_jatuh_tempo' => 10,
        ]);
        $pengaturan->update($validated);

        return redirect()->back()->with('success', 'Parameter tarif & denda berhasil diperbarui!');
    }

    // Generate Tagihan Bulan Ini
    public function generate()
    {
        $pengaturan = PengaturanIuran::first();
        $bulanSekarang = date('Y-m'); // Format: YYYY-MM

        $warga = Warga::all();

        $generatedCount = 0;
        foreach ($warga as $p) {
            // Cek apakah tagihan bulan ini sudah ada
            $exists = Iuran::where('warga_id', $p->id)
                ->where('bulan_tagihan', $bulanSekarang)
                ->exists();

            if (!$exists) {
                Iuran::create([
                    'warga_id'      => $p->id,
                    'bulan_tagihan'     => $bulanSekarang,
                    'jumlah_tagihan'    => $pengaturan->tarif_dasar_bulanan,
                    'denda'             => 0,
                    'status_pembayaran' => 'Belum Bayar',
                ]);
                $generatedCount++;
            }
        }

        return redirect()->back()->with('success', "Berhasil me-generate {$generatedCount} tagihan untuk bulan {$bulanSekarang}.");
    }

    // Konfirmasi Pembayaran Lunas (Hitung Denda Jika Terlambat)
    public function bayar(Request $request, $id)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:Tunai,Non-Tunai',
        ]);

        $iuran = Iuran::findOrFail($id);
        $pengaturan = PengaturanIuran::first();

        // Cek keterlambatan pembayaran berdasarkan tanggal jatuh tempo
        $now = now();
        $tglJatuhTempo = now()->setDate($now->year, $now->month, $pengaturan->tgl_jatuh_tempo);

        $denda = 0;
        if ($now->greaterThan($tglJatuhTempo)) {
            // Menggunakan denda flat dari pengaturan
            $denda = $pengaturan->nominal_denda_flat;
        }

        $iuran->update([
            'denda'             => $denda,
            'status_pembayaran' => 'Lunas',
            'tanggal_bayar'     => now()->toDateString(),
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        return redirect()->back()->with('success', 'Pembayaran iuran berhasil dikonfirmasi!');
    }
}