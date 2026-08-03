<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tps;
use Illuminate\Http\Request;

class TpsController extends Controller
{
    public function index()
    {
        $dataTps = Tps::latest()->get();
        return view('admin.tps.index', compact('dataTps'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_tps'              => 'required|string|max:255',
            'lokasi_koordinat'      => 'nullable|string|max:255',
            'kapasitas_maksimal_m3' => 'required|numeric|min:0',
        ]);

        Tps::create($validated);

        return redirect()->route('admin.tps.index')
                         ->with('success', 'Data TPS berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $tps = Tps::findOrFail($id);

        $validated = $request->validate([
            'nama_tps'              => 'required|string|max:255',
            'lokasi_koordinat'      => 'nullable|string|max:255',
            'kapasitas_maksimal_m3' => 'required|numeric|min:0',
        ]);

        $tps->update($validated);

        return redirect()->route('admin.tps.index')
                         ->with('success', 'Data TPS berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $tps = Tps::findOrFail($id);
        $tps->delete();

        return redirect()->route('admin.tps.index')
                         ->with('success', 'Data TPS berhasil dihapus!');
    }
}