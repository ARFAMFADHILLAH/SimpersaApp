<?php

namespace App\Http\Controllers\Petugas_lapangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Ambil riwayat laporan kendala yang dibuat oleh petugas ini
        $riwayatLaporan = DB::table('laporan_kendalas') // Sesuaikan nama tabel jika berbeda
            ->where('petugas_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('petugas_lapangan.laporan', compact('riwayatLaporan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_kendala' => 'required|string|max:50',
            'deskripsi' => 'required|string',
            'lokasi' => 'nullable|string'
        ]);

        // Simpan kendala baru ke database
        DB::table('laporan_kendalas')->insert([
            'petugas_id' => auth()->id(),
            'tipe_kendala' => $request->tipe_kendala,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('petugas.laporan.index')->with('success', 'Laporan kendala berhasil dikirim ke Admin!');
    }
}
