<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    public function index()
    {
        // Load relasi user dan wilayahPelayanan
        $dataPelanggan = Pelanggan::with(['user', 'wilayahPelayanan'])->latest()->get();
        
        // Ambil semua data wilayah untuk isi dropdown di form registrasi
        $wilayahs = Wilayah::all();

        return view('manager.pelanggan.index', compact('dataPelanggan', 'wilayahs'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|string|email|max:255|unique:users,email',
            'no_hp'                => 'required|string|max:20',
            'wilayah_pelayanan_id' => 'required|exists:wilayah_pelayanan,id', // Validasi FK
            'alamat_lengkap'       => 'required|string',
            'latitude'             => 'nullable|string|max:255',
            'longitude'            => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            // 2. Buat User Baru
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make('password123'),
                'role_id'  => 6, // Disarankan berupa integer (atau string tergantung struktur tipe data kolom)
                'status'   => 'aktif',
            ]);

            // 3. Generate No Pelanggan (Format: PLG-YYYYMM-0001)
            $bulanTahun = date('Ym');
            $countPelanggan = Pelanggan::whereYear('created_at', date('Y'))
                                       ->whereMonth('created_at', date('m'))
                                       ->count() + 1;
            $noPelanggan = 'PLG-' . $bulanTahun . '-' . str_pad($countPelanggan, 4, '0', STR_PAD_LEFT);

            // 4. Simpan Data Pelanggan
            Pelanggan::create([
                'user_id'              => $user->id,
                'no_pelanggan'         => $noPelanggan,
                'no_hp'                => $request->no_hp,
                'wilayah_pelayanan_id' => $request->wilayah_pelayanan_id, 
                'alamat_lengkap'       => $request->alamat_lengkap,
                'latitude'             => $request->latitude,
                'longitude'            => $request->longitude,
            ]);
        });

        return redirect()->back()->with('success', 'Pelanggan berhasil didaftarkan! Password bawaan: password123');
    }

    /**
     * Tampilkan form edit pelanggan
     */
    public function edit($id)
    {
        // Ambil data pelanggan beserta relasi user dan wilayah
        $pelanggan = Pelanggan::with('user')->findOrFail($id);
        
        // Ambil semua daftar wilayah untuk dropdown edit
        $wilayahList = Wilayah::orderBy('nama_wilayah','asc')->get();

        return view('manager.pelanggan.edit', compact('pelanggan', 'wilayahList'));
    }

    /**
     * Simpan perubahan data pelanggan
     */
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        // Validation
        $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|unique:users,email,' . $pelanggan->user_id,
            'no_hp'                => 'required|string|max:20',
            'wilayah_pelayanan_id' => 'required|exists:wilayah_pelayanan,id', 
            'alamat_lengkap'       => 'required|string',
            'latitude'             => 'nullable|numeric',
            'longitude'            => 'nullable|numeric',
            'status'               => 'required|in:aktif,nonaktif',
        ]);

        // Transaction
        DB::transaction(function () use ($request, $pelanggan) {
            // 1. Update data User
            $pelanggan->user->update([
                'name'   => $request->name,
                'email'  => $request->email,
                'status' => $request->status,
            ]);

            // Opsional: Update password jika diisi
            if ($request->filled('password')) {
                $pelanggan->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            // 2. Update data Pelanggan
            $pelanggan->update([
                'no_hp'                => $request->no_hp,
                'wilayah_pelayanan_id' => $request->wilayah_pelayanan_id, // Update FK
                'alamat_lengkap'       => $request->alamat_lengkap,
                'latitude'             => $request->latitude,
                'longitude'            => $request->longitude,
            ]);
        });

        return redirect()->route('manager.pelanggan.index')
                         ->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        DB::transaction(function () use ($pelanggan) {
            if ($pelanggan->user) {
                $pelanggan->user->delete();
            }
            $pelanggan->delete();
        });

        return redirect()->back()->with('success', 'Data pelanggan berhasil dihapus!');
    }
}