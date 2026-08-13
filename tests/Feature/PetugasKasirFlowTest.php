<?php

namespace Tests\Feature;

use App\Models\JenisSampah;
use App\Models\KategoriSampah;
use App\Models\Penggajian;
use App\Models\SetoranSampah;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PetugasKasirFlowTest extends TestCase
{
    use RefreshDatabase;

    private function makeRole(string $name): int
    {
        DB::table('roles')->updateOrInsert(
            ['name' => $name],
            ['created_at' => now(), 'updated_at' => now()]
        );

        return DB::table('roles')->where('name', $name)->value('id');
    }

    private function makePetugas(string $name): User
    {
        return User::factory()->create([
            'name' => $name,
            'role_id' => $this->makeRole('petugas_lapangan'),
            'status' => 'aktif',
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

    private function buatWarga(string $nama = 'Budi Warga'): Warga
    {
        $user = User::factory()->create([
            'name' => $nama,
            'role_id' => $this->makeRole('warga'),
            'status' => 'aktif',
        ]);

        return Warga::create([
            'user_id' => $user->id,
            'no_warga' => 'WRG-POS-001',
            'no_hp' => '081234567890',
            'alamat_lengkap' => 'Jl. Uji No. 1, RT 01/02',
        ]);
    }

    public function test_kasir_form_pembelian_menampilkan_warga_dan_master_sampah(): void
    {
        $petugas = $this->makePetugas('Andi');
        $warga = $this->buatWarga('Budi Warga');
        $this->buatMaster();

        $response = $this->actingAs($petugas)->get(route('petugas.pembelian.index'));
        $response->assertOk();
        $response->assertSee('Transaksi Pembelian Sampah');
        $response->assertSee('Budi Warga (WRG-POS-001)');
        $response->assertSee('Non-Organik');
    }

    public function test_kasir_form_penjualan_menampilkan_dropdown_pengepul(): void
    {
        $petugas = $this->makePetugas('Andi');
        $this->buatMaster();

        $response = $this->actingAs($petugas)->get(route('petugas.penjualan.index'));
        $response->assertOk();
        $response->assertSee('nama_pengepul');
        $response->assertSee('Pencatatan Penjualan ke Pengepul');
    }

    public function test_nota_pembelian_dapat_dicetak_dengan_rincian_transaksi(): void
    {
        $petugas = $this->makePetugas('Andi');
        $warga = $this->buatWarga('Budi Warga');
        ['jenis' => $jenis] = $this->buatMaster();

        $setoran = SetoranSampah::create([
            'warga_id' => $warga->id,
            'jenis_sampah_id' => $jenis->id,
            'berat_kg' => 3,
            'harga_per_kg' => 2000,
            'total_bayar' => 6000,
            'tanggal_setoran' => now()->toDateString(),
        ]);

        $response = $this->actingAs($petugas)->get(route('petugas.pembelian.nota', $setoran->id));
        $response->assertOk();
        $response->assertSee('NOTA PENIMBANGAN');
        $response->assertSee('Budi Warga');
        $response->assertSee('Rp 6.000');
    }

    public function test_kasir_hanya_melihat_slip_gaji_milik_sendiri(): void
    {
        $andi = $this->makePetugas('Andi');
        $budi = $this->makePetugas('Budi');

        $gajiAndi = Penggajian::create([
            'petugas_id' => $andi->id,
            'bulan_gaji' => now()->format('Y-m'),
            'gaji_pokok' => 2000000,
            'insentif_lembur' => 100000,
            'potongan' => 0,
            'total_gaji_bersih' => 2100000,
            'status_pembayaran' => 'Pending',
        ]);
        $gajiBudi = Penggajian::create([
            'petugas_id' => $budi->id,
            'bulan_gaji' => now()->format('Y-m'),
            'gaji_pokok' => 2000000,
            'insentif_lembur' => 0,
            'potongan' => 0,
            'total_gaji_bersih' => 2000000,
            'status_pembayaran' => 'Pending',
        ]);

        $this->actingAs($andi)->get(route('petugas.gaji.index'))->assertOk()->assertSee('Gaji');
        $this->actingAs($andi)->get(route('petugas.gaji.slip', $gajiAndi->id))->assertOk();

        // Slip milik petugas lain tidak dapat diakses
        $this->actingAs($andi)->get(route('petugas.gaji.slip', $gajiBudi->id))->assertNotFound();
    }

    public function test_kasir_tidak_bisa_akses_halaman_bendahara_atau_admin(): void
    {
        $petugas = $this->makePetugas('Andi');

        $this->actingAs($petugas)->get(route('bendahara.dashboard'))->assertForbidden();
        $this->actingAs($petugas)->get(route('owner.dashboard'))->assertForbidden();
        $this->actingAs($petugas)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_form_admin_warga_tanpa_peta_dan_koordinat_gps(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Utama',
            'role_id' => $this->makeRole('admin'),
            'status' => 'aktif',
        ]);

        $warga = $this->buatWarga('Warga Uji');

        $response = $this->actingAs($admin)->get(route('admin.warga.index'));
        $response->assertOk();
        $response->assertDontSee('mapWargaIndex');
        $response->assertDontSee('Koordinat GPS');
        $response->assertDontSee('pakai lokasi Anda', false);
        $response->assertSee('Alamat Lengkap');

        $response = $this->actingAs($admin)->get(route('admin.warga.edit', $warga->id));
        $response->assertOk();
        $response->assertDontSee('mapWargaEdit');
        $response->assertDontSee('Pakai Lokasi Saya');
    }

    public function test_aplikasi_menggunakan_timezone_indonesia(): void
    {
        $this->assertSame('Asia/Jakarta', config('app.timezone'));
        $this->assertSame('Asia/Jakarta', now()->timezoneName);
    }
}
