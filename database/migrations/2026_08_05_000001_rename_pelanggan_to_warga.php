<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rename seluruh terminologi "pelanggan" menjadi "warga":
     * - Tabel `pelanggan` -> `warga`
     * - Kolom `no_pelanggan` -> `no_warga`
     * - FK `pelanggan_id` -> `warga_id` pada iuran, pengangkutan, pengaduan
     * - Role `pelanggan` digabung ke role `warga`
     */
    public function up(): void
    {
        // 1. Lepas FK yang menunjuk ke tabel pelanggan
        foreach (['iuran', 'pengangkutan', 'pengaduan'] as $tabel) {
            if (Schema::hasTable($tabel) && Schema::hasColumn($tabel, 'pelanggan_id')) {
                Schema::table($tabel, function (Blueprint $table) {
                    $table->dropForeign(['pelanggan_id']);
                });
            }
        }

        // 2. Rename tabel pelanggan -> warga
        Schema::rename('pelanggan', 'warga');

        // 3. Rename kolom di tabel warga
        if (Schema::hasColumn('warga', 'no_pelanggan')) {
            Schema::table('warga', function (Blueprint $table) {
                $table->renameColumn('no_pelanggan', 'no_warga');
            });
        }

        // 4. Rename kolom pelanggan_id -> warga_id pada tabel relasi
        foreach (['iuran', 'pengangkutan', 'pengaduan'] as $tabel) {
            if (Schema::hasTable($tabel) && Schema::hasColumn($tabel, 'pelanggan_id')) {
                Schema::table($tabel, function (Blueprint $table) {
                    $table->renameColumn('pelanggan_id', 'warga_id');
                });
            }
        }

        // 5. Pasang kembali FK warga_id -> warga
        foreach (['iuran', 'pengangkutan', 'pengaduan'] as $tabel) {
            if (Schema::hasTable($tabel) && Schema::hasColumn($tabel, 'warga_id')) {
                Schema::table($tabel, function (Blueprint $table) {
                    $table->foreign('warga_id')->references('id')->on('warga')->onDelete('cascade');
                });
            }
        }

        // 6. Gabungkan role: semua user ber-role pelanggan dipindah ke role warga
        $rolePelangganId = DB::table('roles')->where('name', 'pelanggan')->value('id');
        $roleWargaId = DB::table('roles')->where('name', 'warga')->value('id');

        if ($rolePelangganId) {
            if (!$roleWargaId) {
                DB::table('roles')->insert([
                    'name' => 'warga',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $roleWargaId = DB::table('roles')->where('name', 'warga')->value('id');
            }

            DB::table('users')->where('role_id', $rolePelangganId)->update(['role_id' => $roleWargaId]);

            DB::table('roles')->where('id', $rolePelangganId)->delete();
        }

        // 7. Sesuaikan akun demo agar bertema warga (email + password baru)
        $demoUser = DB::table('users')->where('email', 'pelanggan@sistemsampah.com')->first();
        if ($demoUser) {
            DB::table('users')->where('id', $demoUser->id)->update([
                'email' => 'warga@sistemsampah.com',
                'name' => 'Budi Warga',
                'password' => \Illuminate\Support\Facades\Hash::make('warga123'),
            ]);
        }
    }

    public function down(): void
    {
        // Balikkan role
        $roleWargaId = DB::table('roles')->where('name', 'warga')->value('id');
        if ($roleWargaId) {
            if (!DB::table('roles')->where('name', 'pelanggan')->exists()) {
                DB::table('roles')->insert([
                    'name' => 'pelanggan',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $rolePelangganId = DB::table('roles')->where('name', 'pelanggan')->value('id');
            DB::table('users')->where('role_id', $roleWargaId)->update(['role_id' => $rolePelangganId]);
        }

        DB::table('users')
            ->where('email', 'warga@sistemsampah.com')
            ->update(['email' => 'pelanggan@sistemsampah.com', 'name' => 'Budi Pelanggan']);

        foreach (['iuran', 'pengangkutan', 'pengaduan'] as $tabel) {
            if (Schema::hasTable($tabel) && Schema::hasColumn($tabel, 'warga_id')) {
                Schema::table($tabel, function (Blueprint $table) {
                    $table->dropForeign(['warga_id']);
                });
            }
        }

        if (Schema::hasTable('warga') && Schema::hasColumn('warga', 'no_warga')) {
            Schema::table('warga', function (Blueprint $table) {
                $table->renameColumn('no_warga', 'no_pelanggan');
            });
        }

        Schema::rename('warga', 'pelanggan');

        foreach (['iuran', 'pengangkutan', 'pengaduan'] as $tabel) {
            if (Schema::hasTable($tabel) && Schema::hasColumn($tabel, 'warga_id')) {
                Schema::table($tabel, function (Blueprint $table) {
                    $table->renameColumn('warga_id', 'pelanggan_id');
                });
            }
        }

        foreach (['iuran', 'pengangkutan', 'pengaduan'] as $tabel) {
            if (Schema::hasTable($tabel) && Schema::hasColumn($tabel, 'pelanggan_id')) {
                Schema::table($tabel, function (Blueprint $table) {
                    $table->foreign('pelanggan_id')->references('id')->on('pelanggan')->onDelete('cascade');
                });
            }
        }
    }
};
