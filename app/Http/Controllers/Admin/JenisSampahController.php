<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSampah;
use App\Models\KategoriSampah;
use Illuminate\Http\Request;

class JenisSampahController extends Controller
{
    public function index()
    {
        $dataSampah = JenisSampah::with('kategoriSampah')->latest()->get();
        $dataKategori = KategoriSampah::orderBy('nama_kategori')->get();
        return view('admin.jenis_sampah.index', compact('dataSampah', 'dataKategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jenis'         => 'required|string|max:255|unique:jenis_sampah_dan_tarif,nama_jenis',
            'kategori_sampah_id' => 'required|exists:kategori_sampah,id',
            'tarif_per_kg'       => 'required|numeric|min:0',
            'tarif_jual_per_kg'  => 'required|numeric|min:0',
        ]);

        JenisSampah::create($validated);

        return redirect()->route('admin.jenis-sampah.index')
                         ->with('success', 'Jenis sampah berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $jenisSampah = JenisSampah::findOrFail($id);

        $validated = $request->validate([
            'nama_jenis'         => 'required|string|max:255|unique:jenis_sampah_dan_tarif,nama_jenis,' . $id,
            'kategori_sampah_id' => 'required|exists:kategori_sampah,id',
            'tarif_per_kg'       => 'required|numeric|min:0',
            'tarif_jual_per_kg'  => 'required|numeric|min:0',
        ]);

        $jenisSampah->update($validated);

        return redirect()->route('admin.jenis-sampah.index')
                         ->with('success', 'Jenis sampah berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $jenisSampah = JenisSampah::findOrFail($id);
        $jenisSampah->delete();

        return redirect()->route('admin.jenis-sampah.index')
                         ->with('success', 'Jenis sampah berhasil dihapus!');
    }
}