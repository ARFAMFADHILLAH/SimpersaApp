<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Parameter Kriteria & Bobot
        Schema::create('kriteria_dss', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kriteria'); // Contoh: C1, C2, C3
            $table->string('nama_kriteria'); // Contoh: Volume Sampah, Jarak, Keluhan
            $table->decimal('bobot', 5, 2);  // Contoh: 0.30 (30%)
            $table->enum('jenis', ['benefit', 'cost']); // Benefit: makin tinggi makin bagus, Cost: makin rendah makin bagus
            $table->timestamps();
        });

        // Tabel Nilai Skor Alternatif (Contoh Objek: TPS)
        Schema::create('skor_alternatif_dss', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tps_id')->constrained('tps')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriteria_dss')->onDelete('cascade');
            $table->decimal('nilai', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skor_alternatif_dss');
        Schema::dropIfExists('kriteria_dss');
    }
};
