<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
    $request->authenticate();
    $request->session()->regenerate();

    $user = \Illuminate\Support\Facades\Auth::user();

    // Deteksi role dari database
    $roleName = isset($user->role->name) ? $user->role->name : $user->role;
    $roleName = strtolower(trim($roleName));

    // JANGAN gunakan redirect()->intended() bawaan Breeze jika ia terus memaksa ke /dashboard
    if ($roleName === 'administrator' || $roleName === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($roleName === 'manajer' || $roleName === 'manager') {
        return redirect()->route('manager.dashboard');
    }

    if ($roleName === 'bendahara') {
        return redirect()->route('bendahara.dashboard');
    }

    if ($roleName === 'petugas_administrasi' || $roleName === 'administrasi') {
        return redirect()->route('administrasi.dashboard');
    }

       // PENGECEKAN UNTUK PELANGGAN ---
    if ($roleName === 'pelanggan' || $roleName === 'warga') {
        return redirect()->route('pelanggan.dashboard');
    }

        // --- TAMBAHKAN PENGECEKAN UNTUK PETUGAS (OPSIONAL) ---
    if ($roleName === 'petugas' || $roleName === 'petugas_lapangan') {
        return redirect()->route('petugas.dashboard');
    }

    // Jika tidak terdeteksi, buang ke halaman welcome/utama
    return redirect()->to('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
