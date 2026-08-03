<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class WilayahPelayananController extends Controller
{
    /**
     * Tampilkan daftar wilayah pelayanan & form tambah
     */
    public function index()
    {
        // Ambil data wilayah dengan jumlah pelanggannya
        $wilayahs = Wilayah::withCount('pelanggan')->latest()->get();

        return view('admin.wilayah.index', compact('wilayahs'));
    }

    /**
     * Simpan wilayah pelayanan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_wilayah' => 'required|string|max:255|unique:wilayah_pelayanan,nama_wilayah',
            'cakupan_area' => 'nullable|string',
        ], [
            'nama_wilayah.required' => 'Nama wilayah wajib diisi.',
            'nama_wilayah.unique' => 'Nama wilayah ini sudah ada.',
        ]);

        Wilayah::create([
            'nama_wilayah' => $request->nama_wilayah,
            'cakupan_area' => $request->cakupan_area,
        ]);

        return redirect()->back()->with('success', 'Wilayah pelayanan berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit wilayah
     */
    public function edit($id)
    {
        $wilayah = Wilayah::findOrFail($id);

        return view('admin.wilayah.edit', compact('wilayah'));
    }

    /**
     * Update data wilayah
     */
    public function update(Request $request, $id)
    {
        $wilayah = Wilayah::findOrFail($id);

        $request->validate([
            'nama_wilayah' => 'required|string|max:255|unique:wilayah_pelayanan,nama_wilayah,' . $id,
            'cakupan_area' => 'nullable|string',
        ]);

        $wilayah->update([
            'nama_wilayah' => $request->nama_wilayah,
            'cakupan_area' => $request->cakupan_area,
        ]);

        return redirect()->route('admin.wilayah.index')
                         ->with('success', 'Data wilayah pelayanan berhasil diperbarui!');
    }

    /**
     * Hapus data wilayah
     */
    public function destroy($id)
    {
        $wilayah = Wilayah::withCount('pelanggan')->findOrFail($id);

        // Mencegah hapus jika wilayah masih digunakan oleh pelanggan
        if ($wilayah->pelanggan_count > 0) {
            return redirect()->back()->with('error', 'Gagal menghapus! Wilayah ini masih terikat dengan ' . $wilayah->pelanggan_count . ' data pelanggan.');
        }

        $wilayah->delete();

        return redirect()->back()->with('success', 'Wilayah pelayanan berhasil dihapus!');
    }
}