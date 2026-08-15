<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('penjualan_sampah', function (Blueprint $table) {
            $table->string('kode_transaksi', 50)->nullable()->index()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualan_sampah', function (Blueprint $table) {
            $table->dropIndex(['kode_transaksi']);
            $table->dropColumn('kode_transaksi');
        });
    }
};