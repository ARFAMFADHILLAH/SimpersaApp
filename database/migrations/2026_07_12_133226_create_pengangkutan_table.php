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
        Schema::create('pengangkutan', function (Blueprint $table) {
        $table->id();
        // Relasi ke pelanggan, armada, dan jenis sampah
        $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade');
        $table->foreignId('armada_id')->constrained('armada')->onDelete('cascade');
        $table->foreignId('jenis_sampah_id')->constrained('jenis_sampah_dan_tarif')->onDelete('cascade');

        // Data Petugas (mengambil dari user bertipe petugas)
        $table->foreignId('petugas_id')->constrained('users')->onDelete('cascade');

        // Detail Operasional & Hasil Pengelolaan Sampah (Modul 4)
        $table->date('tanggal_tugas');
        $table->decimal('volume_m3', 8, 2)->default(0); // Volume dalam meter kubik
        $table->decimal('berat_kg', 8, 2)->default(0);  // Berat dalam kilogram

        // Status Pekerjaan sesuai Modul 3
        $table->enum('status_tugas', ['Belum dikerjakan', 'Sedang dikerjakan', 'Selesai'])->default('Belum dikerjakan');

        $table->text('catatan')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengangkutan');
    }
};
