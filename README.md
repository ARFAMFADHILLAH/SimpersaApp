# Sistem Informasi Manajemen Persampahan Terintegrasi (SIMPERSA)

Sistem informasi pengelolaan sampah yang menyatukan seluruh alur kerja organisasi — dari pendaftaran pelanggan, penjadwalan pengangkutan, penagihan iuran, penggajian, keuangan, hingga pengambilan keputusan — dalam satu platform.

Dibangun dengan **Laravel 13.8** + **Blade** + **Tailwind CSS 3** + **Alpine.js**.

## Fitur

- 📊 Dashboard eksekutif dengan 4 KPI + 3 grafik 12 bulan (pembayaran, volume, biaya) + produktivitas petugas
- 🧾 Tagihan iuran otomatis setiap bulan + denda dinamis (max persentase vs flat)
- 💰 Penggajian otomatis dari absensi (gaji pokok, insentif, bonus, potongan — dikonfigurasi admin) + slip gaji
- 📍 Pengaduan masyarakat dengan geolokasi (latitude/longitude otomatis) + tautan Google Maps
- 🏦 Neraca kas, arus kas, dan laba rugi dengan grafik Chart.js
- 🧮 Decision Support System: kriteria berbobot + skor alternatif + evaluasi wilayah
- 🔔 Notifikasi in-app dengan badge belum dibaca di setiap peran
- ⚙️ Rekap volume harian/mingguan/bulanan + berat sampah (kg)
- ⏰ Otomatisasi terjadwal via cron (tagihan & notifikasi pengingat)
- 📱 Responsif (desktop & mobile) — sidebar + bottom-nav per peran
- 👑 Owner (read-only): pantau laporan pendapatan, volume sampah, kas masuk/keluar, status armada & petugas, kendala petugas

## Daftar Isi Halaman

- **Administrator** (`/admin`) — master data, tarif & denda, pengaturan gaji, DSS, template notifikasi, utilitas & backup
- **Owner** (`/owner`) — Pusat Pantauan read-only: DSS, 10 sub-laporan (termasuk kendala petugas), keuangan, armada, rute, pengaduan
- **Bendahara** (`/bendahara`) — iuran, tunggakan, penggajian, operasional, laporan keuangan
- **Petugas Lapangan** (`/petugas`) — rute & tugas harian, input volume/berat, dokumentasi, laporan kendala, absensi, gaji
- **Warga** (`/warga`) — profil, riwayat, pembayaran iuran + kwitansi, pengaduan, notifikasi

## Cara Menjalankan

```bash
composer install           # install dependency
cp .env.example .env       # atur koneksi database MySQL
php artisan key:generate
php artisan migrate --seed # migrasi + data demo (5 peran, 6 akun, 3 wilayah, 3 rute)
php artisan serve          # jalankan → http://localhost:8000
```

### Production

```bash
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

> ⚠️ Setelah `git pull` selalu jalankan `php artisan migrate` untuk migrasi terbaru. Setelah mengubah file Blade, jalankan `php artisan view:clear` — tanpa itu halaman lama yang ter-cache bisa tetap tampil.

## Teknologi

| Teknologi | Kegunaan |
|---|---|
| Laravel 13.8 (PHP 8.3) | Framework, routing, Eloquent ORM, scheduler |
| Blade + Tailwind CSS 3 + Alpine.js | Template, styling, interaktivitas UI |
| Vite 8 | Build asset frontend |
| MySQL/MariaDB | Basis data |
| Chart.js (CDN) | Grafik dashboard & laporan keuangan |
| Laravel Scheduler (cron) | Command terjadwal (tagihan & notifikasi) |

## Struktur Direktori

```
├── app/
│   ├── Console/Commands/       # iuran:generate-tagihan, notifikasi:kirim-pengingat
│   ├── Http/Controllers/
│   │   ├── Admin/              # area administrator
│   │   ├── Owner/              # area owner (pantauan read-only)
│   │   ├── Bendahara/          # area bendahara
│   │   └── Petugas_lapangan/   # area petugas lapangan
│   ├── Models/                 # 22 model
│   └── Providers/
├── database/
│   ├── migrations/             # 35 migrasi
│   └── seeders/                # role, user, wilayah, rute
├── resources/views/            # view per area + components (sidebar, bottom-nav)
├── routes/
│   └── web.php                 # ±172 rute terintegrasi
├── public/
└── PRESENTASI.md               # catatan presentasi lengkap
```

---

# Catatan Presentasi - SIMPERSA

Sistem Informasi Manajemen Persampahan Terintegrasi
*Untuk Meningkatkan Efisiensi Operasional dan Kualitas Pengambilan Keputusan*

---

## 1. Selayang Pandang

**SIMPERSA** adalah aplikasi web manajemen persampahan yang menyatukan seluruh alur kerja organisasi pengelola sampah - dari pendaftaran pelanggan, penjadwalan pengangkutan, penagihan iuran, penggajian petugas, pencatatan keuangan, hingga pengambilan keputusan - dalam satu sistem terintegrasi.

Masalah yang dipecahkan:

- Operasional lapangan berjalan tanpa pencatatan terpusat: volume, berat, dokumentasi, dan status pekerjaan tidak terdokumentasi.
- Penagihan iuran dan denda dihitung manual, rawan salah dan tidak konsisten.
- Laporan keuangan dan kinerja tersebar di banyak tempat, pimpinan kesulitan mengambil keputusan berbasis data.
- Pengaduan masyarakat tidak terlacak: siapa yang menangani, sejauh mana prosesnya, dan kapan selesai.

SIMPERSA menjawabnya dengan 13 modul fungsional yang saling terhubung, dilengkapi otomatisasi (tagihan bulanan, notifikasi pengingat terjadwal) serta dashboard eksekutif berbasis grafik.

### Aktor Sistem

| No | Peran | Ringkasan Tugas |
|----|-------|-----------------|
| 1 | Administrator | Kelola master data, pengaturan tarif & denda, pengaturan gaji, konfigurasi DSS, template & jadwal notifikasi, utilitas sistem & backup database |
| 2 | Owner / Pemilik | Pantau read-only seluruh operasional: laporan pendapatan, volume sampah, kas masuk/keluar, status armada & petugas, kendala petugas |
| 3 | Bendahara / Keuangan | Kelola iuran, tunggakan, proses penggajian, pengeluaran operasional, laporan keuangan & laba rugi |
| 4 | Petugas Lapangan | Kerjakan rute & tugas harian, input volume/berat sampah, dokumentasi foto, lapor kendala, absensi |
| 5 | Warga | Pantau riwayat, bayar iuran, unduh kwitansi, ajukan pengaduan, terima notifikasi |

---

## 2. Peta Peran ke Halaman Sistem

| Peran | Area Halaman | Fungsi Kunci |
|-------|--------------|--------------|
| Administrator | `/admin/*` | CRUD user, pelanggan, armada, wilayah, TPS, jenis sampah; pengaturan iuran & denda; pengaturan gaji; monitoring pengangkutan; laporan sistem; pengaturan DSS (kriteria & skor); template & jadwal notifikasi; backup database |
| Owner | `/owner/*` | Pusat Pantauan (read-only): DSS & evaluasi wilayah, laporan pendapatan/volume/kas/armada/petugas/kendala, keuangan, kondisi armada, rute & peta, log pengaduan |
| Bendahara | `/bendahara/*` | Iuran (generate, bayar, kwitansi, tunggakan), penggajian (proses, bayar, slip, rekap), operasional (input & verifikasi), laporan (laba rugi, neraca kas, arus kas, grafik) |
| Petugas Lapangan | `/petugas/*` | Dashboard, rute & tugas harian, update status + dokumentasi foto, input pengangkutan (volume/berat), penanganan pengaduan, gaji, laporan kendala, absensi clock-in/out |
| Warga | `/warga/*` | Dashboard, profil, riwayat pengangkutan, iuran (bayar + kwitansi), pengaduan (buat & pantau), notifikasi |

Setelah login, semua peran otomatis diarahkan ke dashboard masing-masing melalui halaman `/dashboard`.

---

## 3. Tumpukan Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Framework | Laravel 13.8 (PHP 8.3) |
| Template & UI | Blade + Tailwind CSS 3 + Alpine.js (sidebar dinamis per peran, bottom-nav mobile) |
| Build Frontend | Vite 8 |
| Basis Data | MySQL/MariaDB dengan Eloquent ORM |
| Grafik | Chart.js via CDN (dashboard manajer & admin, neraca kas, arus kas) |
| Otomatisasi | Scheduler Laravel (cron) untuk command artisan terjadwal |
| Autentikasi | Session Laravel + middleware role pada setiap area |

### Angka Proyek

- 22 model database
- 35 migrasi
- 62 controller
- ±172 rute
- 2 command artisan terjadwal (`iuran:generate-tagihan`, `notifikasi:kirim-pengingat`)
- 5 peran pengguna (administrator, owner, bendahara, petugas lapangan, warga)

---

## 4. Detail Fitur per Modul

### Modul 1 - Master Data

- Admin melakukan CRUD penuh: pengguna & staf, pelanggan, armada & kendaraan, wilayah pelayanan, TPS, jenis sampah.
- Petugas administrasi dapat memperbarui data pelanggan, TPS, dan armada tanpa hak hapus (pembagian wewenang per peran).
- Rute pengangkutan terdefinisi dengan hari angkut (misal: Rute A - Senin & Kamis) dan keterangan cakupan area.

### Modul 2 - Pelanggan

- Registrasi pelanggan menghasilkan **nomor pelanggan otomatis** dengan pola `PLG-{tanggal}-{urutan}` (contoh: `PLG-20260803-001`).
- Profil lengkap: nomor HP, alamat, rute, dan wilayah pelayanan.
- Status pelanggan aktif/nonaktif menjadi dasar penagihan iuran otomatis.
- Pelanggan melihat riwayat pembayaran iuran dan riwayat pengangkutan dari akunnya sendiri.

### Modul 3 - Monitoring Operasional

- Jadwal pengangkutan per rute; petugas administrasi menugaskan petugas ke rute.
- Status pekerjaan berlapis: **Belum Dikerjakan -> Sedang Dikerjakan -> Selesai**.
- Dokumentasi foto sebelum/sesudah pengangkutan diunggah petugas lapangan.
- Absensi harian dengan **clock-in / clock-out** yang menjadi bahan perhitungan gaji otomatis.

### Modul 4 - Pengelolaan Sampah

- Input data pengangkutan: **volume sampah (m3)** dan **berat sampah (kg)**, jenis sampah, lokasi pengangkutan.
- Rekap bertingkat: **harian**, **mingguan** (dikelompokkan dengan fungsi YEARWEEK), dan **bulanan**.
- Rekap mingguan menyertakan kolom berat sampah di samping volume.

### Modul 5 - Manajemen Iuran Sampah

- Pengaturan terpusat oleh admin: tarif dasar bulanan (default Rp20.000), tanggal jatuh tempo (1-31), **nominal denda flat** (default Rp5.000/bulan) dan **denda persentase** (default 5%/bulan).
- **Tagihan otomatis** dibuat setiap tanggal 1 pukul 00.00 untuk seluruh pelanggan aktif via command `iuran:generate-tagihan` (dapat dijalankan manual dengan opsi `--bulan=`).
- Pembayaran tunai/non-tunai; status tagihan berubah lunas otomatis setelah dibayar.
- **Cetak kwitansi** untuk setiap pembayaran.
- **Denda dinamis**: memakai nilai terbesar antara denda persentase (tagihan x % x bulan terlambat) dengan denda flat (flat x bulan terlambat), sehingga kedua skema tarif tetap adil bagi pembayar cepat.
- Monitoring tunggakan per pelanggan; pembayaran iuran memicu notifikasi ke pelanggan.

### Modul 6 - Penggajian Petugas

- Proses gaji bulanan otomatis dari data absensi: hadir, alpha, izin, sakit.
- **Parameter gaji dikonfigurasi admin** di halaman `Pengaturan Gaji` (`/admin/gaji/pengaturan`): gaji pokok, insentif per hadir, bonus kehadiran, minimal hadir untuk bonus, potongan alpha/hari, potongan izin/hari — semua dalam format Rupiah.
- **Rumus penghitungan**:
  - Gaji pokok (default Rp1.500.000)
  - Insentif kehadiran (default Rp25.000) x jumlah hadir
  - Bonus (default Rp200.000) jika hadir mencapai minimal hadir (default 20 hari)
  - Potongan alpha (default Rp50.000/hari) dan izin (default Rp20.000/hari)
  - Total bersih = pokok + insentif + bonus - potongan (tidak pernah negatif)
- **Cetak slip gaji** per petugas dan **rekap gaji** per periode.
- Pembayaran gaji memicu notifikasi ke petugas yang bersangkutan.

### Modul 7 - Manajemen Operasional

- Pencatatan pengeluaran: BBM kendaraan, servis, pergantian ban, pembelian alat.
- Verifikasi biaya oleh bendahara (data masuk berstatus menunggu, kemudian terverifikasi).
- Biaya operasional masuk ke laporan keuangan dan arus kas.

### Modul 8 - Keuangan

- Laporan **laba rugi sederhana**: pendapatan iuran dibandingkan biaya operasional & gaji.
- **Neraca kas**: posisi aset kas (saldo, piutang tunggakan) disajikan lengkap dengan grafik Chart.js.
- **Arus kas**: kas masuk, kas keluar, dan sisa kas per periode dengan grafik.
- Grafik pendapatan untuk analisis tren bulanan.

### Modul 9 - Dashboard Manajemen (Owner)

- **Pusat Pantauan Owner** bersifat read-only: kartu KPI — total pelanggan (aktif & menunggak), pendapatan iuran, biaya operasional + gaji, volume sampah terangkut (m3).
- **Status kesiapan armada & petugas** (siap/rusak, hadir/alpha).
- **Produktivitas petugas** - ringkasan capaian per petugas lapangan.
- **3 grafik 12 bulan**: grafik pembayaran, grafik volume sampah, grafik biaya operasional - dasar pengambilan keputusan berbasis data.
- Quick-links ke seluruh laporan pantauan (termasuk laporan kendala petugas) dan DSS.

### Modul 10 - Laporan

Sub-laporan yang tersedia:

- Laporan pelanggan (status & tunggakan) dan laporan iuran/pendapatan.
- **Laporan tunggakan**: daftar pelanggan beserta jumlah bulan dan nominal yang tertunggak.
- Laporan volume sampah (dengan berat), laporan keuangan (kas masuk/keluar gabungan), laporan gaji, laporan armada.
- **Laporan petugas**: kinerja/produktivitas per petugas lapangan.
- **Laporan kendala petugas**: kendala yang dilaporkan petugas di lapangan.
- **Rekap tahunan**: agregasi data 12 bulan.
- Seluruh laporan dapat dicetak.

### Modul 11 - Pengaduan Masyarakat

- Pelanggan mengajukan pengaduan: tipe kendala, catatan lokasi, dan foto bukti.
- **Titik lokasi geolokasi**: formulir menangkap koordinat latitude/longitude otomatis dari peramban (tombol "Ambil Lokasi"); jika peramban menolak izin, dipakai koordinat profil pelanggan sebagai fallback.
- Setiap pengaduan dapat dibuka langsung di **Google Maps** dari halaman administrasi dan petugas lapangan.
- **Alur penanganan lengkap**: pengaduan masuk -> verifikasi administrasi -> dispatch ke petugas lapangan -> update status penyelesaian -> notifikasi ke pelanggan.
- Pengaduan baru otomatis mengirim notifikasi ke admin dan petugas administrasi.

### Modul 12 - Pengambilan Keputusan (DSS)

- Konfigurasi **kriteria**: kode, nama, bobot, dan tipe (cost/benefit).
- **Input skor alternatif**: nilai skor setiap alternatif (TPS/wilayah) per kriteria diinput langsung melalui antarmuka admin.
- **Rekap evaluasi wilayah**: perbandingan alternatif beserta jumlah pelanggan per wilayah sebagai bahan rekomendasi prioritas.
- Dasar ilmiah pengambilan keputusan untuk prioritas penanganan wilayah.

### Modul 13 - Notifikasi

- **Notifikasi in-app** berbasis database dengan badge jumlah belum dibaca di setiap sidebar peran.
- **Event otomatis**: pengaduan baru masuk (ke admin & administrasi), gaji dibayarkan (ke petugas), iuran lunas (ke pelanggan).
- **Pengingat terjadwal** dari template & jadwal yang dikonfigurasi admin: jatuh tempo iuran, jadwal pengangkutan, jadwal servis kendaraan, notifikasi gaji, notifikasi tunggakan - dikirim otomatis via command `notifikasi:kirim-pengingat`.
- Fitur **tandai baca** dan **tandai semua dibaca**; setiap notifikasi dapat membawa tautan langsung ke halaman terkait.

---

## 5. Otomatisasi Terjadwal

| Perintah | Jadwal | Fungsi |
|----------|--------|--------|
| `iuran:generate-tagihan` | Setiap tanggal 1 pukul 00.00 | Membuat tagihan iuran bulanan untuk semua pelanggan aktif |
| `notifikasi:kirim-pengingat` | Setiap 30 menit | Mengirim notifikasi pengingat sesuai template & jadwal |

Setup cron pada server (dijalankan setiap menit):

```bash
* * * * * cd /path/ke/proyek && php artisan schedule:run >> /dev/null 2>&1
```

Tanpa cron, kedua perintah tetap bisa dijalankan manual lewat `php artisan`.

---

## 6. Skenario Demo Langsung

Berikut alur live demo yang paling representatif (sekitar 5-7 menit):

1. **Login warga** (`warga@sistemsampah.com`) -> buka Pengaduan -> klik **Buat Pengaduan**, isi kendala, tekan **Ambil Lokasi** (koordinat terisi otomatis), unggah foto, kirim.
2. **Login admin** (`admin@sistemsampah.com`) -> perhatikan **badge notifikasi** bertambah -> buka Pusat Notifikasi -> buka detail pengaduan (link Google Maps) -> verifikasi -> dispatch pengaduan ke petugas lapangan.
3. **Login petugas lapangan** (`andi@sistemsampah.com`) -> buka Tugas Harian -> ubah status menjadi Selesai + unggah dokumentasi.
5. **Login bendahara** (`bendahara@sistemsampah.com`) -> buka Laporan -> **Neraca Kas** dan **Arus Kas** (tunjukkan grafik) -> buka Penggajian, proses gaji, lalu bayar gaji (notifikasi terkirim ke petugas).
6. **Login owner** (`owner@sistemsampah.com`) -> **Pusat Pantauan**: tunjukkan 4 kartu KPI, status armada/petugas, dan 3 grafik 12 bulan -> buka Laporan -> **Pendapatan**, **Kendala Petugas**, **Rekap Tahunan**.
7. **Tutup dengan admin** -> Pengaturan DSS: tambah kriteria & isi skor alternatif -> tunjukkan halaman DSS owner (evaluasi wilayah) sebagai penutup.

---

## 7. Poin Sorotan (Cheat-Sheet Pembicara)

- **Dashboard eksekutif**: keputusan owner didukung 4 KPI + 3 grafik 12 bulan + produktivitas petugas - bukan lagi perkiraan.
- **Tagihan otomatis + denda dinamis**: sistem menagih sendiri setiap bulan; denda memakai skema paling menguntungkan unit pengelola (max persentase vs flat).
- **Penggajian otomatis**: gaji dihitung dari absensi dengan parameter (pokok/insentif/bonus/potongan) yang dikonfigurasi admin dalam format Rupiah.
- **Pengaduan terlacak end-to-end**: geolokasi + foto + dispatch + status + notifikasi di semua sisi.
- **Keuangan lengkap**: laba rugi, neraca kas, dan arus kas dalam satu klik.
- **DSS berbasis kriteria**: prioritas penanganan wilayah tidak subjektif, ada bobot dan skor.
- **Notifikasi terjadwal**: sistem mengingatkan jatuh tempo secara otomatis tanpa tenaga manual.

---

## 8. Akun Demo

| Peran | Email | Password |
|-------|-------|----------|
| Administrator | admin@sistemsampah.com | password123 |
| Owner | owner@sistemsampah.com | owner123 |
| Petugas Lapangan | andi@sistemsampah.com | petugas123 |
| Bendahara | bendahara@sistemsampah.com | bendahara123 |
| Warga | warga@sistemsampah.com | warga123 |

---

## 9. Persiapan Presentasi

- Jalankan `php artisan migrate --seed` (seeder menyiapkan 5 peran, 6 akun demo, 3 wilayah pelayanan, 3 rute, dan 1 warga).
- Hidupkan data transaksi: `php artisan iuran:generate-tagihan` lalu lakukan beberapa pembayaran lewat UI agar grafik dan laporan tidak kosong.
- Jalankan `php artisan notifikasi:kirim-pengingat` agar notifikasi pengingat tersedia saat demo.
- Mulai server: `php artisan serve` lalu buka `http://localhost:8000`.
- Siapkan dua jendela peramban (mode normal + mode penyamaran) agar transisi antar peran lebih cepat tanpa logout berulang.

---

## 10. Penutup

SIMPERSA menghubungkan seluruh rantai kerja persampahan - dari lapangan, administrasi, keuangan, hingga meja owner - dalam satu sistem dengan data yang konsisten dan otomatisasi yang mengurangi pekerjaan manual. Hasilnya: operasional lebih efisien dan keputusan diambil berdasarkan data, bukan perkiraan.

---

© 2026 Arfa Muhammad Fadhillah. Dibuat dengan teliti.
