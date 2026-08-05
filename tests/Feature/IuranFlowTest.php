<?php

namespace Tests\Feature;

use App\Models\Iuran;
use App\Models\Notification;
use App\Models\PengaturanIuran;
use App\Models\Role;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class IuranFlowTest extends TestCase
{
    use RefreshDatabase;

    private function makeRoles(): void
    {
        foreach (['warga', 'bendahara', 'admin'] as $r) {
            DB::table('roles')->updateOrInsert(['name' => $r], ['created_at' => now(), 'updated_at' => now()]);
        }
    }

    private function makeWarga(): array
    {
        $this->makeRoles();
        $roleWarga = DB::table('roles')->where('name', 'warga')->value('id');
        $user = User::factory()->create(['role_id' => $roleWarga, 'status' => 'aktif']);
        $warga = Warga::create([
            'user_id' => $user->id,
            'no_warga' => 'WRG-TEST-001',
            'no_hp' => '081234567890',
            'alamat_lengkap' => 'Jl. Uji No. 1',
        ]);
        $iuran = Iuran::create([
            'warga_id' => $warga->id,
            'bulan_tagihan' => '2026-08',
            'jumlah_tagihan' => 20000,
            'denda' => 0,
            'status_pembayaran' => 'Belum Bayar',
        ]);
        return [$user, $warga, $iuran];
    }

    public function test_warga_pays_non_tunai_with_proof_and_status_becomes_sedang_diproses(): void
    {
        [$user, $warga, $iuran] = $this->makeWarga();
        $roleBendahara = DB::table('roles')->where('name', 'bendahara')->value('id');
        User::factory()->create(['role_id' => $roleBendahara, 'status' => 'aktif']);
        Storage::fake('public');

        $response = $this->actingAs($user)->post(route('warga.iuran.bayar', $iuran->id), [
            'metode_pembayaran' => 'Non-Tunai',
            'foto_bukti' => UploadedFile::fake()->image('bukti.png'),
        ]);

        $response->assertRedirect(route('warga.iuran.index'));

        $this->assertDatabaseHas('iuran', [
            'id' => $iuran->id,
            'status_pembayaran' => 'Sedang Diproses',
            'metode_pembayaran' => 'Non-Tunai',
        ]);

        $iuran->refresh();
        $this->assertNotNull($iuran->bukti_pembayaran);
        $this->assertNull($iuran->tanggal_bayar);
        Storage::disk('public')->assertExists($iuran->bukti_pembayaran);

        // Notifikasi ke bendahara terkirim
        $this->assertTrue(Notification::where('tipe', 'iuran_verifikasi')->exists());
    }

    public function test_non_tunai_without_proof_is_rejected(): void
    {
        [$user, $warga, $iuran] = $this->makeWarga();

        $response = $this->actingAs($user)->post(route('warga.iuran.bayar', $iuran->id), [
            'metode_pembayaran' => 'Non-Tunai',
        ]);

        $response->assertSessionHasErrors('foto_bukti');
        $this->assertDatabaseHas('iuran', ['id' => $iuran->id, 'status_pembayaran' => 'Belum Bayar']);
    }

    public function test_tunai_payment_also_goes_to_sedang_diproses_for_treasurer_verification(): void
    {
        [$user, $warga, $iuran] = $this->makeWarga();

        $response = $this->actingAs($user)->post(route('warga.iuran.bayar', $iuran->id), [
            'metode_pembayaran' => 'Tunai',
        ]);

        $response->assertRedirect(route('warga.iuran.index'));
        $this->assertDatabaseHas('iuran', [
            'id' => $iuran->id,
            'status_pembayaran' => 'Sedang Diproses',
            'metode_pembayaran' => 'Tunai',
        ]);
    }

    public function test_bendahara_verifies_and_marks_lunas_then_kwitansi_is_printable(): void
    {
        [$user, $warga, $iuran] = $this->makeWarga();
        Storage::fake('public');

        // Warga kirim konfirmasi + bukti
        $this->actingAs($user)->post(route('warga.iuran.bayar', $iuran->id), [
            'metode_pembayaran' => 'Non-Tunai',
            'foto_bukti' => UploadedFile::fake()->image('bukti.png'),
        ]);

        $roleBendahara = DB::table('roles')->where('name', 'bendahara')->value('id');
        $bendahara = User::factory()->create(['role_id' => $roleBendahara, 'status' => 'aktif']);

        // Bendahara klik "Verifikasi & Lunas"
        $response = $this->actingAs($bendahara)->post(route('bendahara.iuran.bayar', $iuran->id));

        $response->assertRedirect(route('bendahara.iuran.index'));
        $this->assertDatabaseHas('iuran', [
            'id' => $iuran->id,
            'status_pembayaran' => 'Lunas',
            'metode_pembayaran' => 'Non-Tunai',
        ]);

        $iuran->refresh();
        $this->assertNotNull($iuran->tanggal_bayar);

        // Notifikasi lunas terkirim ke warga
        $this->assertTrue(Notification::where('user_id', $user->id)->where('tipe', 'iuran_lunas')->exists());

        // Kwitansi bisa dibuka warga + memuat bukti pembayaran
        $kwitansi = $this->actingAs($user)->get(route('warga.iuran.kwitansi', $iuran->id));
        $kwitansi->assertOk();
        $kwitansi->assertSee('LUNAS');
        $kwitansi->assertSee('Cetak / Simpan PDF');
        $kwitansi->assertSee('Lampiran Bukti Pembayaran');

        // Halaman iuran warga menampilkan status lunas
        $index = $this->actingAs($user)->get(route('warga.iuran.index'));
        $index->assertOk();
        $index->assertSee('LUNAS');
    }

    public function test_iuran_bayar_double_konfirmasi_ditolak(): void
    {
        [$user, $warga, $iuran] = $this->makeWarga();

        $this->actingAs($user)->post(route('warga.iuran.bayar', $iuran->id), [
            'metode_pembayaran' => 'Tunai',
        ]);

        $response = $this->actingAs($user)->post(route('warga.iuran.bayar', $iuran->id), [
            'metode_pembayaran' => 'Tunai',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('iuran', ['id' => $iuran->id, 'status_pembayaran' => 'Sedang Diproses']);
    }

    public function test_tunggakan_page_menampilkan_belum_bayar_dan_sedang_diproses(): void
    {
        [$user, $warga, $iuran] = $this->makeWarga();
        $roleBendahara = DB::table('roles')->where('name', 'bendahara')->value('id');
        $bendahara = User::factory()->create(['role_id' => $roleBendahara, 'status' => 'aktif']);

        Iuran::create([
            'warga_id' => $warga->id,
            'bulan_tagihan' => '2026-09',
            'jumlah_tagihan' => 20000,
            'denda' => 0,
            'status_pembayaran' => 'Sedang Diproses',
            'metode_pembayaran' => 'Non-Tunai',
            'bukti_pembayaran' => 'bukti_iuran/test.png',
        ]);

        $response = $this->actingAs($bendahara)->get(route('bendahara.tunggakan'));
        $response->assertOk();
        $response->assertSee('2026-08');
        $response->assertSee('2026-09');
        $response->assertSee('MENUNGGU VERIFIKASI');
    }
}
