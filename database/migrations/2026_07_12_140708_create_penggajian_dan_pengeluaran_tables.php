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
        // 1. Tabel Penggajian Petugas
    Schema::create('penggajian', function (Blueprint $table) {
        $table->id();
        $table->foreignId('petugas_id')->constrained('users')->onDelete('cascade');
        $table->string('bulan_gaji'); // Format: YYYY-MM
        $table->integer('gaji_pokok')->default(0);
        $table->integer('insentif_lembur')->default(0);
        $table->integer('potongan')->default(0);
        $table->integer('total_gaji_bersih')->default(0);
        $table->enum('status_pembayaran', ['Pending', 'Dibayar'])->default('Pending');
        $table->timestamps();
    });

    // 2. Tabel Pengeluaran Operasional (BBM, Servis, Alat)
    Schema::create('pengeluaran_operasional', function (Blueprint $table) {
        $table->id();
        $table->foreignId('armada_id')->nullable()->constrained('armada')->onDelete('set null');
        $table->date('tanggal_pengeluaran');
        $table->string('kategori_biaya'); // BBM, Servis, Pergantian Ban, Pembelian Alat
        $table->integer('jumlah_biaya');
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggajian');
        Schema::dropIfExists('pengeluaran_operasional');
    }
};
