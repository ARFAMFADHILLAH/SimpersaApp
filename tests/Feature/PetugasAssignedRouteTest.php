<?php

namespace Tests\Feature;

use App\Models\Armada;
use App\Models\JenisSampah;
use App\Models\Pengangkutan;
use App\Models\Rute;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PetugasAssignedRouteTest extends TestCase
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

    private function makeWarga(string $name, int $ruteId): Warga
    {
        $user = User::factory()->create([
            'name' => $name,
            'role_id' => $this->makeRole('warga'),
            'status' => 'aktif',
        ]);
        return Warga::create([
            'user_id' => $user->id,
            'rute_id' => $ruteId,
            'no_warga' => 'WRG-' . strtoupper(str_replace(' ', '-', $name)),
            'no_hp' => '081234567890',
            'alamat_lengkap' => 'Jl. Uji No. 1',
        ]);
    }

    private function tugaskan(User $petugas, Warga $warga): Pengangkutan
    {
        $armada = Armada::create([
            'nama_kendaraan' => 'Truk Isuzu',
            'nomor_plat' => 'B ' . mt_rand(1000, 9999) . ' XYZ',
            'jenis_kendaraan' => 'Truk',
            'kapasitas_m3' => 8,
            'status_kondisi' => 'aktif',
        ]);

        $jenisSampah = JenisSampah::create([
            'nama_jenis' => 'Sampah Rumah Tangga',
            'tarif_per_kg' => 2000,
            'tarif_bulanan_flat' => 20000,
        ]);

        return Pengangkutan::create([
            'warga_id' => $warga->id,
            'armada_id' => $armada->id,
            'jenis_sampah_id' => $jenisSampah->id,
            'petugas_id' => $petugas->id,
            'tanggal_tugas' => today()->toDateString(),
            'status_tugas' => 'Belum dikerjakan',
        ]);
    }

    public function test_petugas_hanya_melihat_rute_dan_warga_yang_ditugaskan(): void
    {
        $andi = $this->makePetugas('Andi');
        $budi = $this->makePetugas('Budi');

        $ruteA = Rute::create(['nama_rute' => 'Rute A', 'hari_angkut' => 'Senin']);
        $ruteB = Rute::create(['nama_rute' => 'Rute B', 'hari_angkut' => 'Selasa']);

        $wargaRuteA = $this->makeWarga('Warga A', $ruteA->id);
        $wargaRuteB = $this->makeWarga('Warga B', $ruteB->id);

        $this->tugaskan($andi, $wargaRuteA);
        $this->tugaskan($budi, $wargaRuteB);

        // Andi hanya melihat Rute A di beranda, Rute & Jadwal, dan Input Sampah
        $response = $this->actingAs($andi)->get(route('petugas.dashboard'));
        $response->assertOk();
        $response->assertSee('Rute A');
        $response->assertDontSee('Rute B');
        $response->assertSee('Warga A');
        $response->assertDontSee('Warga B');

        $response = $this->actingAs($andi)->get(route('petugas.rute.index'));
        $response->assertOk();
        $response->assertSee('Rute A');
        $response->assertDontSee('Rute B');

        $response = $this->actingAs($andi)->get(route('petugas.rute.tugas'));
        $response->assertOk();
        $response->assertSee('Warga A');
        $response->assertDontSee('Warga B');

        // Budi hanya melihat Rute B
        $response = $this->actingAs($budi)->get(route('petugas.dashboard'));
        $response->assertOk();
        $response->assertSee('Rute B');
        $response->assertDontSee('Rute A');
    }

    public function test_petugas_tidak_bisa_membuka_detail_rute_milik_petugas_lain(): void
    {
        $andi = $this->makePetugas('Andi');
        $budi = $this->makePetugas('Budi');

        $ruteA = Rute::create(['nama_rute' => 'Rute A', 'hari_angkut' => 'Senin']);
        $ruteB = Rute::create(['nama_rute' => 'Rute B', 'hari_angkut' => 'Selasa']);

        $wargaRuteA = $this->makeWarga('Warga A', $ruteA->id);
        $wargaRuteB = $this->makeWarga('Warga B', $ruteB->id);

        $this->tugaskan($andi, $wargaRuteA);
        $this->tugaskan($budi, $wargaRuteB);

        $this->actingAs($andi)->get(route('petugas.rute.detail', $ruteA->id))->assertOk();
        $this->actingAs($andi)->get(route('petugas.rute.detail', $ruteB->id))->assertNotFound();
    }

    public function test_petugas_tanpa_penugasan_melihat_empty_state(): void
    {
        $andi = $this->makePetugas('Andi');
        $ruteA = Rute::create(['nama_rute' => 'Rute A', 'hari_angkut' => 'Senin']);
        $this->makeWarga('Warga A', $ruteA->id);

        $response = $this->actingAs($andi)->get(route('petugas.dashboard'));
        $response->assertOk();
        $response->assertSee('Belum Ada Penugasan');

        $response = $this->actingAs($andi)->get(route('petugas.rute.index'));
        $response->assertOk();
        $response->assertSee('Belum ada data rute yang terdaftar.');
    }

    public function test_petugas_mengupload_dokumentasi_dan_hasil_per_titik(): void
    {
        $andi = $this->makePetugas('Andi');
        $ruteA = Rute::create(['nama_rute' => 'Rute A', 'hari_angkut' => 'Senin']);
        $warga = $this->makeWarga('Warga A', $ruteA->id);
        $pengangkutan = $this->tugaskan($andi, $warga);

        $this->actingAs($andi)->post(route('petugas.pengangkutan.upload', $pengangkutan->id), [
            'foto_sebelum' => UploadedFile::fake()->image('sebelum.png'),
            'foto_sesudah' => UploadedFile::fake()->image('sesudah.png'),
            'volume_m3' => 2.5,
            'berat_kg' => 12,
            'catatan' => 'Selesai diangkut tanpa kendala',
        ])->assertRedirect();

        $this->assertDatabaseHas('pengangkutan', [
            'id' => $pengangkutan->id,
            'status_tugas' => 'Selesai',
            'volume_m3' => 2.5,
            'berat_kg' => 12,
            'catatan' => 'Selesai diangkut tanpa kendala',
        ]);

        $pengangkutan->refresh();
        $this->assertNotNull($pengangkutan->foto_sebelum);
        $this->assertNotNull($pengangkutan->foto_sesudah);
    }

    public function test_petugas_tidak_bisa_upload_dokumentasi_titik_petugas_lain(): void
    {
        $andi = $this->makePetugas('Andi');
        $budi = $this->makePetugas('Budi');

        $ruteA = Rute::create(['nama_rute' => 'Rute A', 'hari_angkut' => 'Senin']);
        $ruteB = Rute::create(['nama_rute' => 'Rute B', 'hari_angkut' => 'Selasa']);

        $wargaRuteA = $this->makeWarga('Warga A', $ruteA->id);
        $wargaRuteB = $this->makeWarga('Warga B', $ruteB->id);

        $this->tugaskan($andi, $wargaRuteA);
        $tugasBudi = $this->tugaskan($budi, $wargaRuteB);

        $this->actingAs($andi)->post(route('petugas.pengangkutan.upload', $tugasBudi->id), [
            'foto_sebelum' => UploadedFile::fake()->image('sebelum.png'),
            'foto_sesudah' => UploadedFile::fake()->image('sesudah.png'),
        ]);

        $this->assertDatabaseHas('pengangkutan', [
            'id' => $tugasBudi->id,
            'status_tugas' => 'Belum dikerjakan',
            'foto_sebelum' => null,
        ]);
    }

    public function test_petugas_melihat_tugas_urut_berdasarkan_urutan_warga(): void
    {
        $andi = $this->makePetugas('Andi');
        $ruteA = Rute::create(['nama_rute' => 'Rute A', 'hari_angkut' => 'Senin']);

        $wargaDua = $this->makeWarga('Warga Kedua', $ruteA->id);
        $wargaDua->urutan = 2;
        $wargaDua->save();

        $wargaSatu = $this->makeWarga('Warga Pertama', $ruteA->id);
        $wargaSatu->urutan = 1;
        $wargaSatu->save();

        $this->tugaskan($andi, $wargaDua);
        $this->tugaskan($andi, $wargaSatu);

        $response = $this->actingAs($andi)->get(route('petugas.rute.tugas'));
        $response->assertOk();
        $response->assertSeeInOrder(['Warga Pertama', 'Warga Kedua']);
    }

    public function test_admin_dapat_mengubah_urutan_warga(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Utama',
            'role_id' => $this->makeRole('admin'),
            'status' => 'aktif',
        ]);

        $ruteA = Rute::create(['nama_rute' => 'Rute A', 'hari_angkut' => 'Senin']);
        $wargaAtas = $this->makeWarga('Warga Atas', $ruteA->id);
        $wargaAtas->urutan = 1;
        $wargaAtas->save();
        $wargaBawah = $this->makeWarga('Warga Bawah', $ruteA->id);
        $wargaBawah->urutan = 2;
        $wargaBawah->save();

        // Turunkan warga atas -> posisi bertukar
        $this->actingAs($admin)
            ->post(route('admin.operasional.urut', $wargaAtas->id), ['arah' => 'down'])
            ->assertRedirect();

        $this->assertDatabaseHas('warga', ['id' => $wargaAtas->id, 'urutan' => 2]);
        $this->assertDatabaseHas('warga', ['id' => $wargaBawah->id, 'urutan' => 1]);
    }

    public function test_form_admin_warga_tanpa_input_manual_koordinat(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Utama',
            'role_id' => $this->makeRole('admin'),
            'status' => 'aktif',
        ]);

        $warga = $this->makeWarga('Warga Uji', Rute::create(['nama_rute' => 'Rute A', 'hari_angkut' => 'Senin'])->id);

        // Halaman index (form registrasi) tidak lagi menampilkan input koordinat manual
        $response = $this->actingAs($admin)->get(route('admin.warga.index'));
        $response->assertOk();
        $response->assertDontSee('Klik pada peta...');
        $response->assertDontSee('onclick="getLocation()"');
        $response->assertSee('mapWargaIndex');

        // Halaman edit juga menggunakan picker lokasi, bukan input manual
        $response = $this->actingAs($admin)->get(route('admin.warga.edit', $warga->id));
        $response->assertOk();
        $response->assertDontSee('onclick="cariDariAlamat()"');
        $response->assertDontSee('readonly');
        $response->assertSee('mapWargaEdit');
    }

    public function test_aplikasi_menggunakan_timezone_indonesia(): void
    {
        $this->assertSame('Asia/Jakarta', config('app.timezone'));
        $this->assertSame('Asia/Jakarta', now()->timezoneName);
    }
}
