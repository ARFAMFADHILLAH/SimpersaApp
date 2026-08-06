<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warga;
use App\Models\Armada;

class MasterController extends Controller
{
    public function index()
    {
        $warga = Warga::with('user', 'rute', 'wilayah')->paginate(10);
        $armada = Armada::all();
        return view('admin.master.index', compact('warga', 'armada'));
    }

    public function editWarga($id)
    {
        $warga = Warga::with('user')->findOrFail($id);
        return view('admin.master.edit-warga', compact('warga'));
    }

    public function updateWarga(Request $request, $id)
    {
        $warga = Warga::findOrFail($id);
        $warga->update($request->only(['no_hp', 'alamat_lengkap', 'latitude', 'longitude']));
        return redirect()->route('admin.master.index')->with('success', 'Data warga berhasil diperbarui.');
    }

    public function editArmada($id)
    {
        $armada = Armada::findOrFail($id);
        return view('admin.master.edit-armada', compact('armada'));
    }

    public function updateArmada(Request $request, $id)
    {
        $armada = Armada::findOrFail($id);
        $armada->update($request->only(['nama_kendaraan', 'nomor_plat', 'jenis_kendaraan', 'status_kondisi']));
        return redirect()->route('admin.master.index')->with('success', 'Data armada berhasil diperbarui.');
    }
}
