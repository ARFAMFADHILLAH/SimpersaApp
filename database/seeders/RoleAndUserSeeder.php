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

        // 5. Akun Admin Kedua (sebelumnya Petugas Administrasi)
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

        // 7. Akun Warga
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

        // 8. Seed Data Wilayah Pelayanan
        $wilayahId = DB::table('wilayah_pelayanan')->value('id');
        if (!$wilayahId) {
            DB::table('wilayah_pelayanan')->insert([
                ['nama_wilayah' => 'Wilayah Pusat Kota', 'cakupan_area' => 'Kecamatan Pusat Kota', 'created_at' => now(), 'updated_at' => now()],
                ['nama_wilayah' => 'Wilayah Timur', 'cakupan_area' => 'Kecamatan Timur', 'created_at' => now(), 'updated_at' => now()],
                ['nama_wilayah' => 'Wilayah Barat', 'cakupan_area' => 'Kecamatan Barat', 'created_at' => now(), 'updated_at' => now()],
            ]);
            $wilayahId = DB::table('wilayah_pelayanan')->value('id');
        }

        // 9. Seed Data Rute
        $ruteId = DB::table('rute')->value('id');
        if (!$ruteId) {
            DB::table('rute')->insert([
                ['nama_rute' => 'Rute A - Perumahan Indah', 'hari_angkut' => 'Senin & Kamis', 'keterangan' => 'Perumahan Indah dan sekitarnya', 'created_at' => now(), 'updated_at' => now()],
                ['nama_rute' => 'Rute B - Pasar & Komersil', 'hari_angkut' => 'Selasa & Jumat', 'keterangan' => 'Area pasar dan pertokoan', 'created_at' => now(), 'updated_at' => now()],
                ['nama_rute' => 'Rute C - Perumahan Baru', 'hari_angkut' => 'Rabu & Sabtu', 'keterangan' => 'Perumahan Baru dan sekitarnya', 'created_at' => now(), 'updated_at' => now()],
            ]);
            $ruteId = DB::table('rute')->value('id');
        }

        // 10. Profil Warga (data di tabel warga)
        if ($wargaUserId && !DB::table('warga')->where('user_id', $wargaUserId)->exists()) {
            DB::table('warga')->insert([
                'user_id' => $wargaUserId,
                'no_warga' => 'WRG-' . date('Ymd') . '-001',
                'no_hp' => '081234567890',
                'alamat_lengkap' => 'Jl. Contoh No. 123, RT 01/02',
                'rute_id' => $ruteId,
                'wilayah_pelayanan_id' => $wilayahId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 11. Profil Mitra (Bank Sampah) - pemilik tunggal, tanpa login
        if (!DB::table('mitras')->exists()) {
            DB::table('mitras')->insert([
                'nama_mitra' => 'KISUCI',
                'no_hp' => '081234567890',
                'alamat_kontak' => 'Komunitas Iklim Sungai Cikeas',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('mitras')->where('id', DB::table('mitras')->orderBy('id')->value('id'))
                ->update([
                    'nama_mitra' => 'KISUCI',
                    'alamat_kontak' => 'Komunitas Iklim Sungai Cikeas',
                    'updated_at' => now(),
                ]);
        }
    }
}
