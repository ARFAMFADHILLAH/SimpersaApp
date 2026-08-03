<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 1. Simpan User Baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
            'status' => 'aktif',
        ]);

        // 2. Jika pendaftar memilih Role Pelanggan
        // Otomatis buatkan baris di tabel pelanggan agar tidak 404 saat membuka dashboard
        $rolePelangganId = DB::table('roles')->where('name', 'pelanggan')->value('id');
        if ($rolePelangganId && (int) $request->role_id === (int) $rolePelangganId) {
            DB::table('pelanggan')->insert([
                'user_id' => $user->id,
                'no_pelanggan' => 'PLG-' . date('Ymd') . '-' . sprintf('%03d', $user->id),
                'no_hp' => '-',
                'alamat_lengkap' => 'Alamat belum dikonfigurasi',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}