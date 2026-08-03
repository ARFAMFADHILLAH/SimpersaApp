<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi_petugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable(); // Clock-in
            $table->time('jam_pulang')->nullable(); // Clock-out
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir');
            $table->string('foto_masuk')->nullable(); // Opsional: foto saat clock-in
            $table->string('foto_pulang')->nullable(); // Opsional: foto saat clock-out
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_petugas');
    }
};
