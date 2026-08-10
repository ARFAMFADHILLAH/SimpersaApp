<?php

namespace Tests\Feature;

use App\Models\PenarikanSaldo;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TabunganFlowTest extends TestCase
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

    private function buatBendahara(): User
    {
        return User::factory()->create([
            'name' => 'Bendahara',
            'role_id' => $this->makeRole('bendahara'),
            'status' => 'aktif',
        ]);
    }

    private function buatWargaDenganSaldo(float $saldo): array
    {
        $user = User::factory()->create([
            'name' => 'Budi Warga',
            'role_id' => $this->makeRole('warga'),
            'status' => 'aktif',
        ]);
        $warga = Warga::create([
            'user_id' => $user->id,
            'no_warga' => 'WRG-POS-001',
            'no_hp' => '081234567890',
            'alamat_lengkap' => 'Jl. Uji No. 1',
            'saldo_tabungan' => $saldo,
        ]);

        return [$user, $warga];
    }

    public function test_penarikan_diproses_saldo_tabungan_belum_berkurang(): void
    {
        $bendahara = $this->buatBendahara();
        [, $warga] = $this->buatWargaDenganSaldo(50000);

        $response = $this->actingAs($bendahara)->post(route('bendahara.tabungan.penarikan.store'), [
            'warga_id' => $warga->id,
            'nominal' => 10000,
            'tanggal_penarikan' => now()->toDateString(),
            'catatan' => 'Penarikan tunai di konter',
        ]);

        $response->assertRedirect(route('bendahara.tabungan.index'));
        $this->assertDatabaseHas('penarikan_saldo', [
            'warga_id' => $warga->id,
            'nominal' => 10000,
            'status' => 'Diproses',
        ]);
        $this->assertSame(50000, (int) $warga->refresh()->saldo_tabungan);
    }

    public function test_penarikan_melebihi_saldo_ditolak(): void
    {
        $bendahara = $this->buatBendahara();
        [, $warga] = $this->buatWargaDenganSaldo(5000);

        $response = $this->actingAs($bendahara)->post(route('bendahara.tabungan.penarikan.store'), [
            'warga_id' => $warga->id,
            'nominal' => 10000,
            'tanggal_penarikan' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('bendahara.tabungan.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('penarikan_saldo', ['warga_id' => $warga->id]);
    }

    public function test_penarikan_setelah_ditarik_mengurangi_saldo_dan_tidak_bisa_double(): void
    {
        $bendahara = $this->buatBendahara();
        [, $warga] = $this->buatWargaDenganSaldo(50000);

        $penarikan = PenarikanSaldo::create([
            'warga_id' => $warga->id,
            'nominal' => 20000,
            'tanggal_penarikan' => now()->toDateString(),
            'status' => 'Diproses',
        ]);

        $this->actingAs($bendahara)->put(route('bendahara.tabungan.penarikan.ditarik', $penarikan->id))
            ->assertRedirect(route('bendahara.tabungan.index'));

        $this->assertSame('Ditarik', $penarikan->refresh()->status);
        $this->assertSame(30000, (int) $warga->refresh()->saldo_tabungan);

        // Panggilan kedua tidak boleh mengurangi saldo lagi
        $this->actingAs($bendahara)->put(route('bendahara.tabungan.penarikan.ditarik', $penarikan->id));
        $this->assertSame(30000, (int) $warga->refresh()->saldo_tabungan);
    }

    public function test_penarikan_dengan_nominal_di_bawah_minimum_ditolak(): void
    {
        $bendahara = $this->buatBendahara();
        [, $warga] = $this->buatWargaDenganSaldo(5000);

        $this->actingAs($bendahara)->post(route('bendahara.tabungan.penarikan.store'), [
            'warga_id' => $warga->id,
            'nominal' => 999,
            'tanggal_penarikan' => now()->toDateString(),
        ])->assertSessionHasErrors('nominal');
    }

    public function test_bendahara_tabungan_menampilkan_saldo_warga_dan_riwayat_penarikan(): void
    {
        $bendahara = $this->buatBendahara();
        [$user, $warga] = $this->buatWargaDenganSaldo(25000);

        PenarikanSaldo::create([
            'warga_id' => $warga->id,
            'nominal' => 5000,
            'tanggal_penarikan' => now()->toDateString(),
            'status' => 'Diproses',
        ]);

        $response = $this->actingAs($bendahara)->get(route('bendahara.tabungan.index'));
        $response->assertOk();
        $response->assertSee('Budi Warga');
        $response->assertSee('WRG-POS-001');
        $response->assertSee('Rp 25.000');
    }

    public function test_bendahara_dashboard_menampilkan_penarikan_menunggu(): void
    {
        $bendahara = $this->buatBendahara();
        [, $warga] = $this->buatWargaDenganSaldo(25000);

        PenarikanSaldo::create([
            'warga_id' => $warga->id,
            'nominal' => 5000,
            'tanggal_penarikan' => now()->toDateString(),
            'status' => 'Diproses',
        ]);

        $this->actingAs($bendahara)->get(route('bendahara.dashboard'))->assertOk();
    }
}
