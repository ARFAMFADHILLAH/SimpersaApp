<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// =============================================================
// SCHEDULER OTOMATIS (Jalankan di server: * * * * * php artisan schedule:run)
// =============================================================

// Modul 5: Generate tagihan iuran otomatis tanggal 1 setiap bulan pukul 00:00
Schedule::command('iuran:generate-tagihan')->monthlyOn(1, '00:00');

// Modul 13: Kirim notifikasi pengingat iuran sesuai jadwal_notifikasi
Schedule::command('notifikasi:kirim-pengingat')->everyThirtyMinutes();