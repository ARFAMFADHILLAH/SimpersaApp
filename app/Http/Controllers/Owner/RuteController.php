<?php

namespace App\Http\Controllers\Owner;

use App\Models\Rute;
use App\Models\Warga;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RuteController extends Controller
{
    public function index()
    {
        $dataRute = Rute::withCount('warga')->get();
        return view('owner.rute.index', compact('dataRute'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_rute' => 'required|string|max:255',
            'hari_angkut' => 'required|string',
        ]);

        Rute::create($request->all());

        return redirect()->route('owner.rute.index')->with('success', 'Rute zonasi baru berhasil ditambahkan!');
    }

    //Visualisasi Peta Digital Wilayah
    public function peta($id)
    {
        $rute = Rute::findOrFail($id);

        // Ambil semua warga yang terdaftar di rute ini yang memiliki data koordinat
        $wargaPeta = Warga::with('user')
            ->where('rute_id', $id)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('owner.rute.peta', compact('rute', 'wargaPeta'));
    }
}
