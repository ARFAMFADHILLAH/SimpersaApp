<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            // 1. Lepas dulu foreign key-nya
            $table->dropForeign('pelanggan_wilayah_id_foreign');
            
            // 2. Ubah tipe data dari bigint ke string (varchar)
            $table->string('wilayah_id')->nullable()->change();
            
            // 3. Rename nama kolomnya menjadi wilayah_pelayanan
            $table->renameColumn('wilayah_id', 'wilayah_pelayanan');
        });
    }

    public function down(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->renameColumn('wilayah_pelayanan', 'wilayah_id');
            $table->unsignedBigInteger('wilayah_id')->nullable()->change();
        });
    }
};