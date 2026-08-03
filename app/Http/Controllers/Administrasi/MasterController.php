<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Tps;
use App\Models\Armada;

class MasterController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::with('user', 'rute', 'wilayah')->paginate(10);
        $tpsList = Tps::all();
        $armada = Armada::all();
        return view('administrasi.master.index', compact('pelanggan', 'tpsList', 'armada'));
    }

    public function editPelanggan($id)
    {
        $pelanggan = Pelanggan::with('user')->findOrFail($id);
        return view('administrasi.master.edit-pelanggan', compact('pelanggan'));
    }

    public function updatePelanggan(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update($request->only(['no_hp', 'alamat_lengkap', 'latitude', 'longitude']));
        return redirect()->route('administrasi.master.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function editTps($id)
    {
        $tps = Tps::findOrFail($id);
        return view('administrasi.master.edit-tps', compact('tps'));
    }

    public function updateTps(Request $request, $id)
    {
        $tps = Tps::findOrFail($id);
        $tps->update($request->only(['nama_tps', 'lokasi_koordinat', 'kapasitas_maksimal_m3']));
        return redirect()->route('administrasi.master.index')->with('success', 'Data TPS berhasil diperbarui.');
    }

    public function editArmada($id)
    {
        $armada = Armada::findOrFail($id);
        return view('administrasi.master.edit-armada', compact('armada'));
    }

    public function updateArmada(Request $request, $id)
    {
        $armada = Armada::findOrFail($id);
        $armada->update($request->only(['nama_kendaraan', 'nomor_plat', 'jenis_kendaraan', 'status_kondisi']));
        return redirect()->route('administrasi.master.index')->with('success', 'Data armada berhasil diperbarui.');
    }
}
