<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\User;
use App\Models\Rute;
use App\Models\Wilayah;
use App\Models\Pengangkutan;
use App\Models\Iuran;
use Illuminate\Support\Facades\Hash;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::with('user', 'rute', 'wilayah')->paginate(10);
        return view('administrasi.pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        $rutes = Rute::all();
        $wilayah = Wilayah::all();
        return view('administrasi.pelanggan.create', compact('rutes', 'wilayah'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'required|string|max:20',
            'alamat_lengkap' => 'required|string',
            'rute_id' => 'required|exists:rute,id',
            'wilayah_pelayanan_id' => 'required|exists:wilayah_pelayanan,id',
        ]);

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make('password123'),
            'role_id' => \DB::table('roles')->where('name', 'pelanggan')->value('id'),
            'status' => 'aktif',
        ]);

        $lastNo = Pelanggan::max('id') ?? 0;
        $noPelanggan = 'PLG-' . str_pad($lastNo + 1, 5, '0', STR_PAD_LEFT);

        Pelanggan::create([
            'user_id' => $user->id,
            'rute_id' => $request->rute_id,
            'wilayah_pelayanan_id' => $request->wilayah_pelayanan_id,
            'no_pelanggan' => $noPelanggan,
            'no_hp' => $request->no_hp,
            'alamat_lengkap' => $request->alamat_lengkap,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('administrasi.pelanggan.index')
            ->with('success', "Pelanggan berhasil didaftarkan. No: {$noPelanggan}");
    }

    public function show($id)
    {
        $pelanggan = Pelanggan::with('user', 'rute', 'wilayah')->findOrFail($id);
        $riwayatPengangkutan = Pengangkutan::where('pelanggan_id', $id)
            ->with('armada', 'jenisSampah')
            ->latest('tanggal_tugas')
            ->paginate(10);
        $riwayatPembayaran = Iuran::where('pelanggan_id', $id)
            ->latest('bulan_tagihan')
            ->paginate(10);
        return view('administrasi.pelanggan.show', compact('pelanggan', 'riwayatPengangkutan', 'riwayatPembayaran'));
    }
}
