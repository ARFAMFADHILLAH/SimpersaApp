<?php

namespace Tests\Feature;

use App\Models\Pengaduan;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminMergedRoleTest extends TestCase
{
    use RefreshDatabase;

    private function makeRoles(): void
    {
        foreach (['admin', 'warga', 'petugas_lapangan'] as $r) {
            DB::table('roles')->updateOrInsert(['name' => $r], ['created_at' => now(), 'updated_at' => now()]);
        }
    }

    private function makeAdmin(): User
    {
        $this->makeRoles();
        $roleAdmin = DB::table('roles')->where('name', 'admin')->value('id');
        return User::factory()->create(['role_id' => $roleAdmin, 'status' => 'aktif']);
    }

    public function test_role_admin_bisa_akses_dashboard_dan_fitur_gabungan(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($admin)->get(route('admin.master.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.operasional.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.operasional.rekap-volume'))->assertOk();
        $this->actingAs($admin)->get(route('admin.operasional.jadwal-rute'))->assertOk();
        $this->actingAs($admin)->get(route('admin.logistik.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.warga.create'))->assertOk();
        $this->actingAs($admin)->get(route('admin.warga.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.pengaduan.index'))->assertOk();
    }

    public function test_role_admin_bisa_verifikasi_dan_dispatch_pengaduan(): void
    {
        $admin = $this->makeAdmin();
        $this->makeRoles();
        $roleWarga = DB::table('roles')->where('name', 'warga')->value('id');
        $userWarga = User::factory()->create(['role_id' => $roleWarga, 'status' => 'aktif']);
        $warga = Warga::create([
            'user_id' => $userWarga->id,
            'no_warga' => 'WRG-TEST-002',
            'no_hp' => '081234567891',
            'alamat_lengkap' => 'Jl. Uji No. 2',
        ]);
        $pengaduan = Pengaduan::create([
            'warga_id' => $warga->id,
            'tipe_kendala' => 'Sampah Menumpuk',
            'catatan_lokasi' => 'Depan rumah',
            'status_respon' => 'Belum Dikerjakan',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.pengaduan.verifikasi', $pengaduan->id), [
            'catatan_verifikasi' => 'Dicek',
        ]);
        $response->assertRedirect(route('admin.pengaduan.show', $pengaduan->id));
        $this->assertDatabaseHas('pengaduan', ['id' => $pengaduan->id, 'status_respon' => 'Sedang Dikerjakan']);

        $this->makeRoles();
        $rolePetugas = DB::table('roles')->where('name', 'petugas_lapangan')->value('id');
        $petugas = User::factory()->create(['role_id' => $rolePetugas, 'status' => 'aktif']);

        $response = $this->actingAs($admin)->post(route('admin.pengaduan.dispatch', $pengaduan->id), [
            'petugas_id' => $petugas->id,
            'catatan_dispatch' => 'Tolong ditangani',
        ]);
        $response->assertRedirect(route('admin.pengaduan.show', $pengaduan->id));
        $this->assertDatabaseHas('pengaduan', ['id' => $pengaduan->id, 'petugas_id' => $petugas->id]);
    }

    public function test_role_admin_tidak_terdampak_halaman_administrasi_lama(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
        $this->assertSame('admin', strtolower($admin->role->name));
    }
}
