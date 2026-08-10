<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriSampah;
use Illuminate\Http\Request;

class KategoriSampahController extends Controller
{
    public function index()
    {
        $dataKategori = KategoriSampah::withCount('jenisSampah')->latest()->get();

        return view('admin.kategori_sampah.index', compact('dataKategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_sampah,nama_kategori',
            'keterangan'    => 'nullable|string',
        ]);

        KategoriSampah::create($validated);

        return redirect()->route('admin.kategori-sampah.index')
                         ->with('success', 'Kategori sampah berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriSampah::findOrFail($id);

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_sampah,nama_kategori,' . $id,
            'keterangan'    => 'nullable|string',
        ]);

        $kategori->update($validated);

        return redirect()->route('admin.kategori-sampah.index')
                         ->with('success', 'Kategori sampah berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kategori = KategoriSampah::findOrFail($id);

        if ($kategori->jenisSampah()->exists()) {
            return redirect()->route('admin.kategori-sampah.index')
                             ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki jenis sampah.');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori-sampah.index')
                         ->with('success', 'Kategori sampah berhasil dihapus!');
    }
}