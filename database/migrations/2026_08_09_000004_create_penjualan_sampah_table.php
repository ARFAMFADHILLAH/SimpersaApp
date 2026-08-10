<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan_sampah', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori_sampah_id')->nullable();
            $table->unsignedBigInteger('jenis_sampah_id')->nullable();
            $table->string('nama_pengepul')->nullable();
            $table->decimal('berat_kg', 10, 2)->default(0);
            $table->integer('harga_jual_per_kg')->default(0);
            $table->integer('total_harga')->default(0);
            $table->date('tanggal_penjualan');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('kategori_sampah_id')->references('id')->on('kategori_sampah')->onDelete('set null');
            $table->foreign('jenis_sampah_id')->references('id')->on('jenis_sampah_dan_tarif')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan_sampah');
    }
};