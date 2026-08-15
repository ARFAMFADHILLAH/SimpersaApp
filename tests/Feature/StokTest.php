<?php

namespace Tests\Feature;

use App\Models\JenisSampah;
use App\Models\KategoriSampah;
use App\Models\PenjualanSampah;
use App\Models\SetoranSampah;
use App\Models\User;
use App\Models\Warga;
use App\Support\StokSampah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StokTest extends TestCase
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

    private function buatWarga(): Warga
    {
        $user = $this->makeUser('Budi Warga', 'warga');

        return Warga::create([
            'user_id' => $user->id,
            'no_warga' => 'WRG-POS-' . uniqid(),
            'no_hp' => '081234567890',
            'alamat_lengkap' => 'Jl. Uji No. 1, RT 01/02',
        ]);
    }

    private function buatJenis(): JenisSampah
    {
        $kategori = KategoriSampah::create([
            'nama_kategori' => 'Non-Organik',
            'keterangan' => 'Sampah anorganik bernilai jual',
        ]);

        return JenisSampah::create([
            'kategori_sampah_id' => $kategori->id,
            'nama_jenis' => 'Plastik Botol PET',
            'tarif_per_kg' => 2000,
            'tarif_jual_per_kg' => 3000,
        ]);
    }

    private function setoran(int $jenisId, float $berat): void
    {
        SetoranSampah::create([
            'warga_id' => $this->buatWarga()->id,
            'jenis_sampah_id' => $jenisId,
            'berat_kg' => $berat,
            'harga_per_kg' => 2000,
            'total_bayar' => (int) round($berat * 2000),
            'tanggal_setoran' => now()->toDateString(),
        ]);
    }

    public function test_halaman_stok_dapat_diakses_admin_dan_owner(): void
    {
        $admin = $this->makeUser('Admin', 'admin');
        $this->actingAs($admin)->get(route('admin.stok.index'))->assertOk();

        $owner = $this->makeUser('Owner', 'owner');
        $this->actingAs($owner)->get(route('owner.stok.index'))->assertOk();
    }

    public function test_halaman_stok_ditolak_untuk_role_lain(): void
    {
        foreach (['bendahara', 'petugas_lapangan', 'warga'] as $role) {
            $user = $this->makeUser(ucfirst($role), $role);

            $this->actingAs($user)->get(route('admin.stok.index'))->assertForbidden();
            $this->actingAs($user)->get(route('owner.stok.index'))->assertForbidden();
        }
    }

    public function test_stok_dihitung_dari_setoran_dikurangi_penjualan(): void
    {
        $jenis = $this->buatJenis();
        $this->setoran($jenis->id, 3);

        SetoranSampah::create([
            'warga_id' => $this->buatWarga()->id,
            'jenis_sampah_id' => $jenis->id,
            'berat_kg' => 2,
            'harga_per_kg' => 2000,
            'total_bayar' => 4000,
            'tanggal_setoran' => now()->toDateString(),
        ]);

        PenjualanSampah::create([
            'kategori_sampah_id' => $jenis->kategori_sampah_id,
            'jenis_sampah_id' => $jenis->id,
            'nama_pengepul' => 'Pak Tono',
            'berat_kg' => 1,
            'harga_jual_per_kg' => 3000,
            'total_harga' => 3000,
            'tanggal_penjualan' => now()->toDateString(),
        ]);

        $this->assertSame(4.0, StokSampah::stokTersedia($jenis->id));
        $this->assertSame(4.0, StokSampah::total());

        $admin = $this->makeUser('Admin', 'admin');
        $this->actingAs($admin)
            ->get(route('admin.stok.index'))
            ->assertOk()
            ->assertSee('Plastik Botol PET')
            ->assertSee('4,00');
    }

    public function test_stok_tidak_pernah_negatif_saat_penjualan_melebihi_setoran(): void
    {
        $jenis = $this->buatJenis();
        $this->setoran($jenis->id, 1);

        PenjualanSampah::create([
            'kategori_sampah_id' => $jenis->kategori_sampah_id,
            'jenis_sampah_id' => $jenis->id,
            'nama_pengepul' => 'Pak Tono',
            'berat_kg' => 5,
            'harga_jual_per_kg' => 3000,
            'total_harga' => 15000,
            'tanggal_penjualan' => now()->toDateString(),
        ]);

        $this->assertSame(0.0, StokSampah::stokTersedia($jenis->id));
        $this->assertSame(0.0, StokSampah::total());
    }

    public function test_penjualan_petugas_melebihi_stok_ditolak(): void
    {
        $petugas = $this->makeUser('Andi', 'petugas_lapangan');
        $jenis = $this->buatJenis();
        $this->setoran($jenis->id, 2);

        $response = $this->actingAs($petugas)->post(route('petugas.penjualan.store'), [
            'items' => [
                ['jenis_sampah_id' => $jenis->id, 'berat_kg' => 5, 'harga_jual_per_kg' => 3000],
            ],
            'tanggal_penjualan' => now()->toDateString(),
        ]);

        $response->assertSessionHasErrors('items');
        $this->assertDatabaseCount('penjualan_sampah', 0);
    }

    public function test_penjualan_petugas_item_ganda_jenis_sama_tidak_melebihi_stok(): void
    {
        $petugas = $this->makeUser('Andi', 'petugas_lapangan');
        $jenis = $this->buatJenis();
        $this->setoran($jenis->id, 2);

        $this->actingAs($petugas)->post(route('petugas.penjualan.store'), [
            'items' => [
                ['jenis_sampah_id' => $jenis->id, 'berat_kg' => 1.5, 'harga_jual_per_kg' => 3000],
                ['jenis_sampah_id' => $jenis->id, 'berat_kg' => 1, 'harga_jual_per_kg' => 3000],
            ],
            'tanggal_penjualan' => now()->toDateString(),
        ])->assertSessionHasErrors('items');

        $this->assertDatabaseCount('penjualan_sampah', 0);
    }

    public function test_penjualan_bendahara_melebihi_stok_ditolak(): void
    {
        $bendahara = $this->makeUser('Bendahara', 'bendahara');
        $jenis = $this->buatJenis();
        $this->setoran($jenis->id, 2);

        $this->actingAs($bendahara)->post(route('bendahara.penjualan.store'), [
            'items' => [
                ['jenis_sampah_id' => $jenis->id, 'berat_kg' => 3, 'harga_jual_per_kg' => 3000],
            ],
            'tanggal_penjualan' => now()->toDateString(),
        ])->assertSessionHasErrors('items');

        $this->assertDatabaseCount('penjualan_sampah', 0);
    }

    public function test_penjualan_yang_sesuai_stok_diterima(): void
    {
        $petugas = $this->makeUser('Andi', 'petugas_lapangan');
        $jenis = $this->buatJenis();
        $this->setoran($jenis->id, 2);

        $this->actingAs($petugas)->post(route('petugas.penjualan.store'), [
            'items' => [
                ['jenis_sampah_id' => $jenis->id, 'berat_kg' => 1, 'harga_jual_per_kg' => 3000],
            ],
            'tanggal_penjualan' => now()->toDateString(),
        ])->assertRedirect(route('petugas.penjualan.index'));

        $this->assertDatabaseCount('penjualan_sampah', 1);
        $this->assertSame(1.0, StokSampah::stokTersedia($jenis->id));
    }
}