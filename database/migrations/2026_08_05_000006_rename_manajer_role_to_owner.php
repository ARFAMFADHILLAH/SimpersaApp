<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('roles')->whereIn('name', ['manajer', 'manager'])->update(['name' => 'owner']);
    }

    public function down(): void
    {
        DB::table('roles')->where('name', 'owner')->update(['name' => 'manajer']);
    }
};
