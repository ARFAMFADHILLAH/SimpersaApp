<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WargaController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('q', '');

        $query = Warga::with(['user'])->latest();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('no_warga', 'like', "%{$keyword}%")
                  ->orWhere('no_hp', 'like', "%{$keyword}%")
                  ->orWhere('alamat_lengkap', 'like', "%{$keyword}%")
                  ->orWhereHas('user', function ($u) use ($keyword) {
                      $u->where('name', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");
                  });
            });
        }

        $dataWarga = $query->get();

        return view('admin.warga.index', compact('dataWarga', 'keyword'));
    }

    public function create()
    {
        return view('admin.warga.create');
    }

    public function show(int $id)
    {
        $warga = Warga::with('user')->findOrFail($id);

        return view('admin.warga.show', compact('warga'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'no_hp' => 'required|string|max:20',
            'alamat_lengkap' => 'required|string',
        ]);

        DB::transaction(function () use ($request) {
            // 2. Buat User Baru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password123'),
                'role_id' => DB::table('roles')->where('name', 'warga')->value('id'),
                'status' => 'aktif',
            ]);

            // 3. Generate No Warga (Format: WRG-YYYYMM-0001)
            $bulanTahun = date('Ym');
            $countWarga = Warga::where('created_at', '>=', now()->startOfMonth(), 'and')
                ->where('created_at', '<=', now()->endOfMonth(), 'and')
                ->count() + 1;
            $noWarga = 'WRG-'.$bulanTahun.'-'.str_pad($countWarga, 4, '0', STR_PAD_LEFT);

            // 4. Simpan Data Warga
            Warga::create([
                'user_id' => $user->id,
                'no_warga' => $noWarga,
                'no_hp' => $request->no_hp,
                'alamat_lengkap' => $request->alamat_lengkap,
            ]);
        });

        return redirect()->back()->with('success', 'Warga berhasil didaftarkan!');
    }

    /**
     * Tampilkan form edit warga
     */
    public function edit(int $id)
    {
        // Ambil data warga beserta relasi user
        $warga = Warga::with('user')->findOrFail($id);

        return view('admin.warga.edit', compact('warga'));
    }

    /**
     * Simpan perubahan data warga
     */
    public function update(Request $request, int $id)
    {
        $warga = Warga::findOrFail($id);

        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$warga->user_id,
            'no_hp' => 'required|string|max:20',
            'alamat_lengkap' => 'required|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        // Transaction
        DB::transaction(function () use ($request, $warga) {
            // 1. Update data User
            $warga->user->update([
                'name' => $request->name,
                'email' => $request->email,
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
                'no_hp' => $request->no_hp,
                'alamat_lengkap' => $request->alamat_lengkap,
            ]);
        });

        return redirect()->route('admin.warga.index')
            ->with('success', 'Data warga berhasil diperbarui!');
    }

    public function destroy(int $id)
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
