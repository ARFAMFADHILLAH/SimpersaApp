<?php

namespace Tests\Feature;

use App\Models\JenisSampah;
use App\Models\KategoriSampah;
use App\Models\PenarikanSaldo;
use App\Models\PengaturanGaji;
use App\Models\Penggajian;
use App\Models\PenjualanSampah;
use App\Models\SetoranSampah;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AllRolesSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['admin', 'owner', 'bendahara', 'petugas_lapangan', 'warga'] as $name) {
            DB::table('roles')->updateOrInsert(
                ['name' => $name],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    private function makeUser(string $name, string $role): User
    {
        return User::factory()->create([
            'name' => $name,
            'role_id' => DB::table('roles')->where('name', $role)->value('id'),
            'status' => 'aktif',
        ]);
    }

    /**
     * Fixture lengkap POS: semua tabel terisi agar halaman bisa dirender 200.
     */
    private function buatDataDasar(): array
    {
        $admin = $this->makeUser('Admin Utama', 'admin');
        $owner = $this->makeUser('Owner Utama', 'owner');
        $bendahara = $this->makeUser('Bendahara', 'bendahara');
        $petugas = $this->makeUser('Andi Petugas', 'petugas_lapangan');
        $wargaUser = $this->makeUser('Budi Warga', 'warga');

        $kategori = KategoriSampah::create([
            'nama_kategori' => 'Non-Organik',
            'keterangan' => 'Sampah anorganik bernilai jual',
        ]);
        $jenis = JenisSampah::create([
            'kategori_sampah_id' => $kategori->id,
            'nama_jenis' => 'Plastik Botol PET',
            'tarif_per_kg' => 2000,
            'tarif_jual_per_kg' => 3000,
        ]);

        $warga = Warga::create([
            'user_id' => $wargaUser->id,
            'no_warga' => 'WRG-POS-001',
            'no_hp' => '081234567890',
            'alamat_lengkap' => 'Jl. Uji No. 1, RT 01/02',
            'saldo_tabungan' => 25000,
        ]);

        $setoran = SetoranSampah::create([
            'warga_id' => $warga->id,
            'jenis_sampah_id' => $jenis->id,
            'berat_kg' => 3,
            'harga_per_kg' => 2000,
            'total_bayar' => 6000,
            'tanggal_setoran' => now()->toDateString(),
        ]);

        $penjualan = PenjualanSampah::create([
            'kategori_sampah_id' => $kategori->id,
            'jenis_sampah_id' => $jenis->id,
            'nama_pengepul' => 'CV Bersih Sejahtera',
            'berat_kg' => 6,
            'harga_jual_per_kg' => 3000,
            'total_harga' => 18000,
            'tanggal_penjualan' => now()->toDateString(),
        ]);

        $penarikan = PenarikanSaldo::create([
            'warga_id' => $warga->id,
            'nominal' => 5000,
            'tanggal_penarikan' => now()->toDateString(),
            'status' => 'Diproses',
        ]);

        PengaturanGaji::ambil()->update(['gaji_pokok' => 2000000]);

        $tambahPetugas = User::factory()->create([
            'name' => 'Andi Petugas',
            'role_id' => DB::table('roles')->where('name', 'petugas_lapangan')->value('id'),
            'status' => 'aktif',
        ]);

        $gaji = Penggajian::create([
            'petugas_id' => $tambahPetugas->id,
            'bulan_gaji' => now()->format('Y-m'),
            'gaji_pokok' => 2000000,
            'insentif_lembur' => 100000,
            'potongan' => 0,
            'total_gaji_bersih' => 2100000,
            'status_pembayaran' => 'Pending',
        ]);

        return compact(
            'admin', 'owner', 'bendahara', 'petugas', 'wargaUser',
            'kategori', 'jenis', 'warga', 'setoran', 'penjualan', 'penarikan', 'gaji'
        );
    }

    public function test_semua_halaman_admin_membuka_200(): void
    {
        $d = $this->buatDataDasar();
        $admin = $d['admin'];

        $pages = [
            route('admin.dashboard'),
            route('admin.users.index'),
            route('admin.users.create'),
            route('admin.users.edit', $admin->id),
            route('admin.warga.index'),
            route('admin.warga.create'),
            route('admin.warga.show', $d['warga']->id),
            route('admin.warga.edit', $d['warga']->id),
            route('admin.kategori-sampah.index'),
            route('admin.jenis-sampah.index'),
            route('admin.gaji.index'),
            route('admin.sistem.index'),
            route('admin.absensi.index'),
        ];

        foreach ($pages as $page) {
            $this->actingAs($admin)->get($page)->assertOk("Gagal buka: $page");
        }
    }

    public function test_semua_halaman_owner_membuka_200(): void
    {
        $d = $this->buatDataDasar();
        $owner = $d['owner'];

        $pages = [
            route('owner.dashboard'),
            route('owner.laporan.index'),
            route('owner.laporan.kas'),
            route('owner.laporan.pembelian'),
            route('owner.laporan.penjualan'),
            route('owner.laporan.gaji'),
            route('owner.laporan.tabungan'),
            route('owner.keuangan.index'),
            route('owner.warga.index'),
            route('owner.pengguna.index'),
        ];

        foreach ($pages as $page) {
            $this->actingAs($owner)->get($page)->assertOk("Gagal buka: $page");
        }
    }

    public function test_semua_halaman_bendahara_membuka_200(): void
    {
        $d = $this->buatDataDasar();
        $bendahara = $d['bendahara'];

        $pages = [
            route('bendahara.dashboard'),
            route('bendahara.penjualan.index'),
            route('bendahara.pembelian.index'),
            route('bendahara.tabungan.index'),
            route('bendahara.penggajian.index'),
            route('bendahara.penggajian.rekap'),
            route('bendahara.penggajian.slip', $d['gaji']->id),
            route('bendahara.laporan.index'),
            route('bendahara.laporan.neraca'),
            route('bendahara.laporan.arus-kas'),
            route('bendahara.laporan.grafik'),
            route('bendahara.absensi.index'),
        ];

        foreach ($pages as $page) {
            $this->actingAs($bendahara)->get($page)->assertOk("Gagal buka: $page");
        }
    }

    public function test_semua_halaman_petugas_membuka_200(): void
    {
        $d = $this->buatDataDasar();
        $petugas = $d['petugas'];

        $pages = [
            route('petugas.dashboard'),
            route('petugas.pembelian.index'),
            route('petugas.penjualan.index'),
            route('petugas.pembelian.nota', $d['setoran']->id),
            route('petugas.gaji.index'),
            route('petugas.absensi.index'),
        ];

        foreach ($pages as $page) {
            $this->actingAs($petugas)->get($page)->assertOk("Gagal buka: $page");
        }
    }

    public function test_role_tidak_sah_ditolak_dari_semua_area(): void
    {
        $d = $this->buatDataDasar();

        $warga = $d['wargaUser'];
        $this->actingAs($warga)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($warga)->get(route('owner.dashboard'))->assertForbidden();
        $this->actingAs($warga)->get(route('bendahara.dashboard'))->assertForbidden();
        $this->actingAs($warga)->get(route('petugas.dashboard'))->assertForbidden();

        $petugas = $d['petugas'];
        $this->actingAs($petugas)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($petugas)->get(route('owner.dashboard'))->assertForbidden();
        $this->actingAs($petugas)->get(route('bendahara.dashboard'))->assertForbidden();

        $admin = $d['admin'];
        $this->actingAs($admin)->get(route('petugas.dashboard'))->assertForbidden();
        $this->actingAs($admin)->get(route('bendahara.dashboard'))->assertForbidden();
        $this->actingAs($admin)->get(route('owner.dashboard'))->assertForbidden();
    }

    public function test_route_backup_terdaftar_dan_dilindungi_role_admin(): void
    {
        $this->assertTrue(Route::has('admin.sistem.backup'));
        $route = Route::getRoutes()->getByName('admin.sistem.backup');
        $this->assertStringContainsString('role:', implode('|', $route->gatherMiddleware()));
    }

    public function test_route_pos_utama_terdaftar_semua(): void
    {
        $routes = [
            'admin.kategori-sampah.store',
            'admin.jenis-sampah.store',
            'petugas.pembelian.store',
            'petugas.penjualan.store',
            'bendahara.penjualan.store',
            'bendahara.tabungan.penarikan.store',
            'bendahara.tabungan.penarikan.ditarik',
            'bendahara.penggajian.proses',
            'owner.laporan.kas',
        ];

        foreach ($routes as $name) {
            $this->assertTrue(Route::has($name), "Route '$name' tidak terdaftar");
        }
    }
}
