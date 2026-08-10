<?php

namespace Tests\Feature;

use App\Models\AbsensiPetugas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AbsensiFlowTest extends TestCase
{
    use RefreshDatabase;

    private function makePetugas(string $name): User
    {
        DB::table('roles')->updateOrInsert(
            ['name' => 'petugas_lapangan'],
            ['created_at' => now(), 'updated_at' => now()]
        );

        return User::factory()->create([
            'name' => $name,
            'role_id' => DB::table('roles')->where('name', 'petugas_lapangan')->value('id'),
            'status' => 'aktif',
        ]);
    }

    private function fotoWajah(): UploadedFile
    {
        return UploadedFile::fake()->image('wajah.jpg', 200, 200);
    }

    public function test_petugas_clock_in_menyimpan_absensi_hari_ini(): void
    {
        $petugas = $this->makePetugas('Andi');

        $response = $this->actingAs($petugas)->post(route('petugas.absensi.clockin'), [
            'foto_masuk' => $this->fotoWajah(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('absensi_petugas', [
            'user_id' => $petugas->id,
            'tanggal' => now()->toDateString(),
            'status' => 'hadir',
        ]);

        $absen = AbsensiPetugas::where('user_id', $petugas->id)->where('tanggal', now()->toDateString())->first();
        $this->assertNotNull($absen->jam_masuk);
        $this->assertNotNull($absen->foto_masuk);
    }

    public function test_halaman_absensi_petugas_menampilkan_kamera_realtime(): void
    {
        $petugas = $this->makePetugas('Andi');

        $response = $this->actingAs($petugas)->get(route('petugas.absensi.index'));

        $response->assertOk();
        $response->assertSee('Buka Kamera');
        $response->assertSee('foto_masuk');

        $this->actingAs($petugas)->post(route('petugas.absensi.clockin'), [
            'foto_masuk' => $this->fotoWajah(),
        ])->assertSessionHas('success');

        $response = $this->actingAs($petugas)->get(route('petugas.absensi.index'));
        $response->assertOk();
        $response->assertSee('foto_pulang');
        $response->assertSee('Buka Kamera');
    }

    public function test_petugas_tidak_bisa_clock_in_dua_kali(): void
    {
        $petugas = $this->makePetugas('Andi');

        $this->actingAs($petugas)->post(route('petugas.absensi.clockin'), [
            'foto_masuk' => $this->fotoWajah(),
        ])->assertSessionHas('success');

        $response = $this->actingAs($petugas)->post(route('petugas.absensi.clockin'), [
            'foto_masuk' => $this->fotoWajah(),
        ]);
        $response->assertSessionHas('error');

        $this->assertSame(1, AbsensiPetugas::where('user_id', $petugas->id)->count());
    }

    public function test_clock_in_tanpa_foto_wajah_ditolak(): void
    {
        $petugas = $this->makePetugas('Andi');

        $response = $this->actingAs($petugas)->post(route('petugas.absensi.clockin'));

        $response->assertSessionHasErrors('foto_masuk');
        $this->assertDatabaseMissing('absensi_petugas', ['user_id' => $petugas->id]);
    }

    public function test_petugas_tidak_bisa_clock_out_sebelum_clock_in(): void
    {
        $petugas = $this->makePetugas('Andi');

        $response = $this->actingAs($petugas)->post(route('petugas.absensi.clockout'), [
            'foto_pulang' => $this->fotoWajah(),
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('absensi_petugas', ['user_id' => $petugas->id]);
    }

    public function test_petugas_clock_out_menyimpan_jam_pulang(): void
    {
        $petugas = $this->makePetugas('Andi');

        $this->actingAs($petugas)->post(route('petugas.absensi.clockin'), [
            'foto_masuk' => $this->fotoWajah(),
        ])->assertSessionHas('success');

        $response = $this->actingAs($petugas)->post(route('petugas.absensi.clockout'), [
            'foto_pulang' => $this->fotoWajah(),
        ]);
        $response->assertSessionHas('success');

        $absen = AbsensiPetugas::where('user_id', $petugas->id)->where('tanggal', now()->toDateString())->first();
        $this->assertNotNull($absen->jam_pulang);
        $this->assertNotNull($absen->foto_pulang);
    }

    public function test_clock_out_tanpa_foto_wajah_ditolak(): void
    {
        $petugas = $this->makePetugas('Andi');

        $this->actingAs($petugas)->post(route('petugas.absensi.clockin'), [
            'foto_masuk' => $this->fotoWajah(),
        ])->assertSessionHas('success');

        $response = $this->actingAs($petugas)->post(route('petugas.absensi.clockout'));
        $response->assertSessionHasErrors('foto_pulang');

        $absen = AbsensiPetugas::where('user_id', $petugas->id)->where('tanggal', now()->toDateString())->first();
        $this->assertNull($absen->jam_pulang);
    }

    public function test_petugas_tidak_bisa_clock_out_dua_kali(): void
    {
        $petugas = $this->makePetugas('Andi');

        $this->actingAs($petugas)->post(route('petugas.absensi.clockin'), [
            'foto_masuk' => $this->fotoWajah(),
        ]);
        $this->actingAs($petugas)->post(route('petugas.absensi.clockout'), [
            'foto_pulang' => $this->fotoWajah(),
        ])->assertSessionHas('success');

        $response = $this->actingAs($petugas)->post(route('petugas.absensi.clockout'), [
            'foto_pulang' => $this->fotoWajah(),
        ]);
        $response->assertSessionHas('error');
    }

    public function test_admin_dan_bendahara_membuka_rekap_absensi(): void
    {
        foreach (['admin', 'bendahara'] as $name) {
            DB::table('roles')->updateOrInsert(
                ['name' => $name],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        $petugas = $this->makePetugas('Andi');

        AbsensiPetugas::create([
            'user_id' => $petugas->id,
            'tanggal' => now()->toDateString(),
            'jam_masuk' => '07:30:00',
            'jam_pulang' => '15:30:00',
            'status' => 'hadir',
        ]);

        $admin = User::factory()->create([
            'name' => 'Admin',
            'role_id' => DB::table('roles')->where('name', 'admin')->value('id'),
            'status' => 'aktif',
        ]);

        $bendahara = User::factory()->create([
            'name' => 'Bendahara',
            'role_id' => DB::table('roles')->where('name', 'bendahara')->value('id'),
            'status' => 'aktif',
        ]);

        $this->actingAs($admin)->get(route('admin.absensi.index'))->assertOk()->assertSee('Andi');
        $this->actingAs($bendahara)->get(route('bendahara.absensi.index'))->assertOk()->assertSee('Andi');
    }

    public function test_role_salah_tidak_bisa_akses_halaman_absensi(): void
    {
        DB::table('roles')->updateOrInsert(
            ['name' => 'warga'],
            ['created_at' => now(), 'updated_at' => now()]
        );

        $warga = User::factory()->create([
            'name' => 'Budi',
            'role_id' => DB::table('roles')->where('name', 'warga')->value('id'),
            'status' => 'aktif',
        ]);

        $this->actingAs($warga)->get(route('petugas.absensi.index'))->assertForbidden();
        $this->actingAs($warga)->get(route('admin.absensi.index'))->assertForbidden();
        $this->actingAs($warga)->get(route('bendahara.absensi.index'))->assertForbidden();
    }
}
