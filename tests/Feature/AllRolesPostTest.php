<?php

namespace Tests\Feature;

use App\Models\JenisSampah;
use App\Models\KategoriSampah;
use App\Models\PenarikanSaldo;
use App\Models\PengaturanGaji;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AllRolesPostTest extends TestCase
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
        $roleId = DB::table('roles')->where('name', $role)->value('id');

        return User::factory()->create([
            'name' => $name,
            'role_id' => $roleId,
            'status' => 'aktif',
        ]);
    }

    private function buatWarga(string $nama = 'Budi Warga'): Warga
    {
        $user = $this->makeUser($nama, 'warga');

        return Warga::create([
            'user_id' => $user->id,
            'no_warga' => 'WRG-POS-001',
            'no_hp' => '081234567890',
            'alamat_lengkap' => 'Jl. Uji No. 1, RT 01/02',
        ]);
    }

    private function buatMaster(): array
    {
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

        return compact('kategori', 'jenis');
    }

    public function test_admin_menambah_kategori_dan_jenis_sampah_dengan_harga_beli_jual(): void
    {
        $admin = $this->makeUser('Admin', 'admin');
        $kategori = KategoriSampah::create(['nama_kategori' => 'Organik', 'keterangan' => 'Sisa organik']);

        $this->actingAs($admin)->post(route('admin.kategori-sampah.store'), [
            'nama_kategori' => 'B3',
            'keterangan' => 'Bahan berbahaya',
        ])->assertRedirect();
        $this->assertDatabaseHas('kategori_sampah', ['nama_kategori' => 'B3']);

        $this->actingAs($admin)->post(route('admin.jenis-sampah.store'), [
            'nama_jenis' => 'Sisa Makanan',
            'kategori_sampah_id' => $kategori->id,
            'tarif_per_kg' => 1500,
            'tarif_jual_per_kg' => 2500,
        ])->assertRedirect();
        $this->assertDatabaseHas('jenis_sampah_dan_tarif', [
            'nama_jenis' => 'Sisa Makanan',
            'kategori_sampah_id' => $kategori->id,
            'tarif_per_kg' => 1500,
            'tarif_jual_per_kg' => 2500,
        ]);

        $jenisBaru = JenisSampah::where('nama_jenis', 'Sisa Makanan')->first();
        $this->actingAs($admin)->put(route('admin.jenis-sampah.update', $jenisBaru->id), [
            'nama_jenis' => 'Sisa Makanan',
            'kategori_sampah_id' => $kategori->id,
            'tarif_per_kg' => 1600,
            'tarif_jual_per_kg' => 2600,
        ])->assertRedirect();
        $this->assertDatabaseHas('jenis_sampah_dan_tarif', ['id' => $jenisBaru->id, 'tarif_per_kg' => 1600]);
    }

    public function test_admin_mendaftarkan_pengguna_dan_warga(): void
    {
        $admin = $this->makeUser('Admin', 'admin');
        $rolePetugas = DB::table('roles')->where('name', 'petugas_lapangan')->value('id');

        $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Petugas Baru',
            'email' => 'petugasbaru@sistemsampah.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'role_id' => $rolePetugas,
            'status' => 'aktif',
        ])->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'petugasbaru@sistemsampah.com']);

        $this->actingAs($admin)->post(route('admin.warga.store'), [
            'name' => 'Warga Baru',
            'email' => 'wargabaru@sistemsampah.com',
            'no_hp' => '081298765432',
            'alamat_lengkap' => 'Jl. Melati No. 10, RT 03/05',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', ['email' => 'wargabaru@sistemsampah.com']);
        $user = User::where('email', 'wargabaru@sistemsampah.com')->first();
        $this->assertDatabaseHas('warga', [
            'user_id' => $user->id,
            'alamat_lengkap' => 'Jl. Melati No. 10, RT 03/05',
            'wilayah_pelayanan_id' => null,
            'rute_id' => null,
        ]);
    }

    public function test_admin_menyimpan_pengaturan_gaji(): void
    {
        $admin = $this->makeUser('Admin', 'admin');

        $this->actingAs($admin)->put(route('admin.gaji.update-pengaturan'), [
            'gaji_pokok' => 2000000,
        ])->assertRedirect();

        $this->assertDatabaseHas('pengaturan_gaji', [
            'gaji_pokok' => 2000000,
        ]);
    }

    public function test_petugas_mencatat_pembelian_sampah_dan_saldo_warga_bertambah(): void
    {
        $petugas = $this->makeUser('Andi', 'petugas_lapangan');
        $warga = $this->buatWarga();
        ['jenis' => $jenis] = $this->buatMaster();

        $response = $this->actingAs($petugas)->post(route('petugas.pembelian.store'), [
            'warga_id' => $warga->id,
            'jenis_sampah_id' => $jenis->id,
            'berat_kg' => 3,
            'tanggal_setoran' => now()->toDateString(),
            'keterangan' => 'Setoran mingguan',
        ]);

        $response->assertRedirect(route('petugas.pembelian.nota', 1));
        $this->assertDatabaseHas('setoran_sampahs', [
            'warga_id' => $warga->id,
            'jenis_sampah_id' => $jenis->id,
            'berat_kg' => 3,
            'harga_per_kg' => 2000,
            'total_bayar' => 6000,
        ]);

        $warga->refresh();
        $this->assertSame(6000, (int) $warga->saldo_tabungan);
    }

    public function test_petugas_pembelian_ditolak_jika_berat_nol(): void
    {
        $petugas = $this->makeUser('Andi', 'petugas_lapangan');
        $warga = $this->buatWarga();
        ['jenis' => $jenis] = $this->buatMaster();

        $this->actingAs($petugas)->post(route('petugas.pembelian.store'), [
            'warga_id' => $warga->id,
            'jenis_sampah_id' => $jenis->id,
            'berat_kg' => 0,
            'tanggal_setoran' => now()->toDateString(),
        ])->assertSessionHasErrors('berat_kg');

        $this->assertSame(0.0, (float) $warga->refresh()->saldo_tabungan);
    }

    public function test_petugas_mencatat_penjualan_sampah_ke_pengepul(): void
    {
        $petugas = $this->makeUser('Andi', 'petugas_lapangan');
        ['jenis' => $jenis] = $this->buatMaster();

        $response = $this->actingAs($petugas)->post(route('petugas.penjualan.store'), [
            'jenis_sampah_id' => $jenis->id,
            'nama_pengepul' => 'Bapak Tono',
            'berat_kg' => 6,
            'harga_jual_per_kg' => 2500,
            'tanggal_penjualan' => now()->toDateString(),
            'catatan' => 'Penjualan rutin',
        ]);

        $response->assertRedirect(route('petugas.penjualan.index'));
        $this->assertDatabaseHas('penjualan_sampah', [
            'jenis_sampah_id' => $jenis->id,
            'kategori_sampah_id' => $jenis->kategori_sampah_id,
            'nama_pengepul' => 'Bapak Tono',
            'berat_kg' => 6,
            'harga_jual_per_kg' => 2500,
            'total_harga' => 15000,
        ]);
    }

    public function test_bendahara_mencatat_penjualan_dari_meja_keuangan(): void
    {
        $bendahara = $this->makeUser('Bendahara', 'bendahara');
        ['jenis' => $jenis] = $this->buatMaster();

        $response = $this->actingAs($bendahara)->post(route('bendahara.penjualan.store'), [
            'jenis_sampah_id' => $jenis->id,
            'nama_pengepul' => 'CV Bersih Sejahtera',
            'berat_kg' => 2,
            'harga_jual_per_kg' => 3000,
            'tanggal_penjualan' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('bendahara.penjualan.index'));
        $this->assertDatabaseHas('penjualan_sampah', [
            'nama_pengepul' => 'CV Bersih Sejahtera',
            'total_harga' => 6000,
        ]);
    }

    public function test_bendahara_alur_penarikan_diproses_sampai_ditarik(): void
    {
        $bendahara = $this->makeUser('Bendahara', 'bendahara');
        $warga = $this->buatWarga();
        $warga->update(['saldo_tabungan' => 10000]);

        $response = $this->actingAs($bendahara)->post(route('bendahara.tabungan.penarikan.store'), [
            'warga_id' => $warga->id,
            'nominal' => 4000,
            'tanggal_penarikan' => now()->toDateString(),
        ]);
        $response->assertRedirect(route('bendahara.tabungan.index'));

        $penarikan = PenarikanSaldo::where('warga_id', $warga->id)->firstOrFail();
        $this->assertSame('Diproses', $penarikan->status);
        $this->assertSame(10000, (int) $warga->refresh()->saldo_tabungan);

        $this->actingAs($bendahara)->put(route('bendahara.tabungan.penarikan.ditarik', $penarikan->id))->assertRedirect();

        $this->assertSame('Ditarik', $penarikan->refresh()->status);
        $this->assertSame(6000, (int) $warga->refresh()->saldo_tabungan);
    }

    public function test_penarikan_melebihi_saldo_tabungan_ditolak(): void
    {
        $bendahara = $this->makeUser('Bendahara', 'bendahara');
        $warga = $this->buatWarga();
        $warga->update(['saldo_tabungan' => 5000]);

        $response = $this->actingAs($bendahara)->post(route('bendahara.tabungan.penarikan.store'), [
            'warga_id' => $warga->id,
            'nominal' => 10000,
            'tanggal_penarikan' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('bendahara.tabungan.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('penarikan_saldo', ['warga_id' => $warga->id]);
    }

    public function test_penarikan_dari_nominal_seribu_ke_bawah_ditolak(): void
    {
        $bendahara = $this->makeUser('Bendahara', 'bendahara');
        $warga = $this->buatWarga();
        $warga->update(['saldo_tabungan' => 5000]);

        $this->actingAs($bendahara)->post(route('bendahara.tabungan.penarikan.store'), [
            'warga_id' => $warga->id,
            'nominal' => 999,
            'tanggal_penarikan' => now()->toDateString(),
        ])->assertSessionHasErrors('nominal');
    }

    public function test_bendahara_memproses_dan_membayar_gaji_petugas(): void
    {
        $bendahara = $this->makeUser('Bendahara', 'bendahara');
        $petugas = $this->makeUser('Andi', 'petugas_lapangan');

        PengaturanGaji::ambil()->update([
            'gaji_pokok' => 2000000,
        ]);

        $bulan = now()->subMonth()->format('Y-m');

        $this->actingAs($bendahara)->post(route('bendahara.penggajian.proses'), [
            'bulan_gaji' => $bulan,
        ])->assertRedirect();

        $this->assertDatabaseHas('penggajian', [
            'petugas_id' => $petugas->id,
            'bulan_gaji' => $bulan,
            'gaji_pokok' => 2000000,
            'insentif_lembur' => 0,
            'potongan' => 0,
            'total_gaji_bersih' => 2000000,
        ]);

        $gaji = DB::table('penggajian')->where('petugas_id', $petugas->id)->where('bulan_gaji', $bulan)->first();
        $this->actingAs($bendahara)->post(route('bendahara.penggajian.bayar', $gaji->id), [
            'insentif_lembur' => 500000,
        ])->assertRedirect();
        $this->assertDatabaseHas('penggajian', [
            'id' => $gaji->id,
            'insentif_lembur' => 500000,
            'total_gaji_bersih' => 2500000,
            'status_pembayaran' => 'Dibayar',
        ]);
    }

    public function test_owner_tidak_bisa_melakukan_input_posts(): void
    {
        $owner = $this->makeUser('Owner', 'owner');
        ['jenis' => $jenis] = $this->buatMaster();

        // Tidak ada route POST pada dashboard owner
        $this->actingAs($owner)->post('/owner/dashboard', [])->assertStatus(405);

        // Route POST modul lain ditolak via middleware role
        $this->actingAs($owner)->post(route('petugas.pembelian.store'), [
            'warga_id' => 1, 'jenis_sampah_id' => $jenis->id, 'berat_kg' => 1,
        ])->assertForbidden();

        $this->actingAs($owner)->post(route('bendahara.penjualan.store'), [
            'jenis_sampah_id' => $jenis->id, 'berat_kg' => 1, 'harga_jual_per_kg' => 1000,
        ])->assertForbidden();

        $this->actingAs($owner)->post(route('admin.jenis-sampah.store'), [
            'nama_jenis' => 'X', 'kategori_sampah_id' => 1,
        ])->assertForbidden();
    }

    public function test_role_lama_manajer_tidak_lagi_berfungsi(): void
    {
        $roleManajer = DB::table('roles')->insertGetId([
            'name' => 'manajer',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $manajer = User::factory()->create([
            'name' => 'Manajer Lama',
            'role_id' => $roleManajer,
            'status' => 'aktif',
        ]);

        $this->actingAs($manajer)->get(route('owner.dashboard'))->assertForbidden();
        $this->actingAs($manajer)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_redirect_setelah_login_ke_dashboard_owner(): void
    {
        $owner = $this->makeUser('Owner', 'owner');
        $this->actingAs($owner)->get('/dashboard')->assertRedirect(route('owner.dashboard'));
    }
}
