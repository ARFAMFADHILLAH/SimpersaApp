<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Daftar Role
        $roles = [
            'admin',
            'owner',
            'petugas_lapangan',
            'bendahara',
            'warga',
        ];

        $roleIds = [];
        foreach ($roles as $roleName) {
            // Menggunakan updateOrInsert agar aman dari error Duplicate Entry
            DB::table('roles')->updateOrInsert(
                ['name' => $roleName],
                ['created_at' => now(), 'updated_at' => now()]
            );

            // Ambil ID untuk relasi ke tabel users
            $roleIds[$roleName] = DB::table('roles')->where('name', $roleName)->value('id');
        }

        // 2. Akun Admin (Gabungan Super Admin & Administrasi)
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@sistemsampah.com'],
            [
                'role_id' => $roleIds['admin'],
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 3. Akun Owner
        DB::table('users')->updateOrInsert(
            ['email' => 'owner@sistemsampah.com'],
            [
                'role_id' => $roleIds['owner'],
                'name' => 'Owner Utama',
                'password' => Hash::make('owner123'),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 4. Akun Petugas Lapangan
        DB::table('users')->updateOrInsert(
            ['email' => 'andi@sistemsampah.com'],
            [
                'role_id' => $roleIds['petugas_lapangan'],
                'name' => 'Andi Petugas',
                'password' => Hash::make('petugas123'),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 5. Akun Admin Kedua 
        DB::table('users')->updateOrInsert(
            ['email' => 'Anton@sistemsampah.com'],
            [
                'role_id' => $roleIds['admin'],
                'name' => 'Anton Administrasi',
                'password' => Hash::make('admin123'),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        //6. Akun Bendahara
        DB::table('users')->updateOrInsert(
            ['email' => 'bendahara@sistemsampah.com'],
            [
                'role_id' => $roleIds['bendahara'],
                'name' => 'Bendahara',
                'password' => Hash::make('bendahara123'),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 7. Akun Warga (Nasabah Bank Sampah)
        $wargaUserId = null;
        DB::table('users')->updateOrInsert(
            ['email' => 'warga@sistemsampah.com'],
            [
                'role_id' => $roleIds['warga'],
                'name' => 'Budi Warga',
                'password' => Hash::make('warga123'),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $wargaUserId = DB::table('users')->where('email', 'warga@sistemsampah.com')->value('id');

        // 8. Profil Warga (data di tabel warga)
        if ($wargaUserId && !DB::table('warga')->where('user_id', $wargaUserId)->exists()) {
            DB::table('warga')->insert([
                'user_id' => $wargaUserId,
                'no_warga' => 'WRG-' . date('Ymd') . '-001',
                'no_hp' => '081234567890',
                'alamat_lengkap' => 'Jl. Contoh No. 123, RT 01/02',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 9. Seed Data Kategori Sampah (POS Bank Sampah)
        if (!DB::table('kategori_sampah')->exists()) {
            DB::table('kategori_sampah')->insert([
                ['nama_kategori' => 'Organik', 'keterangan' => 'Sampah sisa organik rumah tangga', 'created_at' => now(), 'updated_at' => now()],
                ['nama_kategori' => 'Non-Organik', 'keterangan' => 'Sampah anorganik bernilai jual', 'created_at' => now(), 'updated_at' => now()],
                ['nama_kategori' => 'Bahan Berbahaya & Beracun (B3)', 'keterangan' => 'Sampah B3 rumah tangga', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 10. Seed Data Jenis Sampah & Tarif (Harga Beli = bayar ke warga, Harga Jual = jual ke pengepul)
        if (!DB::table('jenis_sampah_dan_tarif')->exists()) {
            $kategoriIds = DB::table('kategori_sampah')->pluck('id', 'nama_kategori');
            DB::table('jenis_sampah_dan_tarif')->insert([
                ['kategori_sampah_id' => $kategoriIds['Organik'] ?? null, 'nama_jenis' => 'Sisa Makanan', 'tarif_per_kg' => 1500, 'tarif_jual_per_kg' => 2000, 'tarif_bulanan_flat' => 0, 'created_at' => now(), 'updated_at' => now()],
                ['kategori_sampah_id' => $kategoriIds['Organik'] ?? null, 'nama_jenis' => 'Daun Kering', 'tarif_per_kg' => 500, 'tarif_jual_per_kg' => 800, 'tarif_bulanan_flat' => 0, 'created_at' => now(), 'updated_at' => now()],
                ['kategori_sampah_id' => $kategoriIds['Non-Organik'] ?? null, 'nama_jenis' => 'Plastik Botol PET', 'tarif_per_kg' => 2000, 'tarif_jual_per_kg' => 3000, 'tarif_bulanan_flat' => 0, 'created_at' => now(), 'updated_at' => now()],
                ['kategori_sampah_id' => $kategoriIds['Non-Organik'] ?? null, 'nama_jenis' => 'Plastik Kemasan', 'tarif_per_kg' => 1200, 'tarif_jual_per_kg' => 1800, 'tarif_bulanan_flat' => 0, 'created_at' => now(), 'updated_at' => now()],
                ['kategori_sampah_id' => $kategoriIds['Non-Organik'] ?? null, 'nama_jenis' => 'Kertas & Kardus', 'tarif_per_kg' => 2500, 'tarif_jual_per_kg' => 3500, 'tarif_bulanan_flat' => 0, 'created_at' => now(), 'updated_at' => now()],
                ['kategori_sampah_id' => $kategoriIds['Non-Organik'] ?? null, 'nama_jenis' => 'Logam & Kaleng', 'tarif_per_kg' => 4000, 'tarif_jual_per_kg' => 5000, 'tarif_bulanan_flat' => 0, 'created_at' => now(), 'updated_at' => now()],
                ['kategori_sampah_id' => $kategoriIds['Non-Organik'] ?? null, 'nama_jenis' => 'Kaca & Botol Bekas', 'tarif_per_kg' => 1000, 'tarif_jual_per_kg' => 1500, 'tarif_bulanan_flat' => 0, 'created_at' => now(), 'updated_at' => now()],
                ['kategori_sampah_id' => $kategoriIds['Bahan Berbahaya & Beracun (B3)'] ?? null, 'nama_jenis' => 'Baterai & Elektronik', 'tarif_per_kg' => 3000, 'tarif_jual_per_kg' => 4500, 'tarif_bulanan_flat' => 0, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 11. Seed Data Pengaturan Gaji (Standar Gaji Pokok Petugas)
        if (!DB::table('pengaturan_gaji')->exists()) {
            DB::table('pengaturan_gaji')->insert([
                'gaji_pokok' => 2000000,
                'insentif_per_hadir' => 50000,
                'bonus_amount' => 500000,
                'minimal_hadir_bonus' => 15,
                'potongan_alpha_per_hari' => 75000,
                'potongan_izin_per_hari' => 30000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
