<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatbotController;

// =========================================================================
// CONTROLLER POS BANK SAMPAH (SIMPERSA)
// =========================================================================
// Admin (Super Admin)
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WargaController;
use App\Http\Controllers\Admin\KategoriSampahController;
use App\Http\Controllers\Admin\JenisSampahController;
use App\Http\Controllers\Admin\GajiController;
use App\Http\Controllers\Admin\SistemController;
use App\Http\Controllers\Admin\AbsensiController as AdminAbsensiController;

// Petugas (Kasir / Operasional)
use App\Http\Controllers\Petugas_lapangan\DashboardController as KasirDashboardController;
use App\Http\Controllers\Petugas_lapangan\AbsensiController as KasirAbsensiController;
use App\Http\Controllers\Petugas_lapangan\KasirController;
use App\Http\Controllers\Petugas_lapangan\GajiController as KasirGajiController;

// Bendahara (Keuangan)
use App\Http\Controllers\Bendahara\DashboardController as BendaharaDashboardController;
use App\Http\Controllers\Bendahara\PenjualanController;
use App\Http\Controllers\Bendahara\TabunganController;
use App\Http\Controllers\Bendahara\PembelianController;
use App\Http\Controllers\Bendahara\AbsensiController as BendaharaAbsensiController;
use App\Http\Controllers\BendaharaGajiController;
use App\Http\Controllers\BendaharaLaporanController;

// Owner (Pemantauan Read-Only)
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\LaporanController as OwnerLaporanController;
use App\Http\Controllers\Owner\ManagerWargaController as OwnerWargaController;
use App\Http\Controllers\Owner\PenggunaController as OwnerPenggunaController;
use App\Http\Controllers\Owner\ManagerKeuanganController as OwnerKeuanganController;
use App\Http\Controllers\Owner\StokController as OwnerStokController;
use App\Http\Controllers\Admin\StokController as AdminStokController;

Route::get('/', function () {
    return view('welcome');
});

// Chatbot Asisten SIMPERSA (tanpa middleware auth: welcome juga bisa untuk tamu)
Route::post('/chatbot/tanya', [ChatbotController::class, 'tanya'])->name('chatbot.tanya');

// Profil Bawaan (Semua User Login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =========================================================================
// AREA SUPER ADMIN — MASTER DATA & PENGATURAN
// =========================================================================
Route::middleware(['auth', 'role:admin,administrator,administrasi'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manajemen Pengguna
    Route::resource('/users', UserController::class)->except('show')->names('users');

    // Manajemen Data Nasabah (Warga)
    Route::resource('/warga', WargaController::class)->names('warga');

    // Master Data Sampah: Kategori & Jenis (Harga Beli = bayar ke warga, Harga Jual = jual ke pengepul)
    Route::resource('/kategori-sampah', KategoriSampahController::class)->except(['create', 'edit', 'show'])->names('kategori-sampah');
    Route::resource('/jenis-sampah', JenisSampahController::class)->except(['create', 'edit', 'show'])->names('jenis-sampah');

    // Standar Gaji Pokok Petugas
    Route::get('/gaji/pengaturan', [GajiController::class, 'index'])->name('gaji.index');
    Route::put('/gaji/pengaturan', [GajiController::class, 'updatePengaturan'])->name('gaji.update-pengaturan');

    // Rekap Kehadiran Petugas
    Route::get('/absensi', [AdminAbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi', [AdminAbsensiController::class, 'store'])->name('absensi.store');
    Route::patch('/absensi/{absensi}', [AdminAbsensiController::class, 'updateStatus'])->name('absensi.update-status');

    // Stok Sampah
    Route::get('/stok', [AdminStokController::class, 'index'])->name('stok.index');

    // Utilitas Sistem (Backup Data)
    Route::get('/sistem', [SistemController::class, 'index'])->name('sistem.index');
    Route::get('/sistem/backup', [SistemController::class, 'backupDatabase'])->name('sistem.backup');
});

// =========================================================================
// AREA OWNER / PEMILIK (PEMANTAUAN READ-ONLY)
// =========================================================================
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {

    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

    // Pusat Laporan Monitoring
    Route::get('/laporan', [OwnerLaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/kas', [OwnerLaporanController::class, 'kas'])->name('laporan.kas');
    Route::get('/laporan/pembelian', [OwnerLaporanController::class, 'pembelian'])->name('laporan.pembelian');
    Route::get('/laporan/penjualan', [OwnerLaporanController::class, 'penjualan'])->name('laporan.penjualan');
    Route::get('/laporan/gaji', [OwnerLaporanController::class, 'gaji'])->name('laporan.gaji');
    Route::get('/laporan/tabungan', [OwnerLaporanController::class, 'tabungan'])->name('laporan.tabungan');

    // Monitoring Data Operasional
    Route::get('/keuangan', [OwnerKeuanganController::class, 'index'])->name('keuangan.index');
    Route::get('/warga', [OwnerWargaController::class, 'index'])->name('warga.index');
    Route::get('/pengguna', [OwnerPenggunaController::class, 'index'])->name('pengguna.index');
    Route::get('/stok', [OwnerStokController::class, 'index'])->name('stok.index');
});

// =========================================================================
// AREA BENDAHARA / KEUANGAN
// =========================================================================
Route::middleware(['auth', 'role:bendahara'])->prefix('bendahara')->name('bendahara.')->group(function () {

    Route::get('/dashboard', [BendaharaDashboardController::class, 'index'])->name('dashboard');

    // Penjualan Sampah ke Pengepul (Input & Rekap)
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');

    // Rincian Pembelian Sampah Warga
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');

    // Tabungan Warga & Penarikan Dana
    Route::get('/tabungan', [TabunganController::class, 'index'])->name('tabungan.index');
    Route::post('/tabungan/penarikan', [TabunganController::class, 'storePenarikan'])->name('tabungan.penarikan.store');
    Route::put('/tabungan/penarikan/{id}/ditarik', [TabunganController::class, 'tandaiDitarik'])->name('tabungan.penarikan.ditarik');

    // Penggajian Petugas (Gaji Pokok + Bonus)
    Route::get('/penggajian', [BendaharaGajiController::class, 'index'])->name('penggajian.index');
    Route::post('/penggajian/proses', [BendaharaGajiController::class, 'prosesGaji'])->name('penggajian.proses');
    Route::post('/penggajian/bayar/{id}', [BendaharaGajiController::class, 'bayarGaji'])->name('penggajian.bayar');
    Route::get('/penggajian/slip/{id}', [BendaharaGajiController::class, 'cetakSlip'])->name('penggajian.slip');
    Route::get('/penggajian/rekap', [BendaharaGajiController::class, 'rekapGaji'])->name('penggajian.rekap');

    // Rekap Kehadiran Petugas
    Route::get('/absensi', [BendaharaAbsensiController::class, 'index'])->name('absensi.index');

    // Laporan Keuangan (Arus Kas Masuk & Keluar)
    Route::get('/laporan', [BendaharaLaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/cetak', [BendaharaLaporanController::class, 'cetak'])->name('laporan.cetak');
    Route::get('/laporan/grafik', [BendaharaLaporanController::class, 'dataGrafik'])->name('laporan.grafik');
    Route::get('/laporan/neraca-kas', [BendaharaLaporanController::class, 'neracaKas'])->name('laporan.neraca');
    Route::get('/laporan/arus-kas', [BendaharaLaporanController::class, 'arusKas'])->name('laporan.arus-kas');
});

// =========================================================================
// AREA PETUGAS / KASIR (Operasional Konter)
// =========================================================================
Route::middleware(['auth', 'role:petugas,petugas_lapangan'])->prefix('petugas')->name('petugas.')->group(function () {

    Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');

    // Transaksi Pembelian Sampah dari Warga + Cetak Nota
    Route::get('/pembelian', [KasirController::class, 'pembelian'])->name('pembelian.index');
    Route::post('/pembelian', [KasirController::class, 'storePembelian'])->name('pembelian.store');
    Route::get('/pembelian/nota/{id}', [KasirController::class, 'nota'])->name('pembelian.nota');

    // Pencatatan Penjualan Sampah ke Pengepul
    Route::get('/penjualan', [KasirController::class, 'penjualan'])->name('penjualan.index');
    Route::post('/penjualan', [KasirController::class, 'storePenjualan'])->name('penjualan.store');

    // Absensi (Clock-In / Clock-Out)
    Route::get('/absensi', [KasirAbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi/clockin', [KasirAbsensiController::class, 'clockIn'])->name('absensi.clockin');
    Route::post('/absensi/clockout', [KasirAbsensiController::class, 'clockOut'])->name('absensi.clockout');
    Route::post('/absensi/lapor', [KasirAbsensiController::class, 'lapor'])->name('absensi.lapor');
    Route::delete('/absensi/lapor', [KasirAbsensiController::class, 'laporBatal'])->name('absensi.lapor-batal');

    // Gaji & Slip Gaji Pribadi
    Route::get('/gaji', [KasirGajiController::class, 'index'])->name('gaji.index');
    Route::get('/gaji/slip/{id}', [KasirGajiController::class, 'slip'])->name('gaji.slip');
});

// =========================================================================
// REDIRECT OTOMATIS SESUAI ROLE (AFTER LOGIN)
// =========================================================================
Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();
    $userRole = DB::table('roles')->where('id', $user->role_id)->first();

    if ($userRole) {
        $roleName = strtolower(trim($userRole->name));

        if (in_array($roleName, ['administrator', 'admin', 'petugas_administrasi', 'administrasi'])) {
            return redirect()->route('admin.dashboard');
        }

        if (in_array($roleName, ['owner'])) {
            return redirect()->route('owner.dashboard');
        }

        if (in_array($roleName, ['bendahara'])) {
            return redirect()->route('bendahara.dashboard');
        }

        if (in_array($roleName, ['petugas', 'petugas_lapangan'])) {
            return redirect()->route('petugas.dashboard');
        }
    }

    return redirect('/');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';