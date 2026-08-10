<?php

namespace Tests\Feature;

use App\Models\JenisSampah;
use App\Models\KategoriSampah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminMergedRoleTest extends TestCase
{
    use RefreshDatabase;

    private function makeRoles(): void
    {
        foreach (['admin', 'warga', 'petugas_lapangan', 'bendahara', 'owner'] as $r) {
            DB::table('roles')->updateOrInsert(['name' => $r], ['created_at' => now(), 'updated_at' => now()]);
        }
    }

    private function makeAdmin(): User
    {
        $this->makeRoles();
        $roleAdmin = DB::table('roles')->where('name', 'admin')->value('id');

        return User::factory()->create(['role_id' => $roleAdmin, 'status' => 'aktif']);
    }

    public function test_role_admin_bisa_akses_dashboard_dan_fitur_gabungan_pos(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($admin)->get(route('admin.users.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.warga.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.warga.create'))->assertOk();
        $this->actingAs($admin)->get(route('admin.kategori-sampah.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.jenis-sampah.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.gaji.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.sistem.index'))->assertOk();
    }

    public function test_role_admin_bisa_kelola_master_data_sampah(): void
    {
        $admin = $this->makeAdmin();

        $kategori = KategoriSampah::create(['nama_kategori' => 'Organik', 'keterangan' => 'Sisa organik']);
        $jenis = JenisSampah::create([
            'kategori_sampah_id' => $kategori->id,
            'nama_jenis' => 'Sisa Makanan',
            'tarif_per_kg' => 1500,
            'tarif_jual_per_kg' => 2500,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.jenis-sampah.update', $jenis->id), [
            'nama_jenis' => 'Sisa Makanan',
            'kategori_sampah_id' => $kategori->id,
            'tarif_per_kg' => 1600,
            'tarif_jual_per_kg' => 2600,
        ]);
        $response->assertRedirect(route('admin.jenis-sampah.index'));
        $this->assertDatabaseHas('jenis_sampah_dan_tarif', ['id' => $jenis->id, 'tarif_per_kg' => 1600]);

        $response = $this->actingAs($admin)->put(route('admin.kategori-sampah.update', $kategori->id), [
            'nama_kategori' => 'Organik Sisa',
            'keterangan' => 'Sisa organik rumah tangga',
        ]);
        $response->assertRedirect(route('admin.kategori-sampah.index'));
        $this->assertDatabaseHas('kategori_sampah', ['id' => $kategori->id, 'nama_kategori' => 'Organik Sisa']);
    }

    public function test_role_admin_tidak_mengakses_area_role_lain(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->get(route('petugas.dashboard'))->assertForbidden();
        $this->actingAs($admin)->get(route('bendahara.dashboard'))->assertForbidden();
        $this->actingAs($admin)->get(route('owner.dashboard'))->assertForbidden();
    }

    public function test_role_admin_dan_role_asetetap_satu_entitas(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
        $this->assertSame('admin', strtolower($admin->role->name));
    }
}
