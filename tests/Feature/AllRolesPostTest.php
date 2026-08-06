<?php

namespace Tests\Feature;

use App\Models\Armada;
use App\Models\Iuran;
use App\Models\JenisSampah;
use App\Models\Mitra;
use App\Models\Pengaduan;
use App\Models\PengaturanGaji;
use App\Models\PengaturanIuran;
use App\Models\Penggajian;
use App\Models\Rute;
use App\Models\SetoranSampah;
use App\Models\Tps;
use App\Models\User;
use App\Models\Warga;
use App\Models\Wilayah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AllRolesPostTest extends TestCase
{
    use RefreshDatabase;

    private array $roles = [];

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['admin', 'owner', 'bendahara', 'petugas_lapangan', 'warga'] as $name) {
            DB::table('roles')->updateOrInsert(
                ['name' => $name],
                ['created_at' => now(), 'updated_at' => now()]
            );
            $this->roles[$name] = DB::table('roles')->where('name', $name)->value('id');
        }
    }

    private function makeUser(string $name, string $role): User
    {
        return User::factory()->create([
            'name' => $name,
            'role_id' => $this->roles[$role],
            'status' => 'aktif',
        ]);
    }

    private function buatDataDasar(): array
    {
        $admin = $this->makeUser('Admin', 'admin');
        $owner = $this->makeUser('Owner', 'owner');
        $bendahara = $this->makeUser('Bendahara', 'bendahara');
        $petugas = $this->makeUser('Andi', 'petugas_lapangan');
        $wargaUser = $this->makeUser('Budi', 'warga');

        $wilayah = Wilayah::create(['nama_wilayah' => 'Wilayah Pusat', 'cakupan_area' => 'Pusat Kota']);
        $rute = Rute::create(['nama_rute' => 'Rute A', 'hari_angkut' => 'Senin']);
        $warga = Warga::create([
            'user_id' => $wargaUser->id,
            'no_warga' => 'WRG-001',
            'no_hp' => '081234567890',
            'alamat_lengkap' => 'Jl. Uji No. 1',
            'rute_id' => $rute->id,
            'wilayah_pelayanan_id' => $wilayah->id,
        ]);
        $armada = Armada::create([
            'nama_kendaraan' => 'Truk Isuzu',
            'nomor_plat' => 'B 9999 ABC',
            'jenis_kendaraan' => 'Truk',
            'kapasitas_m3' => 8,
            'status_kondisi' => 'aktif',
        ]);
        $jenisSampah = JenisSampah::create([
            'nama_jenis' => 'Sampah Rumah Tangga',
            'tarif_per_kg' => 2000,
            'tarif_bulanan_flat' => 20000,
        ]);
        $tps = Tps::create([
            'nama_tps' => 'TPS Utama',
            'lokasi_koordinat' => '-6.2,106.8',
            'kapasitas_maksimal_m3' => '50',
        ]);
        $iuran = Iuran::create([
            'warga_id' => $warga->id,
            'bulan_tagihan' => now()->format('Y-m'),
            'jumlah_tagihan' => 20000,
            'status_pembayaran' => 'Belum Bayar',
        ]);
        $pengaduan = Pengaduan::create([
            'warga_id' => $warga->id,
            'tipe_kendala' => 'Tumpukan Sampah',
            'catatan_lokasi' => 'Jl. Uji No. 1',
            'status_respon' => 'Belum Dikerjakan',
        ]);
        $penggajian = Penggajian::create([
            'petugas_id' => $petugas->id,
            'bulan_gaji' => now()->format('Y-m'),
            'gaji_pokok' => 1500000,
            'total_gaji_bersih' => 1500000,
            'status_pembayaran' => 'Pending',
        ]);

        $mitra = Mitra::create([
            'nama_mitra' => 'Mitra Utama Bank Sampah',
            'no_hp' => '081234567890',
            'alamat_kontak' => 'Jl. Uji No. 2',
        ]);

        return compact('admin', 'owner', 'bendahara', 'petugas', 'wargaUser', 'wilayah', 'rute', 'warga', 'armada', 'jenisSampah', 'tps', 'iuran', 'pengaduan', 'penggajian', 'mitra');
    }

    public function test_admin_crud_master_data(): void
    {
        $d = $this->buatDataDasar();
        $admin = $d['admin'];

        $this->actingAs($admin)->post(route('admin.armada.store'), [
            'nama_kendaraan' => 'Truk Baru', 'nomor_plat' => 'B 4321 QWE', 'jenis_kendaraan' => 'Truk',
            'kapasitas_m3' => 10, 'status_kondisi' => 'aktif',
        ])->assertRedirect();
        $this->assertDatabaseHas('armada', ['nama_kendaraan' => 'Truk Baru']);

        $this->actingAs($admin)->post(route('admin.jenis-sampah.store'), [
            'nama_jenis' => 'Sampah Organik', 'tarif_per_kg' => 1500, 'tarif_bulanan_flat' => 15000,
        ])->assertRedirect();
        $this->assertDatabaseHas('jenis_sampah_dan_tarif', ['nama_jenis' => 'Sampah Organik']);

        $this->actingAs($admin)->post(route('admin.wilayah.store'), [
            'nama_wilayah' => 'Wilayah Timur', 'cakupan_area' => 'Kecamatan Timur',
        ])->assertRedirect();
        $this->assertDatabaseHas('wilayah_pelayanan', ['nama_wilayah' => 'Wilayah Timur']);

        $this->actingAs($admin)->post(route('admin.rute.store'), [
            'nama_rute' => 'Rute C', 'hari_angkut' => 'Rabu', 'keterangan' => 'Perumahan Baru',
        ])->assertRedirect();
        $this->assertDatabaseHas('rute', ['nama_rute' => 'Rute C']);

        $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Petugas Baru', 'email' => 'baru@sistemsampah.com',
            'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
            'role_id' => $this->roles['petugas_lapangan'], 'status' => 'aktif',
        ])->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'baru@sistemsampah.com']);
    }

    public function test_admin_mencatat_setoran_warga_dibayar_mitra(): void
    {
        $d = $this->buatDataDasar();
        $admin = $d['admin'];
        $mitra = $d['mitra'];

        // Profil mitra tunggal bisa diperbarui admin
        $this->actingAs($admin)->put(route('admin.mitra.update'), [
            'nama_mitra' => 'KISUCI',
            'no_hp' => '081234567890',
            'alamat_kontak' => 'Komunitas Iklim Sungai Cikeas',
        ])->assertRedirect();
        $this->assertDatabaseHas('mitras', ['id' => $mitra->id, 'nama_mitra' => 'KISUCI']);

        // Catat setoran 3 kg dengan tarif 2000/kg => total 6000, dibayar tunai otomatis oleh profil mitra
        $this->actingAs($admin)->post(route('admin.bank-sampah.store'), [
            'warga_id' => $d['warga']->id,
            'jenis_sampah_id' => $d['jenisSampah']->id,
            'berat_kg' => 3,
            'tanggal_setoran' => now()->toDateString(),
            'keterangan' => 'Setoran rutin mingguan',
        ])->assertRedirect();

        $this->assertDatabaseHas('setoran_sampahs', [
            'warga_id' => $d['warga']->id,
            'mitra_id' => $mitra->id,
            'jenis_sampah_id' => $d['jenisSampah']->id,
            'berat_kg' => 3,
            'harga_per_kg' => 2000,
            'total_bayar' => 6000,
        ]);

        // Warga penyetor bisa melihat riwayat setorannya sendiri
        $this->actingAs($d['wargaUser'])
            ->get(route('warga.bank-sampah.index'))
            ->assertOk()
            ->assertSee('KISUCI')
            ->assertSee('6.000');
    }

    public function test_admin_mendaftarkan_warga_dengan_alamat_lengkap(): void
    {
        $d = $this->buatDataDasar();
        $this->actingAs($d['admin'])->post(route('admin.warga.store'), [
            'name' => 'Warga Baru',
            'email' => 'wargabaru@sistemsampah.com',
            'no_hp' => '081298765432',
            'wilayah_pelayanan_id' => $d['wilayah']->id,
            'rute_id' => $d['rute']->id,
            'alamat_lengkap' => 'Jl. Melati No. 10, RT 03/05',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', ['email' => 'wargabaru@sistemsampah.com']);
        $user = User::where('email', 'wargabaru@sistemsampah.com')->first();
        $this->assertDatabaseHas('warga', ['user_id' => $user->id, 'alamat_lengkap' => 'Jl. Melati No. 10, RT 03/05']);
    }

    public function test_admin_pengaturan_iuran_dan_gaji_disenkripsi_ke_db(): void
    {
        $d = $this->buatDataDasar();
        $admin = $d['admin'];

        $this->actingAs($admin)->put(route('admin.iuran.update-pengaturan'), [
            'tarif_dasar_bulanan' => 25000,
            'persentase_denda_per_bulan' => 4,
            'nominal_denda_flat' => 7000,
            'tgl_jatuh_tempo' => 15,
        ])->assertRedirect();

        $this->assertDatabaseHas('pengaturan_iuran', [
            'tarif_dasar_bulanan' => 25000,
            'persentase_denda_per_bulan' => 4,
            'nominal_denda_flat' => 7000,
            'tgl_jatuh_tempo' => 15,
        ]);

        $this->actingAs($admin)->put(route('admin.gaji.update-pengaturan'), [
            'gaji_pokok' => 2000000,
            'insentif_per_hadir' => 50000,
            'bonus_amount' => 500000,
            'minimal_hadir_bonus' => 15,
            'potongan_alpha_per_hari' => 75000,
            'potongan_izin_per_hari' => 30000,
        ])->assertRedirect();

        $this->assertDatabaseHas('pengaturan_gaji', [
            'gaji_pokok' => 2000000,
            'insentif_per_hadir' => 50000,
            'bonus_amount' => 500000,
            'minimal_hadir_bonus' => 15,
            'potongan_alpha_per_hari' => 75000,
            'potongan_izin_per_hari' => 30000,
        ]);
    }

    public function test_admin_keputusan_kriteria_dan_skor(): void
    {
        $d = $this->buatDataDasar();
        $admin = $d['admin'];

        $this->actingAs($admin)->post(route('admin.keputusan.kriteria.store'), [
            'kode_kriteria' => 'C1', 'nama_kriteria' => 'Volume Sampah', 'bobot' => 0.30, 'jenis' => 'benefit',
        ])->assertRedirect();
        $this->assertDatabaseHas('kriteria_dss', ['kode_kriteria' => 'C1']);

        $kriteriaId = DB::table('kriteria_dss')->where('kode_kriteria', 'C1')->value('id');
        $tpsId = $d['tps']->id;

        $this->actingAs($admin)->post(route('admin.keputusan.skor.store'), [
            'skor' => [$tpsId => [$kriteriaId => 8]],
        ])->assertRedirect();

        $this->assertDatabaseHas('skor_alternatif_dss', [
            'tps_id' => $tpsId,
            'kriteria_id' => $kriteriaId,
        ]);
        $this->assertSame(8.0, (float) DB::table('skor_alternatif_dss')->where('tps_id', $tpsId)->value('nilai'));
    }

    public function test_admin_notifikasi_template_dan_jadwal(): void
    {
        $d = $this->buatDataDasar();
        $admin = $d['admin'];

        $this->actingAs($admin)->post(route('admin.notifikasi.template.store'), [
            'kode_template' => 'PENGINGAT_IURAN',
            'judul_template' => 'Pengingat Iuran',
            'saluran' => 'push',
            'subjek' => 'Iuran Belum Dibayar',
            'isi_pesan' => 'Segera bayar iuran Anda.',
        ])->assertRedirect();

        $templateId = DB::table('template_notifikasi')->where('kode_template', 'PENGINGAT_IURAN')->value('id');
        $this->assertNotNull($templateId);

        $this->actingAs($admin)->post(route('admin.notifikasi.jadwal.store'), [
            'template_id' => $templateId,
            'nama_jadwal' => 'Pengingat Jatuh Tempo',
            'pemicu' => 'bulanan',
            'waktu_kirim' => '08:00',
            'hari_ke' => 5,
        ])->assertRedirect();
        $this->assertDatabaseHas('jadwal_notifikasi', ['nama_jadwal' => 'Pengingat Jatuh Tempo']);
    }

    public function test_admin_verifikasi_dan_dispatch_pengaduan(): void
    {
        $d = $this->buatDataDasar();
        $admin = $d['admin'];

        $this->actingAs($admin)->post(route('admin.pengaduan.verifikasi', $d['pengaduan']->id), [
            'catatan_verifikasi' => 'OK',
        ])->assertRedirect();

        $this->actingAs($admin)->post(route('admin.pengaduan.dispatch', $d['pengaduan']->id), [
            'petugas_id' => $d['petugas']->id,
        ])->assertRedirect();

        $pengaduan = $d['pengaduan']->refresh();
        $this->assertSame('Sedang Dikerjakan', $pengaduan->status_respon);
        $this->assertSame($d['petugas']->id, $pengaduan->petugas_id);
    }

    public function test_admin_tugaskan_petugas_dan_update_master(): void
    {
        $d = $this->buatDataDasar();
        $admin = $d['admin'];

        $this->actingAs($admin)->post(route('admin.operasional.tugaskan'), [
            'rute_id' => $d['rute']->id,
            'petugas_id' => $d['petugas']->id,
            'tanggal_tugas' => today()->toDateString(),
            'armada_id' => $d['armada']->id,
        ])->assertRedirect();
        $this->assertDatabaseHas('pengangkutan', ['warga_id' => $d['warga']->id, 'petugas_id' => $d['petugas']->id]);

        $this->actingAs($admin)->put(route('admin.master.warga.update', $d['warga']->id), [
            'no_hp' => '081300000000', 'alamat_lengkap' => 'Jl. Edit No. 9',
        ])->assertRedirect();
        $this->assertDatabaseHas('warga', ['id' => $d['warga']->id, 'alamat_lengkap' => 'Jl. Edit No. 9']);

        $this->actingAs($admin)->put(route('admin.master.armada.update', $d['armada']->id), [
            'nama_kendaraan' => 'Truk Edit', 'nomor_plat' => 'B 1111 XXX',
            'jenis_kendaraan' => 'Truk', 'status_kondisi' => 'rusak',
        ])->assertRedirect();
        $this->assertDatabaseHas('armada', ['id' => $d['armada']->id, 'status_kondisi' => 'rusak']);
    }

    public function test_bendahara_generate_dan_bayar_iuran(): void
    {
        $d = $this->buatDataDasar();
        PengaturanIuran::firstOrCreate([
            'tarif_dasar_bulanan' => 20000, 'persentase_denda_per_bulan' => 5,
            'nominal_denda_flat' => 5000, 'tgl_jatuh_tempo' => 10,
        ]);
        $bendahara = $d['bendahara'];

        $this->actingAs($bendahara)->post(route('bendahara.iuran.generate'))->assertRedirect();
        $this->assertDatabaseHas('iuran', ['warga_id' => $d['warga']->id, 'bulan_tagihan' => now()->format('Y-m')]);

        $this->actingAs($bendahara)->post(route('bendahara.iuran.bayar', $d['iuran']->id), [
            'metode_pembayaran' => 'Tunai',
        ])->assertRedirect();
        $this->assertDatabaseHas('iuran', ['id' => $d['iuran']->id, 'status_pembayaran' => 'Lunas']);
    }

    public function test_bendahara_proses_gaji_menggunakan_parameter_pengaturan(): void
    {
        $d = $this->buatDataDasar();
        PengaturanGaji::ambil()->update([
            'gaji_pokok' => 2000000,
            'insentif_per_hadir' => 50000,
            'bonus_amount' => 500000,
            'minimal_hadir_bonus' => 15,
            'potongan_alpha_per_hari' => 75000,
            'potongan_izin_per_hari' => 30000,
        ]);

        $bulan = now()->subMonth()->format('Y-m');
        DB::table('absensi_petugas')->insert([
            ['user_id' => $d['petugas']->id, 'tanggal' => now()->subMonth()->startOfMonth()->addDays(1)->toDateString(), 'status' => 'hadir'],
            ['user_id' => $d['petugas']->id, 'tanggal' => now()->subMonth()->startOfMonth()->addDays(2)->toDateString(), 'status' => 'hadir'],
            ['user_id' => $d['petugas']->id, 'tanggal' => now()->subMonth()->startOfMonth()->addDays(3)->toDateString(), 'status' => 'alpha'],
            ['user_id' => $d['petugas']->id, 'tanggal' => now()->subMonth()->startOfMonth()->addDays(4)->toDateString(), 'status' => 'izin'],
        ]);

        $this->actingAs($d['bendahara'])->post(route('bendahara.penggajian.proses'), [
            'bulan_gaji' => $bulan,
        ])->assertRedirect();

        $this->assertDatabaseHas('penggajian', [
            'petugas_id' => $d['petugas']->id,
            'bulan_gaji' => $bulan,
            'gaji_pokok' => 2000000,
            'insentif_lembur' => 100000,
            'potongan' => 105000,
            'total_gaji_bersih' => 1995000,
        ]);
    }

    public function test_bendahara_bayar_gaji(): void
    {
        $d = $this->buatDataDasar();
        $this->actingAs($d['bendahara'])->post(route('bendahara.penggajian.bayar', $d['penggajian']->id))->assertRedirect();
        $this->assertDatabaseHas('penggajian', ['id' => $d['penggajian']->id, 'status_pembayaran' => 'Dibayar']);
    }

    public function test_petugas_absensi_laporan_dan_pengaduan(): void
    {
        $d = $this->buatDataDasar();
        $petugas = $d['petugas'];

        $this->actingAs($petugas)->post(route('petugas.absensi.clockin'), [
            'latitude' => '-6.2', 'longitude' => '106.8',
        ])->assertRedirect();
        $this->assertDatabaseHas('absensi_petugas', ['user_id' => $petugas->id, 'tanggal' => today()->toDateString(), 'jam_masuk' => now()->format('H:i:s')]);

        $this->actingAs($petugas)->post(route('petugas.absensi.clockout'), [])->assertRedirect();
        $this->assertDatabaseHas('absensi_petugas', ['user_id' => $petugas->id, 'tanggal' => today()->toDateString()]);
        $absensiHariIni = DB::table('absensi_petugas')->where('user_id', $petugas->id)->where('tanggal', today()->toDateString())->first();
        $this->assertNotNull($absensiHariIni->jam_pulang);

        $this->actingAs($petugas)->post(route('petugas.laporan.store'), [
            'tipe_kendala' => 'Truk Mogok', 'deskripsi' => 'Ban bocor', 'lokasi' => 'Jl. Uji',
        ])->assertRedirect();
        $this->assertDatabaseHas('laporan_kendalas', ['petugas_id' => $petugas->id, 'tipe_kendala' => 'Truk Mogok']);

        $this->actingAs($petugas)->post(route('petugas.pengaduan.update', $d['pengaduan']->id), [
            'status_respon' => 'Selesai',
            'catatan_petugas' => 'Sudah dibersihkan',
        ])->assertRedirect();
        $this->assertDatabaseHas('pengaduan', ['id' => $d['pengaduan']->id, 'status_respon' => 'Selesai']);
    }

    public function test_warga_pengaduan_dan_bayar_iuran(): void
    {
        $d = $this->buatDataDasar();
        $wargaUser = $d['wargaUser'];

        $this->actingAs($wargaUser)->post(route('warga.pengaduan.store'), [
            'tipe_kendala' => 'Bau Sampah',
            'catatan_lokasi' => 'Depan rumah',
            'latitude' => '-6.2', 'longitude' => '106.8',
        ])->assertRedirect();
        $this->assertDatabaseHas('pengaduan', ['warga_id' => $d['warga']->id, 'tipe_kendala' => 'Bau Sampah']);

        $this->actingAs($wargaUser)->post(route('warga.iuran.bayar', $d['iuran']->id), [
            'metode_pembayaran' => 'Non-Tunai',
            'foto_bukti' => UploadedFile::fake()->image('bukti.png'),
        ])->assertRedirect();
        $this->assertDatabaseHas('iuran', ['id' => $d['iuran']->id, 'status_pembayaran' => 'Sedang Diproses']);
    }

    public function test_owner_tidak_bisa_melakukan_input_posts(): void
    {
        $d = $this->buatDataDasar();
        $owner = $d['owner'];

        $this->actingAs($owner)->post('/owner/rute', [
            'nama_rute' => 'Rute X',
        ])->assertStatus(405);

        $this->actingAs($owner)->post('/owner/iuran/generate')->assertNotFound();

        $this->actingAs($owner)->post("/owner/iuran/bayar/{$d['iuran']->id}", [])->assertNotFound();
    }

    public function test_role_lama_manajer_tidak_lagi_berfungsi(): void
    {
        $manajer = User::factory()->create([
            'name' => 'Manajer Lama',
            'role_id' => DB::table('roles')->insertGetId(['name' => 'manajer', 'created_at' => now(), 'updated_at' => now()]),
            'status' => 'aktif',
        ]);

        $this->actingAs($manajer)->get(route('owner.dashboard'))->assertForbidden();
        $this->actingAs($manajer)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_redirect_setelah_login_ke_dashboard_owner(): void
    {
        $d = $this->buatDataDasar();
        $this->actingAs($d['owner'])->get('/dashboard')->assertRedirect(route('owner.dashboard'));
    }

    public function test_admin_pengingat_sistem_berjalan(): void
    {
        $d = $this->buatDataDasar();
        $this->actingAs($d['admin'])->post(route('admin.sistem.pengingat'))->assertRedirect();
    }
}