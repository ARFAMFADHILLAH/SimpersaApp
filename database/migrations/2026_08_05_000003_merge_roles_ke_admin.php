<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Gabungkan role "administrator"/"admin" dan "petugas_administrasi"/"administrasi"
     * menjadi satu role "admin":
     * - Buat role `admin` jika belum ada
     * - Pindahkan semua user dengan role legacy ke role `admin`
     * - Hapus role legacy (administrator, petugas_administrasi, administrasi, admin lama)
     */
    public function up(): void
    {
        if (!DB::table('roles')->where('name', 'admin')->exists()) {
            DB::table('roles')->insert([
                'name' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');

        foreach (['administrator', 'petugas_administrasi', 'administrasi'] as $legacyName) {
            $legacyId = DB::table('roles')->where('name', $legacyName)->value('id');
            if ($legacyId) {
                DB::table('users')->where('role_id', $legacyId)->update(['role_id' => $adminRoleId]);
                DB::table('roles')->where('id', $legacyId)->delete();
            }
        }
    }

    public function down(): void
    {
        $legacyNames = ['administrator', 'petugas_administrasi', 'administrasi'];
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');

        if (!$adminRoleId) {
            return;
        }

        // Kembalikan user ke role administrator (karena tidak bisa mengetahui asal user)
        foreach ($legacyNames as $legacyName) {
            if (!DB::table('roles')->where('name', $legacyName)->exists()) {
                DB::table('roles')->insert([
                    'name' => $legacyName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $administratorId = DB::table('roles')->where('name', 'administrator')->value('id');
        DB::table('users')->where('role_id', $adminRoleId)->update(['role_id' => $administratorId]);

        DB::table('roles')->where('name', 'admin')->delete();
    }
};
