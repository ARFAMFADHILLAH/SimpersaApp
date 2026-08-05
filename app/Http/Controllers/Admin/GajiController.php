<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanGaji;
use Illuminate\Http\Request;

class GajiController extends Controller
{
    public function index()
    {
        $pengaturan = PengaturanGaji::ambil();

        return view('admin.gaji.index', compact('pengaturan'));
    }

    public function updatePengaturan(Request $request)
    {
        $validated = $request->validate([
            'gaji_pokok'              => 'required|numeric|min:0',
            'insentif_per_hadir'      => 'required|numeric|min:0',
            'bonus_amount'            => 'required|numeric|min:0',
            'minimal_hadir_bonus'     => 'required|numeric|min:0',
            'potongan_alpha_per_hari' => 'required|numeric|min:0',
            'potongan_izin_per_hari'  => 'required|numeric|min:0',
        ]);

        $pengaturan = PengaturanGaji::ambil();
        $pengaturan->update($validated);

        return redirect()->back()->with('success', 'Parameter penggajian berhasil diperbarui!');
    }
}
