<?php

namespace App\Http\Controllers;

use App\Models\Penggajian;
use App\Models\User;
use Illuminate\Http\Request;

class PenggajianController extends Controller
{
    public function index()
    {
        $dataGaji = Penggajian::with('petugas')->orderBy('bulan_gaji', 'desc')->get();

        // Ambil data user yang memiliki akses petugas/bukan pelanggan untuk form dropdown
        $rolePelanggan = \DB::table('roles')->where('name', 'pelanggan')->first();
        $dataPetugas = User::where('role_id', '!=', $rolePelanggan->id)->get();

        return view('admin.penggajian.index', compact('dataGaji', 'dataPetugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'petugas_id' => 'required',
            'bulan_gaji' => 'required',
            'gaji_pokok' => 'required|numeric|min:0',
            'insentif_lembur' => 'required|numeric|min:0',
            'potongan' => 'required|numeric|min:0',
        ]);

        $totalBersih = $request->gaji_pokok + $request->insentif_lembur - $request->potongan;

        // Cek double input di bulan yang sama
        $cek = Penggajian::where('petugas_id', $request->petugas_id)->where('bulan_gaji', $request->bulan_gaji)->exists();
        if ($cek) {
            return redirect()->back()->with('error', 'Gaji petugas pada bulan tersebut sudah pernah diinput!');
        }

        Penggajian::create(array_merge($request->all(), [
            'total_gaji_bersih' => $totalBersih,
            'status_pembayaran' => 'Dibayar'
        ]));

        return redirect()->route('penggajian.index')->with('success', 'Slip gaji petugas berhasil diterbitkan!');
    }
}
