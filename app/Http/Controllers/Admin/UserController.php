<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('role')
            ->when($request->role_id, fn ($q) => $q->where('role_id', $request->role_id))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        // Mengambil daftar role untuk dropdown select
        $roles = DB::table('roles')->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id'  => 'required|exists:roles,id',
            'status'   => 'required|in:aktif,nonaktif',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $request->role_id,
            'status'   => $request->status,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil dibuat!');
    }

    /**
     * Tampilkan form edit pengguna
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); // Mengambil seluruh daftar role

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Simpan perubahan data pengguna
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi input
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'role_id'  => 'required|exists:roles,id',
            'status'   => 'required|in:aktif,nonaktif',
            'password' => 'nullable|string|min:8',
        ]);

        // Persiapkan data yang akan diupdate
        $dataToUpdate = [
            'name'    => $request->name,
            'email'   => $request->email,
            'role_id' => $request->role_id,
            'status'  => $request->status,
        ];

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        $user->update($dataToUpdate);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Data pengguna berhasil diperbarui!');
    }

    /**
     * Menghapus data pengguna dari database.
     */
    public function destroy(User $user)
    {
        // Proteksi 1: Mencegah user menghapus akunnya sendiri yang sedang login
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang digunakan!');
        }

        // Hapus data pengguna
        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Pengguna berhasil dihapus!');
    }
}