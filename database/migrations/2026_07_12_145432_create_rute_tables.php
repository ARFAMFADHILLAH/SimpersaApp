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
        // 1. Tabel Master Rute / Wilayah Kerja
    Schema::create('rute', function (Blueprint $table) {
        $table->id();
        $table->string('nama_rute'); // Contoh: Rute Perumahan A, Rute Komersil Barat
        $table->string('hari_angkut'); // Contoh: Senin & Kamis
        $table->string('titik_koordinat_pusat')->nullable(); // Lat, Long pusat wilayah untuk peta
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });

    // 2. Tambahkan kolom relasi rute_id ke tabel pelanggan yang sudah ada
    Schema::table('pelanggan', function (Blueprint $table) {
        $table->foreignId('rute_id')->nullable()->after('user_id')->constrained('rute')->nullOnDelete();
        $table->string('latitude')->nullable()->after('alamat_lengkap'); // Untuk penanda titik rumah di peta
        $table->string('longitude')->nullable()->after('latitude');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
        $table->dropForeign(['rute_id']);
        $table->dropColumn(['rute_id', 'latitude', 'longitude']);
        });
        Schema::dropIfExists('rute');
    }
};
