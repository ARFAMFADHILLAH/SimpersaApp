<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * - Tambah kolom `bukti_pembayaran` (foto bukti transfer) pada tabel iuran
     * - Perluas enum `status_pembayaran` dengan status "Sedang Diproses"
     */
    public function up(): void
    {
        if (Schema::hasTable('iuran') && !Schema::hasColumn('iuran', 'bukti_pembayaran')) {
            Schema::table('iuran', function (Blueprint $table) {
                $table->string('bukti_pembayaran')->nullable()->after('metode_pembayaran');
            });
        }

        DB::statement("ALTER TABLE iuran MODIFY status_pembayaran ENUM('Belum Bayar', 'Sedang Diproses', 'Lunas') NOT NULL DEFAULT 'Belum Bayar'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE iuran MODIFY status_pembayaran ENUM('Belum Bayar', 'Lunas') NOT NULL DEFAULT 'Belum Bayar'");

        if (Schema::hasTable('iuran') && Schema::hasColumn('iuran', 'bukti_pembayaran')) {
            Schema::table('iuran', function (Blueprint $table) {
                $table->dropColumn('bukti_pembayaran');
            });
        }
    }
};
