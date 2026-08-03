
## Informasi Aplikasi 

Dengan judul:

* Sistem Informasi Manajemen Persampahan Terintegrasi untuk Meningkatkan Efisiensi Operasional dan Kualitas Pengambilan Keputusan**


## Aktor Sistem

Sistem dapat melibatkan beberapa aktor utama:

1. Administrator
2. Manajer/Pimpinan
3. Petugas Lapangan
4. Petugas Administrasi
5. Bendahara/Keuangan
6. Masyarakat/Pelanggan

---

# Modul 1. Master Data

Fitur:

* Data pelanggan
* Data petugas
* Data kendaraan operasional
* Data wilayah pelayanan
* Data jenis sampah
* Data tarif iuran
* Data TPS
* Data armada

---

# Modul 2. Pelanggan

Fitur:

* Registrasi pelanggan
* Nomor pelanggan otomatis
* Status pelanggan aktif/nonaktif
* Riwayat pembayaran
* Riwayat pengangkutan
* Pengaduan pelanggan
* Notifikasi jatuh tempo iuran

---

# Modul 3. Monitoring Operasional

Fitur:

* Jadwal pengangkutan
* Monitoring petugas
* Monitoring armada
* Monitoring rute
* Monitoring penyelesaian tugas
* Dokumentasi sebelum dan sesudah pengangkutan (foto)
* Status pekerjaan:

  * Belum dikerjakan
  * Sedang dikerjakan
  * Selesai

---

# Modul 4. Pengelolaan Sampah

Fitur:

* Input volume sampah
* Input berat sampah
* Jenis sampah
* Lokasi pengangkutan
* Rekap harian
* Rekap mingguan
* Rekap bulanan

---

# Modul 5. Manajemen Iuran Sampah

Fitur:

* Penetapan tarif
* Tagihan otomatis setiap bulan
* Pembayaran tunai/non-tunai
* Cetak bukti pembayaran
* Monitoring tunggakan
* Riwayat pembayaran
* Denda keterlambatan
* Laporan pendapatan

---

# Modul 6. Penggajian Petugas

Fitur:

* Absensi
* Kehadiran
* Insentif
* Bonus
* Potongan
* Perhitungan gaji otomatis
* Slip gaji
* Rekap gaji

---

# Modul 7. Manajemen Operasional

Fitur:

* BBM kendaraan
* Servis kendaraan
* Pergantian ban
* Pembelian alat
* Pengeluaran operasional
* Monitoring biaya operasional

---

# Modul 8. Keuangan

Fitur:

* Kas masuk
* Kas keluar
* Laporan laba rugi sederhana
* Neraca kas
* Arus kas
* Grafik pendapatan

---

# Modul 9. Dashboard Manajemen

Dashboard merupakan nilai tambah utama dalam sistem.

Menampilkan:

* Total pelanggan
* Pelanggan aktif
* Pelanggan menunggak
* Total pendapatan iuran
* Total biaya operasional
* Total gaji petugas
* Volume sampah per hari
* Volume sampah per bulan
* Produktivitas petugas
* Kendaraan aktif
* Kendaraan rusak
* Grafik pembayaran
* Grafik volume sampah
* Grafik biaya operasional

Dashboard ini membantu pimpinan mengambil keputusan berbasis data secara cepat.

---

# Modul 10. Laporan

Laporan yang dapat dihasilkan:

* Laporan pelanggan
* Laporan iuran
* Laporan tunggakan
* Laporan pengangkutan
* Laporan volume sampah
* Laporan kendaraan
* Laporan petugas
* Laporan gaji
* Laporan keuangan
* Rekap bulanan
* Rekap tahunan

---

# Modul 11. Pengaduan Masyarakat

Analisis nama tunggakan

Fitur:

* Pengaduan sampah belum diangkut
* Upload foto
* Titik lokasi
* Tracking status pengaduan
* Respon petugas
* Riwayat penyelesaian

---

# Modul 12. Pengambilan Keputusan (Decision Support)

# Modul 13. Notifikasi

Fitur:

* Pengingat pembayaran iuran
* Jadwal pengangkutan
* Jadwal servis kendaraan
* Notifikasi gaji
* Notifikasi tunggakan
* Pengaduan baru

---

# Panduan Teknis

## Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Scheduler (Cron)

Untuk menjalankan tugas terjadwal (generasi tagihan bulanan & pengingat notifikasi), tambahkan baris berikut ke crontab server:

```bash
* * * * * cd /path/ke/proyek && php artisan schedule:run >> /dev/null 2>&1
```

Perintah terjadwal yang berjalan secara otomatis:

| Command | Jadwal | Fungsi |
|---|---|---|
| `php artisan iuran:generate-tagihan` | Setiap tanggal 1 pukul 00:00 | Membuat tagihan iuran otomatis untuk semua pelanggan aktif |
| `php artisan notifikasi:kirim-pengingat` | Setiap 30 menit | Mengirim notifikasi in-app sesuai jadwal/template (iuran, pengangkutan, servis, gaji) |

## Command Manual

* `php artisan iuran:generate-tagihan --bulan=2026-08` — generate tagihan untuk bulan tertentu (default: bulan berjalan).
* `php artisan notifikasi:kirim-pengingat` — jalankan manual jika ingin langsung mengirim pengingat.

## Modul Baru

* **Pusat Notifikasi** (`/notifikasi`) — tersedia untuk semua role, menampilkan notifikasi in-app (pengaduan baru, gaji dibayar, iuran lunas, pengingat terjadwal) dengan badge jumlah belum dibaca di sidebar.
* **Neraca Kas & Arus Kas** (`/bendahara/laporan/neraca-kas`, `/bendahara/laporan/arus-kas`).
* **Laporan Tunggakan, Petugas, dan Rekap Tahunan** (`/manager/laporan/*`).
* **Input Skor Alternatif DSS** — panel di Pengaturan DSS (admin) untuk mengisi nilai skor TPS per kriteria.
* **Titik Lokasi Pengaduan** — pengaduan kini menyimpan koordinat latitude/longitude (auto-deteksi lokasi browser) dan dapat dibuka di Google Maps.
* **Rekap Volume Mingguan & Berat Sampah** — Modul 4 kini menampilkan rekap mingguan dan kolom berat sampah.
