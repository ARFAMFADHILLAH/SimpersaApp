<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengeluaran_operasional', function (Blueprint $table) {
            $table->enum('status_verifikasi', ['Menunggu', 'Disetujui', 'Ditolak'])
                ->default('Disetujui')
                ->after('keterangan');
            $table->text('catatan_verifikasi')->nullable()->after('status_verifikasi');
            $table->string('bukti_foto')->nullable()->after('catatan_verifikasi');
        });
    }

    public function down(): void
    {
        Schema::table('pengeluaran_operasional', function (Blueprint $table) {
            $table->dropColumn(['status_verifikasi', 'catatan_verifikasi', 'bukti_foto']);
        });
    }
};
