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
        Schema::table('armada', function (Blueprint $table) {
            $table->decimal('kapasitas_m3', 10, 2)->nullable()->after('jenis_kendaraan');
        });
    }

    public function down(): void
    {
        Schema::table('armada', function (Blueprint $table) {
            $table->dropColumn('kapasitas_m3');
        });
    }
};
