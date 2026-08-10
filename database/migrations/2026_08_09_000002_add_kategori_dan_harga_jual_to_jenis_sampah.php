<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jenis_sampah_dan_tarif', function (Blueprint $table) {
            $table->unsignedBigInteger('kategori_sampah_id')->nullable()->after('id');
            $table->integer('tarif_jual_per_kg')->default(0)->after('tarif_per_kg');
            $table->foreign('kategori_sampah_id')->references('id')->on('kategori_sampah')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('jenis_sampah_dan_tarif', function (Blueprint $table) {
            $table->dropForeign(['kategori_sampah_id']);
            $table->dropColumn(['kategori_sampah_id', 'tarif_jual_per_kg']);
        });
    }
};