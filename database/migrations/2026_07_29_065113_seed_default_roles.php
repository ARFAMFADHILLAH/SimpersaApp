<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $roles = ['administrator', 'manajer', 'petugas_lapangan', 'petugas_administrasi', 'bendahara', 'warga'];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role],
                ['name' => $role, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    public function down(): void
    {
        DB::table('roles')->whereIn('name', [
            'administrator', 'manajer', 'petugas_lapangan',
            'petugas_administrasi', 'bendahara', 'warga'
        ])->delete();
    }
};
