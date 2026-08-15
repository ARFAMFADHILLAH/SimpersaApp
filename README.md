# Sistem Informasi Manajemen Persampahan Terintegrasi (SIMPERSA)

Sistem informasi pengelolaan bank sampah yang menyatukan alur kerja operasional dalam satu platform: petugas kasir mencatat pembelian sampah dari warga (saldo tabungan), bendahara mengelola kas, tabungan, dan penggajian, admin mengelola master data sekaligus memantau stok sampah, dan owner memantau seluruh operasional secara read-only — dilengkapi asisten chatbot cerdas yang menjawab langsung dari data aplikasi.

Dibangun dengan **Laravel 13.8 (PHP 8.3)** + **Blade** + **Tailwind CSS 3** + **Alpine.js** + **Vite 8** + **MySQL/MariaDB**.

## Fitur Utama

### Admin (`/admin`)
- Master pengguna & data warga (nasabah) — registrasi walk-in dengan nomor warga otomatis (`WRG-YYYYMM-####`) dan **pencarian warga** (nama, email, no. warga, alamat) + tombol Cari/Reset
- Master kategori & jenis sampah: **harga beli** (dibayar ke warga) dan **harga jual** (ke pengepul)
- **Stok sampah** (`/admin/stok`): tersedia per jenis = setoran − penjualan (minimal 0), kartu "Stok Sampah Tersedia" di dashboard
- **Rekap kehadiran** petugas per bulan (hadir/izin/sakit/alpha): **koreksi status per baris** (dropdown) + **tambah absensi manual** (backfill izin/sakit/alpha di hari tertentu)
- Pengaturan **gaji pokok** petugas (bonus/insentif diinput Bendahara saat pembayaran)
- Utilitas sistem & **backup database**

### Petugas / Kasir (`/petugas`)
- **Absensi clock-in/clock-out dengan foto wajah wajib** memakai kamera realtime (getUserMedia; tombol kamera depan/ambil ulang; fallback unggah file) — foto masuk & pulang tersimpan, riwayat menampilkan thumbnail
- **Lapor Izin / Sakit** (tanpa clock-in): pilih status + alasan (keterangan), bisa dibatalkan sendiri; clock-in otomatis diblokir setelah lapor
- **Pembelian sampah dari warga**: berat (kg) × harga beli = saldo tabungan warga bertambah, disertai **nota** yang dapat dicetak, **filter tanggal** dengan tombol "Tampilkan"
- **Penjualan sampah ke pengepul**: cek stok otomatis (ditolak bila melebihi stok), **filter tanggal**
- **Slip gaji** pribadi

### Bendahara (`/bendahara`)
- Rincian **pembelian** & **penjualan** sampah
- **Tabungan warga**: saldo per nasabah, penarikan (status menunggu → diproses → ditarik), saldo berkurang hanya saat ditarik, **filter status** (Semua/Diproses/Ditarik) + tombol Filter/Reset
- **Penggajian**: proses gaji per bulan (gaji pokok), pembayaran dengan **Bonus / Insentif manual** (total = pokok + bonus, tanpa potongan otomatis, anti pembayaran ganda), **slip gaji** & **rekap gaji**
- **Rekap kehadiran** petugas per bulan
- **Laporan keuangan**: neraca kas, arus kas, grafik, cetak rekap

### Owner (`/owner`)
- Dashboard eksekutif (KPI & grafik 12 bulan) — read-only
- Laporan: kas, pembelian, penjualan, gaji, tabungan
- **Stok sampah** (`/owner/stok`): tersedia per jenis + kartu di dashboard
- Keuangan, data warga, dan pengguna

> **Peran Warga (nasabah)**: warga adalah data nasabah yang akunnya dibuat oleh admin; saldo tabungan dikelola petugas (setoran) dan bendahara (penarikan). Setelah login, warga diarahkan ke halaman depan. Saat ini tidak ada area khusus `/warga/*`.

### Asisten Chatbot (semua halaman)
- **Rule-based lokal** tanpa API eksternal — penjawab pintar yang tampil sebagai tombol melayang di semua halaman login maupun halaman awal (welcome)
- Menjawab **langsung dari database**: saldo tabungan (per warga), stok sampah (per jenis & total), jumlah nasabah, transaksi hari ini, penjualan bulan ini, penarikan menunggu, dan penggajian
- **FAQ fitur per peran**: jawaban disesuaikan dengan role (warga/petugas/bendahara/admin/owner/tamu)
- **Role-aware**: petugas/bendahara/admin/owner bebas menanyakan semua data; warga hanya saldo sendiri; tamu (belum login) hanya FAQ — logika intent di `app/Support/ChatbotIntent.php`
- Halaman **profil berbahasa Indonesia** lengkap dengan tombol "perlihatkan kata sandi" (mata) pada form kata sandi

## Alur Bisnis

```
Warga setor sampah (kasir/Petugas)  ──►  Berat × Harga Beli  ──►  Saldo Tabungan warga bertambah + Nota
Warga menarik saldo (Bendahara)     ──►  Verifikasi: menunggu → diproses → ditarik  ──►  Saldo warga berkurang
Penjualan ke pengepul (Petugas)     ──►  Tercatat di rincian Bendahara & laporan Owner
Penggajian (Bendahara)              ──►  Proses = Gaji Pokok  ──►  Bayar = Pokok + Bonus/Insentif manual  ──►  Slip & Notifikasi
Absensi (Petugas & Admin)           ──►  Clock-in/out foto wajah / lapor izin-sakit  ──►  Rekap hadir-izin-sakit-alpha (Admin koreksi)
Stok sampah (Admin & Owner)         ──►  Setoran − penjualan per jenis (min. 0)      ──►  Pemantauan real-time, jual > stok ditolak
Chatbot Asisten (semua halaman)     ──►  Tanya data & FAQ → jawab langsung dari DB
```

## Peta Peran ke Halaman

| Peran | Area | Fungsi Kunci |
|---|---|---|
| Administrator | `/admin/*` | users, warga (+ pencarian), kategori & jenis sampah, stok sampah, rekap kehadiran (+ koreksi status & tambah manual), pengaturan gaji pokok, backup |
| Petugas Lapangan | `/petugas/*` | absensi kamera (clock-in/out), lapor izin/sakit, pembelian sampah + nota (+ filter tanggal), penjualan pengepul (cek stok), slip gaji |
| Bendahara | `/bendahara/*` | pembelian/penjualan, tabungan & penarikan (+ filter status), penggajian (pokok + bonus), rekap kehadiran, laporan keuangan |
| Owner | `/owner/*` | dashboard KPI, stok sampah, laporan (kas/pembelian/penjualan/gaji/tabungan), keuangan, warga, pengguna |

Setelah login, semua peran diarahkan otomatis ke dashboard masing-masing melalui `/dashboard` (middleware `role` membatasi akses lintas area).

## Cara Menjalankan

```bash
composer install           # install dependency
cp .env.example .env       # atur koneksi database MySQL
php artisan key:generate
php artisan migrate --seed # migrasi + data demo (5 peran, 6 akun, 1 warga)
php artisan storage:link   # agar foto absensi dapat ditampilkan
php artisan serve          # jalankan → http://localhost:8000
```

### Production

```bash
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

> Setelah `git pull` selalu jalankan `php artisan migrate` untuk migrasi terbaru. Setelah mengubah file Blade, jalankan `php artisan view:clear` — tanpa itu halaman lama yang ter-cache bisa tetap tampil.

## Teknologi

| Teknologi | Kegunaan |
|---|---|
| Laravel 13.8 (PHP 8.3) | Framework, routing, Eloquent ORM |
| Blade + Tailwind CSS 3 + Alpine.js | Template, styling, interaktivitas (komponen kamera realtime, UI dinamis) |
| Vite 8 | Build asset frontend |
| MySQL/MariaDB | Basis data |
| Chart.js (CDN) | Grafik dashboard & laporan keuangan |
| PHPUnit (Feature Tests) | Pengujian otomatis — **101 kasus, 374 assertion, 100% hijau** (`php artisan test`) |

## Struktur Direktori

```
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/               # master data, gaji, rekap kehadiran, backup
│   │   ├── Owner/               # pantauan read-only
│   │   ├── Bendahara/           # tabungan, penggajian, laporan keuangan
│   │   ├── Petugas_lapangan/    # kasir, absensi kamera, gaji
│   │   └── Auth/                # autentikasi
│   ├── Models/                  # 13 model
│   ├── Support/                 # StokSampah, ChatbotIntent, KodeTransaksi
│   └── Providers/
├── database/
│   ├── migrations/              # 25 migrasi
│   └── seeders/                 # 5 peran + 6 akun demo
├── resources/views/            # view per area + components (sidebar, bottom-nav, camera-capture, lokasi-picker, chatbot)
├── routes/
│   └── web.php                 # 94 rute terintegrasi
├── tests/Feature/              # 101 kasus uji (kasir, tabungan, absensi, gaji, role access, stok, chatbot)
└── public/
```

## Akun Demo

| Peran | Email | Password |
|---|---|---|
| Administrator | admin@sistemsampah.com | password123 |
| Owner | owner@sistemsampah.com | owner123 |
| Petugas Lapangan | andi@sistemsampah.com | petugas123 |
| Administrasi | Anton@sistemsampah.com | admin123 |
| Bendahara | bendahara@sistemsampah.com | bendahara123 |
| Warga | warga@sistemsampah.com | warga123 |

## Menjalankan Pengujian

```bash
php artisan test    # 101 tests, seluruhnya lulus
php vendor/bin/pint # format kode (PSR-12)
```

## Catatan

- **Absensi**: foto wajah wajib dikirim saat clock-in maupun clock-out (validasi `required|image|jpeg,png,jpg|max:2048`); foto disimpan di `storage/app/public/absensi` dan dapat dilihat pada riwayat petugas. Status **Izin/Sakit** dilaporkan petugas sendiri (form "Lapor Izin / Sakit" + keterangan, dapat dibatalkan; clock-in diblokir hari itu), sedangkan **Alpha** serta koreksi status lainnya diisi **Admin** lewat dropdown per baris atau form tambah manual di `/admin/absensi` (Bendahara tetap read-only).
- **Penggajian**: parameter di halaman admin `/admin/gaji/pengaturan` cukup **Gaji Pokok**; bonus/insentif diinput Bendahara per baris saat pembayaran; kolom `pengaturan_gaji` lama (insentif/bonus/potongan) tidak lagi dipakai perhitungan.
- **Laporan keuangan**: pemasukan dihitung dari **penjualan sampah ke pengepul** (`penjualan_sampah`), pengeluaran dari gaji dibayar (`penggajian`) dan operasional (`pengeluaran_operasional`).
- **Stok sampah**: dihitung dari **setoran dikurangi penjualan per jenis** (helper `app/Support/StokSampah.php`, nilai minimal 0); penjualan ke pengepul otomatis **ditolak bila melebihi stok** tersedia.
- **Chatbot**: berbasis **intent rule-based** (`app/Support/ChatbotIntent.php`) tanpa API eksternal; jawaban data diambil langsung dari database dan dibatasi sesuai peran pengguna.
- Modul-modul lama (iuran, rute, pengangkutan, armada, DSS, TPS, pengaduan, mitra) telah dihapus dari basis data, kode, dan menu aplikasi.

---

© 2026 Arfa Muhammad Fadhillah.