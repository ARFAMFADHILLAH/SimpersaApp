<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pengaduan')) {
            Schema::create('pengaduan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade');
                $table->string('tipe_kendala');
                $table->text('catatan_lokasi')->nullable();
                $table->string('foto_bukti')->nullable();
                $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
                $table->enum('status_respon', ['Belum Dikerjakan', 'Sedang Dikerjakan', 'Selesai'])->default('Belum Dikerjakan');
                $table->text('catatan_petugas')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};
