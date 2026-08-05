<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotifikasiController;

// Controller Admin
use App\Http\Controllers\Admin\TPSController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\WargaController;
use App\Http\Controllers\Admin\JenisSampahController;
use App\Http\Controllers\Admin\IuranController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\SistemController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KeputusanController;
use App\Http\Controllers\Admin\NotifikasiController as AdminNotifikasiController;
use App\Http\Controllers\Admin\PengaduanController as AdminPengaduanController;
use App\Http\Controllers\Admin\ArmadaController;
use App\Http\Controllers\Admin\WilayahPelayananController;
use App\Http\Controllers\Admin\RuteController;
use App\Http\Controllers\Admin\PengangkutanController;
use App\Http\Controllers\Admin\MasterController;
use App\Http\Controllers\Admin\OperasionalController;
use App\Http\Controllers\Admin\LogistikController;
use App\Http\Controllers\Admin\GajiController;

// Controller Manager
use App\Http\Controllers\Owner\LaporanController as ManagerLaporanCtrl;
use App\Http\Controllers\Owner\ManagerLaporanController;
use App\Http\Controllers\Owner\RuteController as ManagerRuteController;
use App\Http\Controllers\Owner\DashboardController as ManagerDashboardController;
use App\Http\Controllers\Owner\ManagerController;
use App\Http\Controllers\Owner\ManagerArmadaController;
use App\Http\Controllers\Owner\ManagerDSSController;
use App\Http\Controllers\Owner\ManagerKeuanganController;
use App\Http\Controllers\Owner\ManagerPengaduanController;
use App\Http\Controllers\Owner\WargaController as ManagerWargaController;

// Controller Petugas Lapangan
use App\Http\Controllers\Petugas_lapangan\RuteController as PetugasLapanganRuteController;
use App\Http\Controllers\Petugas_lapangan\PengangkutanController as PetugasLapanganPengangkutanController;
use App\Http\Controllers\Petugas_lapangan\LaporanController as PetugasLapanganLaporanController;
use App\Http\Controllers\Petugas_lapangan\AbsensiController as PetugasLapanganAbsensiController;
use App\Http\Controllers\Petugas_lapangan\PengaduanController as PetugasLapanganPengaduanController;
use App\Http\Controllers\Petugas_lapangan\DashboardController as PetugasLapanganDashboardController;
use App\Http\Controllers\Petugas_lapangan\GajiController as PetugasLapanganGajiController;

// Controller Warga
use App\Http\Controllers\Warga\DashboardController as WargaDashboardController;
use App\Http\Controllers\Warga\ProfileController as WargaProfileController;
use App\Http\Controllers\Warga\IuranController as WargaIuranController;
use App\Http\Controllers\Warga\PengaduanController as WargaPengaduanController;
use App\Http\Controllers\Warga\NotifikasiController as WargaNotifikasiController;

// Controller Bendahara
use App\Http\Controllers\Bendahara\DashboardController as BendaharaDashboardController;
use App\Http\Controllers\BendaharaIuranController;
use App\Http\Controllers\BendaharaGajiController;
use App\Http\Controllers\BendaharaOperasionalController;
use App\Http\Controllers\BendaharaLaporanController;

Route::get('/', function () {
    return view('welcome');
});

// Profil Bawaan (Semua User Login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Modul 13: Notifikasi In-App (Semua Role)
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/{id}/baca', [NotifikasiController::class, 'tandaiBaca'])->name('notifikasi.baca');
    Route::post('/notifikasi/semua-baca', [NotifikasiController::class, 'tandaiSemuaBaca'])->name('notifikasi.semua-baca');
});

// =========================================================================
// AREA KHUSUS ADMIN (GABUNGAN SUPER ADMIN & ADMINISTRASI)
// =========================================================================
Route::middleware(['auth', 'role:admin,administrator,administrasi'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // System Utilities
    Route::get('/sistem', [SistemController::class, 'index'])->name('sistem.index');
    Route::post('/sistem/pengingat', [SistemController::class, 'kirimPengingat'])->name('sistem.pengingat');
    Route::get('/sistem/backup', [SistemController::class, 'backupDatabase'])->name('sistem.backup');

    // Master Data Management
    Route::resource('/jenis-sampah', JenisSampahController::class)->except(['create', 'edit', 'show'])->names('jenis-sampah');
    Route::resource('/warga', WargaController::class)->names('warga');
    Route::resource('/armada', ArmadaController::class)->names('armada');
    Route::resource('/wilayah-pelayanan', WilayahPelayananController::class)->except(['create', 'show'])->names('wilayah');
    Route::resource('/tps', TPSController::class)->except(['create', 'edit', 'show'])->names('tps');
    Route::resource('/users', UserController::class)->except('show')->names('users');

    // Parameter & Rule Configurations
    Route::get('/iuran/pengaturan', [IuranController::class, 'index'])->name('iuran.index');
    Route::put('/iuran/pengaturan', [IuranController::class, 'updatePengaturan'])->name('iuran.update-pengaturan');

    // Parameter Penggajian Petugas
    Route::get('/gaji/pengaturan', [GajiController::class, 'index'])->name('gaji.index');
    Route::put('/gaji/pengaturan', [GajiController::class, 'updatePengaturan'])->name('gaji.update-pengaturan');

    // Monitoring & GIS Rute (Read/Setup Only)
    Route::get('/rute/{id}/peta', [RuteController::class, 'peta'])->name('rute.peta');
    Route::resource('rute', RuteController::class)->only(['index', 'store']);

    // Log Monitoring Operasional Sampah (Monitoring & Audit Only - Tanpa Direct Input)
    Route::get('/pengangkutan', [PengangkutanController::class, 'index'])->name('pengangkutan.index');

    // Laporan & Audit System
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');

    // Decision Support System (DSS) Parameter Config
    Route::get('/keputusan', [KeputusanController::class, 'index'])->name('keputusan.index');
    Route::post('/keputusan/kriteria', [KeputusanController::class, 'storeKriteria'])->name('keputusan.kriteria.store');
    Route::put('/keputusan/kriteria/{id}', [KeputusanController::class, 'updateKriteria'])->name('keputusan.kriteria.update');
    Route::delete('/keputusan/kriteria/{id}', [KeputusanController::class, 'destroyKriteria'])->name('keputusan.kriteria.destroy');
    Route::post('/keputusan/skor', [KeputusanController::class, 'storeSkor'])->name('keputusan.skor.store');

    // Template & Schedule Notification
    Route::get('/notifikasi', [AdminNotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/template', [AdminNotifikasiController::class, 'storeTemplate'])->name('notifikasi.template.store');
    Route::put('/notifikasi/template/{id}', [AdminNotifikasiController::class, 'updateTemplate'])->name('notifikasi.template.update');
    Route::delete('/notifikasi/template/{id}', [AdminNotifikasiController::class, 'destroyTemplate'])->name('notifikasi.template.destroy');
    Route::post('/notifikasi/jadwal', [AdminNotifikasiController::class, 'storeJadwal'])->name('notifikasi.jadwal.store');
    Route::put('/notifikasi/jadwal/{id}', [AdminNotifikasiController::class, 'updateJadwal'])->name('notifikasi.jadwal.update');
    Route::delete('/notifikasi/jadwal/{id}', [AdminNotifikasiController::class, 'destroyJadwal'])->name('notifikasi.jadwal.destroy');

    // Monitoring Pengaduan (Monitoring + Verifikasi & Dispatch)
    Route::get('/pengaduan', [AdminPengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/{id}', [AdminPengaduanController::class, 'show'])->name('pengaduan.show');
    Route::post('/pengaduan/{id}/verifikasi', [AdminPengaduanController::class, 'verifikasi'])->name('pengaduan.verifikasi');
    Route::post('/pengaduan/{id}/dispatch', [AdminPengaduanController::class, 'dispatch'])->name('pengaduan.dispatch');

    // Master Data (Edit Warga, TPS, Armada) - dari Panel Administrasi
    Route::get('/master', [MasterController::class, 'index'])->name('master.index');
    Route::get('/master/warga/{id}/edit', [MasterController::class, 'editWarga'])->name('master.warga.edit');
    Route::put('/master/warga/{id}', [MasterController::class, 'updateWarga'])->name('master.warga.update');
    Route::get('/master/tps/{id}/edit', [MasterController::class, 'editTps'])->name('master.tps.edit');
    Route::put('/master/tps/{id}', [MasterController::class, 'updateTps'])->name('master.tps.update');
    Route::get('/master/armada/{id}/edit', [MasterController::class, 'editArmada'])->name('master.armada.edit');
    Route::put('/master/armada/{id}', [MasterController::class, 'updateArmada'])->name('master.armada.update');

    // Operasional: Rekap Volume & Jadwal Rute / Penugasan - dari Panel Administrasi
    Route::get('/operasional', [OperasionalController::class, 'index'])->name('operasional.index');
    Route::get('/operasional/rekap-volume', [OperasionalController::class, 'rekapVolume'])->name('operasional.rekap-volume');
    Route::get('/operasional/jadwal-rute', [OperasionalController::class, 'jadwalRute'])->name('operasional.jadwal-rute');
    Route::post('/operasional/tugaskan', [OperasionalController::class, 'tugaskanPetugas'])->name('operasional.tugaskan');
    Route::post('/operasional/warga/{id}/urut', [OperasionalController::class, 'ubahUrutan'])->name('operasional.urut');

    // Logistik Armada (Pengeluaran Operasional) - Monitoring (Baca-Saja, input oleh bendahara)
    Route::get('/logistik', [LogistikController::class, 'index'])->name('logistik.index');
});

// =========================================================================
// AREA KHUSUS OWNER / PEMILIK (PEMANTAUAN READ-ONLY)
// =========================================================================
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {

    // 1. Dashboard Utama
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');

    // 2. DSS & Rekomendasi / Evaluasi Prioritas Wilayah
    Route::get('/dss', [ManagerDSSController::class, 'index'])->name('dss.index');

    // 3. Pusat Laporan Executif & Sub-Laporan
    Route::get('/laporan', [ManagerLaporanCtrl::class, 'index'])->name('laporan.index');
    Route::post('/laporan/cetak', [ManagerLaporanCtrl::class, 'cetak'])->name('laporan.cetak');
    Route::get('/laporan/warga', [ManagerLaporanController::class, 'warga'])->name('laporan.warga');
    Route::get('/laporan/iuran', [ManagerLaporanController::class, 'iuran'])->name('laporan.iuran');
    Route::get('/laporan/volume', [ManagerLaporanController::class, 'volume'])->name('laporan.volume');
    Route::get('/laporan/keuangan', [ManagerLaporanController::class, 'keuangan'])->name('laporan.keuangan');
    Route::get('/laporan/gaji', [ManagerLaporanController::class, 'gaji'])->name('laporan.gaji');
    Route::get('/laporan/armada', [ManagerLaporanController::class, 'armada'])->name('laporan.armada');
    Route::get('/laporan/tunggakan', [ManagerLaporanController::class, 'tunggakan'])->name('laporan.tunggakan');
    Route::get('/laporan/petugas', [ManagerLaporanController::class, 'petugas'])->name('laporan.petugas');
    Route::get('/laporan/rekap-tahunan', [ManagerLaporanController::class, 'rekapTahunan'])->name('laporan.rekap-tahunan');
    Route::get('/laporan/kendala', [ManagerLaporanController::class, 'kendala'])->name('laporan.kendala');

    // 4. Monitoring Warga
    Route::get('/warga', [ManagerWargaController::class, 'index'])->name('warga.index');

    // 5. Arus Kas & Gaji (Keuangan)
    Route::get('/keuangan', [ManagerKeuanganController::class, 'index'])->name('keuangan.index');

    // 6. Kondisi Armada & Rute Operasional
    Route::get('/armada', [ManagerArmadaController::class, 'index'])->name('armada.index');
    Route::get('/rute', [ManagerRuteController::class, 'index'])->name('rute.index');
    Route::get('/rute/{id}/peta', [ManagerRuteController::class, 'peta'])->name('rute.peta');

    // 7. Log Pengaduan
    Route::get('/pengaduan', [ManagerPengaduanController::class, 'index'])->name('pengaduan.index');

    // 8. Iuran
    Route::get('/iuran', [ManagerController::class, 'iuran'])->name('iuran.index');
});

// =========================================================================
// AREA KHUSUS BENDAHARA / KEUANGAN
// =========================================================================
Route::middleware(['auth', 'role:bendahara'])->prefix('bendahara')->name('bendahara.')->group(function () {

    Route::get('/dashboard', [BendaharaDashboardController::class, 'index'])->name('dashboard');

    // ===== Modul 5: Manajemen Iuran =====
    Route::get('/iuran', [BendaharaIuranController::class, 'index'])->name('iuran.index');
    Route::post('/iuran/generate', [BendaharaIuranController::class, 'generate'])->name('iuran.generate');
    Route::post('/iuran/bayar/{id}', [BendaharaIuranController::class, 'bayar'])->name('iuran.bayar');
    Route::get('/iuran/kwitansi/{id}', [BendaharaIuranController::class, 'cetakKwitansi'])->name('iuran.kwitansi');
    Route::get('/iuran/tunggakan', [BendaharaIuranController::class, 'tunggakan'])->name('tunggakan');

    // ===== Modul 6: Penggajian Petugas =====
    Route::get('/penggajian', [BendaharaGajiController::class, 'index'])->name('penggajian.index');
    Route::post('/penggajian/proses', [BendaharaGajiController::class, 'prosesGaji'])->name('penggajian.proses');
    Route::post('/penggajian/bayar/{id}', [BendaharaGajiController::class, 'bayarGaji'])->name('penggajian.bayar');
    Route::get('/penggajian/slip/{id}', [BendaharaGajiController::class, 'cetakSlip'])->name('penggajian.slip');
    Route::get('/penggajian/rekap', [BendaharaGajiController::class, 'rekapGaji'])->name('penggajian.rekap');

    // ===== Modul 7: Manajemen Operasional =====
    Route::get('/operasional', [BendaharaOperasionalController::class, 'index'])->name('operasional.index');
    Route::post('/operasional/store', [BendaharaOperasionalController::class, 'store'])->name('operasional.store');
    Route::post('/operasional/verifikasi/{id}', [BendaharaOperasionalController::class, 'verifikasi'])->name('operasional.verifikasi');
    Route::delete('/operasional/{id}', [BendaharaOperasionalController::class, 'destroy'])->name('operasional.destroy');

    // ===== Modul 8: Laporan Keuangan & Laba Rugi =====
    Route::get('/laporan', [BendaharaLaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/cetak', [BendaharaLaporanController::class, 'cetak'])->name('laporan.cetak');
    Route::get('/laporan/grafik', [BendaharaLaporanController::class, 'dataGrafik'])->name('laporan.grafik');
    Route::get('/laporan/neraca-kas', [BendaharaLaporanController::class, 'neracaKas'])->name('laporan.neraca');
    Route::get('/laporan/arus-kas', [BendaharaLaporanController::class, 'arusKas'])->name('laporan.arus-kas');
});

// =========================================================================
// AREA KHUSUS PETUGAS LAPANGAN 
// =========================================================================
Route::middleware(['auth', 'role:petugas,petugas_lapangan'])->prefix('petugas')->name('petugas.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [PetugasLapanganDashboardController::class, 'index'])->name('dashboard');

    // ===== Modul 3: Rute, Jadwal & Monitoring Operasional =====
    Route::get('/rute', [PetugasLapanganRuteController::class, 'index'])->name('rute.index');
    Route::get('/rute/tugas-harian', [PetugasLapanganRuteController::class, 'tugasHarian'])->name('rute.tugas');
    Route::get('/rute/{id}', [PetugasLapanganRuteController::class, 'show'])->name('rute.detail');
    Route::post('/rute/{id}/update-status', [PetugasLapanganRuteController::class, 'updateStatus'])->name('rute.update');

    // ===== Modul 4: Pengelolaan Sampah (Hasil Pengangkutan per Titik) =====
    Route::post('/pengangkutan/{id}/upload-foto', [PetugasLapanganPengangkutanController::class, 'uploadFoto'])->name('pengangkutan.upload');

    // ===== Modul 11: Penugasan & Disposisi Pengaduan =====
    Route::get('/pengaduan', [PetugasLapanganPengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/{id}', [PetugasLapanganPengaduanController::class, 'show'])->name('pengaduan.show');
    Route::post('/pengaduan/{id}/update', [PetugasLapanganPengaduanController::class, 'updateStatus'])->name('pengaduan.update');

    // ===== Modul 6: Gaji & Presensi =====
    Route::get('/gaji', [PetugasLapanganGajiController::class, 'index'])->name('gaji.index');

    // Laporan Kendala Lapangan
    Route::get('/laporan', [PetugasLapanganLaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan', [PetugasLapanganLaporanController::class, 'store'])->name('laporan.store');

    // Absensi Harian (Clock-In / Clock-Out)
    Route::post('/absensi/clock-in', [PetugasLapanganAbsensiController::class, 'clockIn'])->name('absensi.clockin');
    Route::post('/absensi/clock-out', [PetugasLapanganAbsensiController::class, 'clockOut'])->name('absensi.clockout');

});

// =========================================================================
// AREA KHUSUS WARGA / MASYARAKAT
// =========================================================================
Route::middleware(['auth', 'role:warga'])->prefix('warga')->name('warga.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [WargaDashboardController::class, 'index'])->name('dashboard');

    // Modul 2: Profil & Riwayat Pengangkutan
    Route::get('/profil', [WargaProfileController::class, 'index'])->name('profile');
    Route::get('/riwayat', [WargaProfileController::class, 'riwayat'])->name('profile.riwayat');

    // Modul 5: Iuran & Pembayaran
    Route::get('/iuran', [WargaIuranController::class, 'index'])->name('iuran.index');
    Route::post('/iuran/{id}/bayar', [WargaIuranController::class, 'bayar'])->name('iuran.bayar');
    Route::get('/iuran/{id}/kwitansi', [WargaIuranController::class, 'kwitansi'])->name('iuran.kwitansi');

    // Modul 11: Pengaduan Masyarakat
    Route::get('/pengaduan', [WargaPengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/create', [WargaPengaduanController::class, 'create'])->name('pengaduan.create');
    Route::post('/pengaduan', [WargaPengaduanController::class, 'store'])->name('pengaduan.store');

    // Modul 13: Notifikasi
    Route::get('/notifikasi', [WargaNotifikasiController::class, 'index'])->name('notifikasi.index');
});

// =========================================================================
// REDIRECT OTOMATIS SESUAI ROLE (AFTER LOGIN)
// =========================================================================
Route::get('/dashboard', function () {
    $user = auth()->user();
    $userRole = \DB::table('roles')->where('id', $user->role_id)->first();

    if ($userRole) {
        $roleName = strtolower(trim($userRole->name));

        if (in_array($roleName, ['administrator', 'admin', 'petugas_administrasi', 'administrasi'])) {
            return redirect()->route('admin.dashboard');
        }

        if (in_array($roleName, ['owner'])) {
            return redirect()->route('owner.dashboard');
        }

        if ($roleName === 'bendahara') {
            return redirect()->route('bendahara.dashboard');
        }

        if (in_array($roleName, ['petugas', 'petugas_lapangan'])) {
            return redirect()->route('petugas.dashboard');
        }

        if (in_array($roleName, ['warga'])) {
            return redirect()->route('warga.dashboard');
        }
    }

    return redirect('/');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';