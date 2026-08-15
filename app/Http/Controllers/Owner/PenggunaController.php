<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    /**
     * Monitoring pengguna (read-only): admin, bendahara, petugas.
     * Warga ditampilkan terpisah di Monitoring Warga.
     */
    public function index(Request $request)
    {
        $keyword = $request->get('q', '');
        $roleFilter = $request->get('role', '');

        $users = User::with('role')->whereHas('role', function ($q) {
            $q->whereNotIn('name', ['warga']);
        });

        if ($keyword) {
            $users->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        if ($roleFilter) {
            $users->whereHas('role', function ($q) use ($roleFilter) {
                $q->where('name', $roleFilter);
            });
        }

        $users = $users->orderBy('id')->get()->map(function ($user) {
            return (object) [
                'id'        => $user->id,
                'nama'      => $user->name,
                'email'     => $user->email,
                'role'      => $user->role->name ?? '-',
                'aktif'     => $user->status === 'aktif',
                'terdaftar' => $user->created_at ? $user->created_at->format('d/m/Y') : '-',
            ];
        });

        $jumlahPerRole = [
            'admin'     => User::whereHas('role', fn ($q) => $q->whereIn('name', ['admin', 'administrator', 'administrasi']))->count(),
            'bendahara' => User::whereHas('role', fn ($q) => $q->where('name', 'bendahara'))->count(),
            'petugas'   => User::whereHas('role', fn ($q) => $q->whereIn('name', ['petugas', 'petugas_lapangan']))->count(),
            'owner'     => User::whereHas('role', fn ($q) => $q->where('name', 'owner'))->count(),
        ];

        $totalAktif = $users->where('aktif', true)->count();

        return view('owner.pengguna.index', compact('users', 'keyword', 'roleFilter', 'jumlahPerRole', 'totalAktif'));
    }
}