<?php

namespace Tests\Feature;

use App\Models\JenisSampah;
use App\Models\KategoriSampah;
use App\Models\SetoranSampah;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ChatbotTest extends TestCase
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

    private function buatWarga(string $nama, float $saldo): Warga
    {
        $user = $this->makeUser($nama, 'warga');
        $warga = Warga::create([
            'user_id' => $user->id,
            'no_warga' => 'WRG-POS-' . uniqid(),
            'no_hp' => '081234567890',
            'alamat_lengkap' => 'Jl. Uji No. 1',
        ]);
        $warga->update(['saldo_tabungan' => $saldo]);

        return $warga;
    }

    private function tanya(string $pesan, ?User $user = null): string
    {
        $payload = ['pesan' => $pesan];

        $response = $user
            ? $this->actingAs($user)->postJson(route('chatbot.tanya'), $payload)
            : $this->postJson(route('chatbot.tanya'), $payload);

        $response->assertOk();

        return $response->json('jawaban');
    }

    public function test_tamu_menanyakan_fitur_mendapat_jawaban_faq(): void
    {
        $jawaban = $this->tanya('fitur apa saja?');

        $this->assertStringContainsString('SIMPERSA', $jawaban);
        $this->assertStringContainsString('admin', $jawaban);
    }

    public function test_tamu_menanyakan_data_disuruh_login(): void
    {
        $jawaban = $this->tanya('saldo saya');

        $this->assertStringContainsString('login', $jawaban);
    }

    public function test_pesan_kosong_ditolak(): void
    {
        $this->postJson(route('chatbot.tanya'), ['pesan' => ''])
            ->assertStatus(422);
    }

    public function test_pertanyaan_tidak_dikenal_mendapat_fallback(): void
    {
        $jawaban = $this->tanya('apa warna langit hari ini?');

        $this->assertStringContainsString('belum memahami', $jawaban);
    }

    public function test_sapaan_dibalas(): void
    {
        $admin = $this->makeUser('Admin', 'admin');
        $jawaban = $this->tanya('halo', $admin);

        $this->assertStringContainsString('Admin', $jawaban);
    }

    public function test_warga_bertanya_saldo_sendiri(): void
    {
        $warga = $this->buatWarga('Budi Warga', 25000);

        $jawaban = $this->tanya('saldo saya', $warga->user);

        $this->assertStringContainsString('25.000', $jawaban);
    }

    public function test_warga_tidak_boleh_melihat_saldo_warga_lain(): void
    {
        $warga = $this->buatWarga('Budi Warga', 25000);
        $lain = $this->buatWarga('Siti Warga', 10000);

        $jawaban = $this->tanya('saldo siti', $warga->user);

        $this->assertStringContainsString('hanya dapat melihat saldo', $jawaban);
    }

    public function test_admin_melihat_saldo_warga_lain(): void
    {
        $admin = $this->makeUser('Admin', 'admin');
        $this->buatWarga('Siti Warga', 30000);

        $jawaban = $this->tanya('saldo siti', $admin);

        $this->assertStringContainsString('30.000', $jawaban);
        $this->assertStringContainsString('Siti', $jawaban);
    }

    public function test_pertanyaan_stok_menjawab_total_dan_jenis(): void
    {
        $admin = $this->makeUser('Admin', 'admin');
        $warga = $this->buatWarga('Budi Warga', 0);

        $kategori = KategoriSampah::create(['nama_kategori' => 'Non-Organik']);
        $jenis = JenisSampah::create([
            'kategori_sampah_id' => $kategori->id,
            'nama_jenis' => 'Plastik Botol PET',
            'tarif_per_kg' => 2000,
            'tarif_jual_per_kg' => 3000,
        ]);

        SetoranSampah::create([
            'warga_id' => $warga->id,
            'jenis_sampah_id' => $jenis->id,
            'berat_kg' => 3,
            'harga_per_kg' => 2000,
            'total_bayar' => 6000,
            'tanggal_setoran' => now()->toDateString(),
        ]);

        $jawabanTotal = $this->tanya('berapa stok sampah?', $admin);
        $this->assertStringContainsString('3,00 kg', $jawabanTotal);

        $jawabanJenis = $this->tanya('stok plastik pet', $admin);
        $this->assertStringContainsString('Plastik Botol PET', $jawabanJenis);
        $this->assertStringContainsString('3,00', $jawabanJenis);
    }

    public function test_jumlah_nasabah_dijawab(): void
    {
        $petugas = $this->makeUser('Andi', 'petugas_lapangan');
        $this->buatWarga('Budi Warga', 0);
        $this->buatWarga('Siti Warga', 0);

        $jawaban = $this->tanya('berapa jumlah nasabah?', $petugas);

        $this->assertStringContainsString('2 warga', $jawaban);
    }

    public function test_transaksi_hari_ini_dijawab(): void
    {
        $petugas = $this->makeUser('Andi', 'petugas_lapangan');
        $warga = $this->buatWarga('Budi Warga', 0);

        $kategori = KategoriSampah::create(['nama_kategori' => 'Non-Organik']);
        $jenis = JenisSampah::create([
            'kategori_sampah_id' => $kategori->id,
            'nama_jenis' => 'Kertas & Kardus',
            'tarif_per_kg' => 1000,
            'tarif_jual_per_kg' => 1500,
        ]);

        SetoranSampah::create([
            'warga_id' => $warga->id,
            'jenis_sampah_id' => $jenis->id,
            'berat_kg' => 2,
            'harga_per_kg' => 1000,
            'total_bayar' => 2000,
            'tanggal_setoran' => now()->toDateString(),
        ]);

        $jawaban = $this->tanya('transaksi hari ini', $petugas);

        $this->assertStringContainsString('1 transaksi', $jawaban);
        $this->assertStringContainsString('2,00 kg', $jawaban);
    }

    public function test_gaji_hanya_untuk_non_warga(): void
    {
        $owner = $this->makeUser('Owner', 'owner');
        $warga = $this->buatWarga('Budi Warga', 0);

        $jawabanOwner = $this->tanya('penggajian bulan ini', $owner);
        $this->assertStringContainsString('Gaji pokok', $jawabanOwner);

        $jawabanWarga = $this->tanya('gaji', $warga->user);
        $this->assertStringContainsString('hanya dapat diakses', $jawabanWarga);
    }
}