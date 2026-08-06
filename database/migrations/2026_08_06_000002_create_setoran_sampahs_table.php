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
        Schema::create('setoran_sampahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->constrained('warga')->cascadeOnDelete();
            $table->foreignId('mitra_id')->nullable()->constrained('mitras')->nullOnDelete();
            $table->foreignId('jenis_sampah_id')->constrained('jenis_sampah_dan_tarif')->cascadeOnDelete();
            $table->decimal('berat_kg', 8, 2);
            $table->decimal('harga_per_kg', 10, 2);
            $table->decimal('total_bayar', 12, 2);
            $table->date('tanggal_setoran');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setoran_sampahs');
    }
};