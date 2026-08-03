<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
Use App\Models\Armada;
use Illuminate\Http\Request;

class ArmadaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $armadas = Armada::latest()->paginate(10);
        return view('admin.armada.index', compact('armadas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.armada.create');
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $validated = $request->validate([
        'nama_kendaraan'  => 'required|string|max:255',
        'nomor_plat'      => 'required|string|max:20|unique:armada,nomor_plat',
        'jenis_kendaraan' => 'required|string',
        'kapasitas_m3'    => 'nullable|numeric|min:0|max:99999999.99',
        'status_kondisi'  => 'required|in:aktif,rusak,servis',
    ]);

    Armada::create($validated);

    return redirect()->route('admin.armada.index')->with('success', 'Armada berhasil ditambahkan!');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Armada $armada)
    {
        return view('admin.armada.edit', compact('armada'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Armada $armada)
{
    $validated = $request->validate([
        'nama_kendaraan'  => 'required|string|max:255',
        'nomor_plat'      => 'required|string|max:20|unique:armada,nomor_plat,' . $armada->id,
        'jenis_kendaraan' => 'required|string',
        'kapasitas_m3'    => 'nullable|numeric|min:0|max:99999999.99',
        'status_kondisi'  => 'required|in:aktif,rusak,servis',
    ]);

    // Update menggunakan data yang sudah lolos validasi
    $armada->update($validated);

    return redirect()->route('admin.armada.index')->with('success', 'Data armada berhasil diperbarui!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Armada $armada)
    {
        $armada->delete();
        return redirect()->route('admin.armada.index')->with('success', 'Armada berhasil dihapus!');
    }
}
