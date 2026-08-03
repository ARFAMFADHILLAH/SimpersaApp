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
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel users (karena pelanggan juga bisa login nantinya)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Menghubungkan ke wilayah pelayanan dari Modul 1
           $table->foreignId('wilayah_id')->constrained('wilayah_pelayanan')->onDelete('cascade');
           $table->string('no_pelanggan')->unique(); // Format otomatis: PLG-2026-0001
           $table->string('no_hp');
           $table->text('alamat_lengkap');
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};
