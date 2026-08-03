<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_kendalas', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel users (petugas yang melapor)
            $table->foreignId('petugas_id')->constrained('users')->onDelete('cascade');

            $table->string('tipe_kendala'); // Misal: Truk Mogok, Jalan Ditutup, Cuaca Buruk, TPS Penuh
            $table->text('deskripsi'); // Detail kejadian di lapangan
            $table->string('lokasi')->nullable(); // Bisa diisi nama jalan atau koordinat manual
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_kendalas');
    }
};
