<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_gaji', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('gaji_pokok')->default(1500000);
            $table->unsignedInteger('insentif_per_hadir')->default(25000);
            $table->unsignedInteger('bonus_amount')->default(200000);
            $table->unsignedInteger('minimal_hadir_bonus')->default(20);
            $table->unsignedInteger('potongan_alpha_per_hari')->default(50000);
            $table->unsignedInteger('potongan_izin_per_hari')->default(20000);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_gaji');
    }
};
