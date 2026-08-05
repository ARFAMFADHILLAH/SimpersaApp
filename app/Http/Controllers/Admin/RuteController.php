<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rute;
use App\Models\Warga;
use Illuminate\Http\Request;

class RuteController extends Controller
{
    public function index()
    {
        $dataRute = Rute::withCount('warga')->get();
        return view('admin.rute.index', compact('dataRute'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_rute' => 'required|string|max:255',
            'hari_angkut' => 'required|string',
            'titik_koordinat_pusat' => 'nullable|string|max:255',
        ]);

        Rute::create($request->all());

        return redirect()->route('admin.rute.index')->with('success', 'Rute zonasi baru berhasil ditambahkan!');
    }

    // Modul 12: Visualisasi Peta Digital Wilayah
    public function peta($id)
    {
        $rute = Rute::findOrFail($id);

        // Ambil semua warga yang terdaftar di rute ini yang memiliki data koordinat
        $wargaPeta = Warga::with('user')
            ->where('rute_id', $id)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('admin.rute.peta', compact('rute', 'wargaPeta'));
    }
}
