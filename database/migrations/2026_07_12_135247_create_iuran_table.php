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
        Schema::create('iuran', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade');
        $table->string('bulan_tagihan'); // Format: YYYY-MM (Contoh: 2026-07)
        $table->integer('jumlah_tagihan')->default(0);
        $table->integer('denda')->default(0);
        $table->enum('status_pembayaran', ['Belum Bayar', 'Lunas'])->default('Belum Bayar');
        $table->date('tanggal_bayar')->nullable();
        $table->enum('metode_pembayaran', ['Tunai', 'Non-Tunai'])->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iuran');
    }
};
