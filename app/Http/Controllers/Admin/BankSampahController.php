<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SetoranSampah;
use App\Models\Warga;
use App\Models\Mitra;
use App\Models\JenisSampah;
use Illuminate\Http\Request;

class BankSampahController extends Controller
{
    public function index()
    {
        $setoran = SetoranSampah::with('warga.user', 'mitra', 'jenisSampah')->latest()->get();
        $totalKg = $setoran->sum('berat_kg');
        $totalBayar = $setoran->sum('total_bayar');
        $totalSetoran = $setoran->count();
        $wargaList = Warga::with('user')->orderBy('no_warga')->get();
        $mitra = Mitra::current();
        $jenisList = JenisSampah::latest()->get();

        return view('admin.bank_sampah.index', compact(
            'setoran', 'totalKg', 'totalBayar', 'totalSetoran', 'wargaList', 'mitra', 'jenisList'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warga_id'        => 'required|exists:warga,id',
            'jenis_sampah_id' => 'required|exists:jenis_sampah_dan_tarif,id',
            'berat_kg'        => 'required|numeric|min:0.01',
            'tanggal_setoran' => 'required|date',
            'keterangan'      => 'nullable|string|max:255',
        ]);

        $jenis = JenisSampah::findOrFail($validated['jenis_sampah_id']);
        $berat = (float) $validated['berat_kg'];
        $harga = (float) $jenis->tarif_per_kg;
        $total = round($berat * $harga, 2);
        $mitra = Mitra::current();

        SetoranSampah::create([
            'warga_id'        => $validated['warga_id'],
            'mitra_id'        => $mitra->id,
            'jenis_sampah_id' => $validated['jenis_sampah_id'],
            'berat_kg'        => $berat,
            'harga_per_kg'    => $harga,
            'total_bayar'     => $total,
            'tanggal_setoran' => $validated['tanggal_setoran'],
            'keterangan'      => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('admin.bank-sampah.index')
            ->with('success', 'Setoran berhasil dicatat! Warga dibayar tunai oleh ' . $mitra->nama_mitra . ' sebesar Rp ' . number_format($total, 0, ',', '.') . '.');
    }

    public function destroy($id)
    {
        SetoranSampah::findOrFail($id)->delete();

        return redirect()->route('admin.bank-sampah.index')
            ->with('success', 'Setoran berhasil dihapus (koreksi data).');
    }
}