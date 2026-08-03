<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_iuran', function (Blueprint $table) {
            $table->id();
            $table->integer('tarif_dasar_bulanan')->default(20000);
            $table->integer('persentase_denda_per_bulan')->default(5);
            $table->integer('nominal_denda_flat')->default(5000);
            $table->integer('tgl_jatuh_tempo')->default(10);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_iuran');
    }
};