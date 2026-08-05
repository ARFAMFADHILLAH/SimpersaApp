<?php

namespace Tests\Feature;

use App\Models\Armada;
use App\Models\Iuran;
use App\Models\JenisSampah;
use App\Models\Pengaduan;
use App\Models\Pengangkutan;
use App\Models\Penggajian;
use App\Models\Rute;
use App\Models\Tps;
use App\Models\User;
use App\Models\Warga;
use App\Models\Wilayah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AllRolesSmokeTest extends TestCase
{
    use RefreshDatabase;

    private array $roles = [];

    protected function setUp(): void
    {
        parent::setUp();

        $names = ['admin', 'owner', 'bendahara', 'petugas_lapangan', 'warga'];
        foreach ($names as $name) {
            DB::table('roles')->updateOrInsert(
                ['name' => $name],
                ['created_at' => now(), 'updated_at' => now()]
            );
            $this->roles[$name] = DB::table('roles')->where('name', $name)->value('id');
        }
    }

    private function makeUser(string $name, string $role, ?int $roleId = null): User
    {
        return User::factory()->create([
            'name' => $name,
            'role_id' => $roleId ?? $this->roles[$role],
            'status' => 'aktif',
        ]);
    }

    /**
     * Bangun fixture lengkap agar semua halaman bisa dirender (tidak ada tabel kosong yang memicu error).
     */
    private function buatDataDasar(): array
    {
        $admin = $this->makeUser('Admin Utama', 'admin');
        $owner = $this->makeUser('Owner Utama', 'owner');
        $bendahara = $this->makeUser('Bendahara', 'bendahara');
        $petugas = $this->makeUser('Andi Petugas', 'petugas_lapangan');
        $wargaUser = $this->makeUser('Budi Warga', 'warga');

        $wilayah = Wilayah::create([
            'nama_wilayah' => 'Wilayah Pusat Kota',
            'cakupan_area' => 'Kecamatan Pusat Kota',
        ]);

        $ruteA = Rute::create(['nama_rute' => 'Rute A - Perumahan Indah', 'hari_angkut' => 'Senin & Kamis', 'keterangan' => 'Perumahan Indah']);
        $ruteB = Rute::create(['nama_rute' => 'Rute B - Pasar & Komersil', 'hari_angkut' => 'Selasa & Jumat', 'keterangan' => 'Area pasar']);

        $warga = Warga::create([
            'user_id' => $wargaUser->id,
            'no_warga' => 'WRG-TEST-001',
            'no_hp' => '081234567890',
            'alamat_lengkap' => 'Jl. Uji No. 1, RT 01/02',
            'rute_id' => $ruteA->id,
            'wilayah_pelayanan_id' => $wilayah->id,
            'urutan' => 1,
        ]);

        $armada = Armada::create([
            'nama_kendaraan' => 'Truk Isuzu',
            'nomor_plat' => 'B 1234 XYZ',
            'jenis_kendaraan' => 'Truk',
            'kapasitas_m3' => 8,
            'status_kondisi' => 'aktif',
        ]);

        $jenisSampah = JenisSampah::create([
            'nama_jenis' => 'Sampah Rumah Tangga',
            'tarif_per_kg' => 2000,
            'tarif_bulanan_flat' => 20000,
        ]);

        $tps = Tps::create([
            'nama_tps' => 'TPS Pusat',
            'lokasi_koordinat' => '-6.200000,106.800000',
            'kapasitas_maksimal_m3' => '100',
        ]);

        $pengangkutan = Pengangkutan::create([
            'warga_id' => $warga->id,
            'armada_id' => $armada->id,
            'jenis_sampah_id' => $jenisSampah->id,
            'petugas_id' => $petugas->id,
            'tanggal_tugas' => today()->toDateString(),
            'status_tugas' => 'Belum dikerjakan',
        ]);

        $pengaduan = Pengaduan::create([
            'warga_id' => $warga->id,
            'tipe_kendala' => 'Truk Mogok',
            'catatan_lokasi' => 'Jl. Uji No. 1',
            'petugas_id' => $petugas->id,
            'status_respon' => 'Sedang Dikerjakan',
        ]);

        $iuran = Iuran::create([
            'warga_id' => $warga->id,
            'bulan_tagihan' => now()->format('Y-m'),
            'jumlah_tagihan' => 20000,
            'denda' => 0,
            'status_pembayaran' => 'Belum Bayar',
        ]);

        $iuranLunas = Iuran::create([
            'warga_id' => $warga->id,
            'bulan_tagihan' => now()->subMonth()->format('Y-m'),
            'jumlah_tagihan' => 20000,
            'denda' => 0,
            'status_pembayaran' => 'Lunas',
            'tanggal_bayar' => now()->toDateString(),
            'metode_pembayaran' => 'Tunai',
        ]);

        $penggajian = Penggajian::create([
            'petugas_id' => $petugas->id,
            'bulan_gaji' => now()->format('Y-m'),
            'gaji_pokok' => 1500000,
            'insentif_lembur' => 100000,
            'potongan' => 0,
            'total_gaji_bersih' => 1600000,
            'status_pembayaran' => 'Pending',
        ]);

        DB::table('pengeluaran_operasional')->insert([
            'armada_id' => $armada->id,
            'tanggal_pengeluaran' => today()->toDateString(),
            'kategori_biaya' => 'BBM',
            'jumlah_biaya' => 100000,
            'keterangan' => 'Isi solar',
            'status_verifikasi' => 'Disetujui',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('absensi_petugas')->insert([
            'user_id' => $petugas->id,
            'tanggal' => today()->toDateString(),
            'jam_masuk' => '08:00:00',
            'jam_pulang' => '16:00:00',
            'status' => 'hadir',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('laporan_kendalas')->insert([
            'petugas_id' => $petugas->id,
            'tipe_kendala' => 'Truk Mogok',
            'deskripsi' => 'Ban bocor di lapangan',
            'lokasi' => 'Jl. Uji No. 1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return compact(
            'admin', 'owner', 'bendahara', 'petugas', 'wargaUser',
            'wilayah', 'ruteA', 'ruteB', 'warga', 'armada', 'jenisSampah', 'tps',
            'pengangkutan', 'pengaduan', 'iuran', 'iuranLunas', 'penggajian'
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
            route('admin.wilayah.index'),
            route('admin.wilayah.edit', $d['wilayah']->id),
            route('admin.rute.index'),
            route('admin.rute.peta', $d['ruteA']->id),
            route('admin.armada.index'),
            route('admin.armada.create'),
            route('admin.armada.show', $d['armada']->id),
            route('admin.armada.edit', $d['armada']->id),
            route('admin.jenis-sampah.index'),
            route('admin.tps.index'),
            route('admin.iuran.index'),
            route('admin.gaji.index'),
            route('admin.operasional.index'),
            route('admin.operasional.jadwal-rute'),
            route('admin.operasional.rekap-volume'),
            route('admin.pengaduan.index'),
            route('admin.pengaduan.show', $d['pengaduan']->id),
            route('admin.pengangkutan.index'),
            route('admin.keputusan.index'),
            route('admin.laporan.index'),
            route('admin.notifikasi.index'),
            route('admin.sistem.index'),
            route('admin.master.index'),
            route('admin.master.warga.edit', $d['warga']->id),
            route('admin.master.armada.edit', $d['armada']->id),
            route('admin.master.tps.edit', $d['tps']->id),
            route('admin.logistik.index'),
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
            route('owner.dss.index'),
            route('owner.laporan.index'),
            route('owner.laporan.warga'),
            route('owner.laporan.iuran'),
            route('owner.laporan.volume'),
            route('owner.laporan.keuangan'),
            route('owner.laporan.gaji'),
            route('owner.laporan.armada'),
            route('owner.laporan.tunggakan'),
            route('owner.laporan.petugas'),
            route('owner.laporan.rekap-tahunan'),
            route('owner.laporan.kendala'),
            route('owner.warga.index'),
            route('owner.keuangan.index'),
            route('owner.armada.index'),
            route('owner.rute.index'),
            route('owner.rute.peta', $d['ruteA']->id),
            route('owner.pengaduan.index'),
            route('owner.iuran.index'),
            route('notifikasi.index'),
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
            route('bendahara.iuran.index'),
            route('bendahara.tunggakan'),
            route('bendahara.iuran.kwitansi', $d['iuranLunas']->id),
            route('bendahara.penggajian.index'),
            route('bendahara.penggajian.rekap'),
            route('bendahara.penggajian.slip', $d['penggajian']->id),
            route('bendahara.operasional.index'),
            route('bendahara.laporan.index'),
            route('bendahara.laporan.neraca'),
            route('bendahara.laporan.arus-kas'),
            route('bendahara.laporan.grafik'),
            route('notifikasi.index'),
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
            route('petugas.rute.index'),
            route('petugas.rute.tugas'),
            route('petugas.rute.detail', $d['ruteA']->id),
            route('petugas.gaji.index'),
            route('petugas.laporan.index'),
            route('petugas.pengaduan.index'),
            route('petugas.pengaduan.show', $d['pengaduan']->id),
            route('notifikasi.index'),
        ];

        foreach ($pages as $page) {
            $this->actingAs($petugas)->get($page)->assertOk("Gagal buka: $page");
        }
    }

    public function test_semua_halaman_warga_membuka_200(): void
    {
        $d = $this->buatDataDasar();
        $wargaUser = $d['wargaUser'];
        $pages = [
            route('warga.dashboard'),
            route('warga.profile'),
            route('warga.profile.riwayat'),
            route('warga.iuran.index'),
            route('warga.iuran.kwitansi', $d['iuranLunas']->id),
            route('warga.pengaduan.index'),
            route('warga.pengaduan.create'),
            route('notifikasi.index'),
        ];

        foreach ($pages as $page) {
            $this->actingAs($wargaUser)->get($page)->assertOk("Gagal buka: $page");
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

        $admin = $d['admin'];
        $this->actingAs($admin)->get(route('warga.dashboard'))->assertForbidden();
        $this->actingAs($admin)->get(route('petugas.dashboard'))->assertForbidden();
    }

    public function test_route_backup_terdaftar_dan_dilindungi_role_admin(): void
    {
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('admin.sistem.backup'));
        $route = \Illuminate\Support\Facades\Route::getRoutes()->getByName('admin.sistem.backup');
        $this->assertStringContainsString('role:', implode('|', $route->gatherMiddleware()));
    }
}
