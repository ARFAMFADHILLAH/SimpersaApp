<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warga;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class WargaController extends Controller
{
    public function index()
    {
        // Load relasi user dan wilayahPelayanan
        $dataWarga = Warga::with(['user', 'wilayahPelayanan'])->latest()->get();
        
        // Ambil semua data wilayah untuk isi dropdown di form registrasi
        $wilayahs = Wilayah::all();

        return view('owner.warga.index', compact('dataWarga', 'wilayahs'));
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

            // 3. Generate No Warga (Format: WRG-YYYYMM-0001)
            $bulanTahun = date('Ym');
            $countWarga = Warga::whereYear('created_at', date('Y'))
                                       ->whereMonth('created_at', date('m'))
                                       ->count() + 1;
            $noWarga = 'WRG-' . $bulanTahun . '-' . str_pad($countWarga, 4, '0', STR_PAD_LEFT);

            // 4. Simpan Data Warga
            Warga::create([
                'user_id'              => $user->id,
                'no_warga'         => $noWarga,
                'no_hp'                => $request->no_hp,
                'wilayah_pelayanan_id' => $request->wilayah_pelayanan_id, 
                'alamat_lengkap'       => $request->alamat_lengkap,
                'latitude'             => $request->latitude,
                'longitude'            => $request->longitude,
            ]);
        });

        return redirect()->back()->with('success', 'Warga berhasil didaftarkan! Password bawaan: password123');
    }

    /**
     * Tampilkan form edit warga
     */
    public function edit($id)
    {
        // Ambil data warga beserta relasi user dan wilayah
        $warga = Warga::with('user')->findOrFail($id);
        
        // Ambil semua daftar wilayah untuk dropdown edit
        $wilayahList = Wilayah::orderBy('nama_wilayah','asc')->get();

        return view('owner.warga.edit', compact('warga', 'wilayahList'));
    }

    /**
     * Simpan perubahan data warga
     */
    public function update(Request $request, $id)
    {
        $warga = Warga::findOrFail($id);

        // Validation
        $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|unique:users,email,' . $warga->user_id,
            'no_hp'                => 'required|string|max:20',
            'wilayah_pelayanan_id' => 'required|exists:wilayah_pelayanan,id', 
            'alamat_lengkap'       => 'required|string',
            'latitude'             => 'nullable|numeric',
            'longitude'            => 'nullable|numeric',
            'status'               => 'required|in:aktif,nonaktif',
        ]);

        // Transaction
        DB::transaction(function () use ($request, $warga) {
            // 1. Update data User
            $warga->user->update([
                'name'   => $request->name,
                'email'  => $request->email,
                'status' => $request->status,
            ]);

            // Opsional: Update password jika diisi
            if ($request->filled('password')) {
                $warga->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            // 2. Update data Warga
            $warga->update([
                'no_hp'                => $request->no_hp,
                'wilayah_pelayanan_id' => $request->wilayah_pelayanan_id, // Update FK
                'alamat_lengkap'       => $request->alamat_lengkap,
                'latitude'             => $request->latitude,
                'longitude'            => $request->longitude,
            ]);
        });

        return redirect()->route('owner.warga.index')
                         ->with('success', 'Data warga berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $warga = Warga::findOrFail($id);

        DB::transaction(function () use ($warga) {
            if ($warga->user) {
                $warga->user->delete();
            }
            $warga->delete();
        });

        return redirect()->back()->with('success', 'Data warga berhasil dihapus!');
    }
}