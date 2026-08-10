<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penarikan_saldo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warga_id');
            $table->decimal('nominal', 14, 2)->default(0);
            $table->date('tanggal_penarikan');
            $table->enum('status', ['Diproses', 'Ditarik'])->default('Diproses');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('warga_id')->references('id')->on('warga')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penarikan_saldo');
    }
};