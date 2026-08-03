<?php

namespace App\Http\Controllers\Manager;

use App\Models\Rute;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RuteController extends Controller
{
    public function index()
    {
        $dataRute = Rute::withCount('pelanggan')->get();
        return view('manager.rute.index', compact('dataRute'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_rute' => 'required|string|max:255',
            'hari_angkut' => 'required|string',
        ]);

        Rute::create($request->all());

        return redirect()->route('manager.rute.index')->with('success', 'Rute zonasi baru berhasil ditambahkan!');
    }

    //Visualisasi Peta Digital Wilayah
    public function peta($id)
    {
        $rute = Rute::findOrFail($id);

        // Ambil semua pelanggan yang terdaftar di rute ini yang memiliki data koordinat
        $pelangganPeta = Pelanggan::with('user')
            ->where('rute_id', $id)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('manager.rute.peta', compact('rute', 'pelangganPeta'));
    }
}
