<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            // Hapus kolom string lama atau rename
            if (Schema::hasColumn('pelanggan', 'wilayah_pelayanan')) {
                $table->dropColumn('wilayah_pelayanan');
            }
            
            // Tambahkan kolom Foreign Key baru
            $table->foreignId('wilayah_pelayanan_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('wilayah_pelayanan')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->dropForeign(['wilayah_pelayanan_id']);
            $table->dropColumn('wilayah_pelayanan_id');
            $table->string('wilayah_pelayanan')->nullable();
        });
    }
};