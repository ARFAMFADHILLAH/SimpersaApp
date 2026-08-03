<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index()
    {
        $dataPengeluaran = Pengeluaran::with('armada')->orderBy('tanggal_pengeluaran', 'desc')->get();
        $dataArmada = \DB::table('armada')->get();

        return view('admin.pengeluaran.index', compact('dataPengeluaran', 'dataArmada'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pengeluaran' => 'required|date',
            'kategori_biaya' => 'required',
            'jumlah_biaya' => 'required|numeric|min:0',
        ]);

        Pengeluaran::create($request->all());

        return redirect()->route('pengeluaran.index')->with('success', 'Biaya pengeluaran operasional berhasil dicatat!');
    }
}
