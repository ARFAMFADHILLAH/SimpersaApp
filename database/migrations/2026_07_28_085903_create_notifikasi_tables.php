<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Master Template Notifikasi
        Schema::create('template_notifikasi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_template')->unique(); // Contoh: TPL_TAGIHAN_WA, TPL_JATUH_TEMPO
            $table->string('judul_template');          // Contoh: Pengingat Tagihan WhatsApp
            $table->enum('saluran', ['whatsapp', 'email', 'push']); 
            $table->string('subjek')->nullable();      // Diisi jika saluran = email
            $table->text('isi_pesan');                 // Format template pesan dengan placeholder
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });

        // Tabel Jadwal / Trigger Pengiriman Otomatis
        Schema::create('jadwal_notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('template_notifikasi')->onDelete('cascade');
            $table->string('nama_jadwal');             // Contoh: Otomatis Kirim Setiap Tgl 25
            $table->enum('pemicu', ['harian', 'mingguan', 'bulanan', 'event']); // Pemicu pengiriman
            $table->time('waktu_kirim')->default('08:00:00'); // Jam pengiriman
            $table->integer('hari_ke')->nullable();    // Contoh: Tanggal 25 (untuk bulanan) / 1=Senin (mingguan)
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_notifikasi');
        Schema::dropIfExists('template_notifikasi');
    }
};
