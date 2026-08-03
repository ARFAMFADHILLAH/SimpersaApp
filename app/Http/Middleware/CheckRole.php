<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('login');
        }

        // 2. Ambil data user yang sedang login beserta nama role-nya via DB Table
        $user = Auth::user();
        $userRole = \DB::table('roles')->where('id', $user->role_id)->first();

        // 3. Cek apakah role user saat ini ada di dalam daftar role yang diizinkan halaman tersebut
        // Menggunakan strtolower agar pengecekan nama role bersifat case-insensitive (huruf besar/kecil tidak masalah)
        if ($userRole && in_array(strtolower(trim($userRole->name)), array_map('strtolower', $roles))) {
            return $next($request);
        }

        // 4. Jika tidak punya akses, lempar ke halaman 403 (Unauthorized)
        abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
    }
}
