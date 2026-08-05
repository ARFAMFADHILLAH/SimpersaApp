<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Gabungkan role "supir" dan "pengangkut" menjadi satu role "petugas_lapangan":
     * - Pindahkan semua user ber-role supir/pengangkut ke petugas_lapangan
     * - Hapus role supir dan pengangkut
     */
    public function up(): void
    {
        $petugasRoleId = DB::table('roles')->where('name', 'petugas_lapangan')->value('id');
        if (!$petugasRoleId) {
            return;
        }

        foreach (['supir', 'pengangkut'] as $legacyName) {
            $legacyId = DB::table('roles')->where('name', $legacyName)->value('id');
            if ($legacyId) {
                DB::table('users')->where('role_id', $legacyId)->update(['role_id' => $petugasRoleId]);
                DB::table('roles')->where('id', $legacyId)->delete();
            }
        }
    }

    public function down(): void
    {
        $petugasRoleId = DB::table('roles')->where('name', 'petugas_lapangan')->value('id');
        if (!$petugasRoleId) {
            return;
        }

        foreach (['supir', 'pengangkut'] as $legacyName) {
            if (!DB::table('roles')->where('name', $legacyName)->exists()) {
                DB::table('roles')->insert([
                    'name' => $legacyName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $supirId = DB::table('roles')->where('name', 'supir')->value('id');
        DB::table('users')->where('role_id', $petugasRoleId)->update(['role_id' => $supirId]);
    }
};