<?php

namespace App\Support;

use App\Models\JenisSampah;
use App\Models\PengaturanGaji;
use App\Models\PenarikanSaldo;
use App\Models\Penggajian;
use App\Models\PenjualanSampah;
use App\Models\SetoranSampah;
use App\Models\User;
use App\Models\Warga;

class ChatbotIntent
{
    /**
     * Proses pesan user menjadi jawaban teks (rule-based, data langsung dari database).
     * Kelompok non-warga (admin, owner, bendahara, petugas) boleh menanyakan semua data;
     * warga hanya data dirinya sendiri; tamu (belum login) hanya FAQ.
     */
    public static function tanya(string $pesan, ?User $user): string
    {
        $pesan = mb_strtolower(trim($pesan));

        if ($pesan === '') {
            return 'Silakan ketik pertanyaan Anda. Contoh: "Berapa stok sampah?", "Saldo saya", atau "Fitur apa saja?".';
        }

        $role = $user ? mb_strtolower((string) $user->role?->name) : 'guest';
        $bolehData = $user !== null && $role !== 'warga';

        // ---- SAPAAN ----
        if (self::cocok($pesan, '/^(halo|hai|hi|hello|hei|assalamualaikum|selamat (pagi|siang|sore|malam))/')) {
            $nama = $user ? ", {$user->name}" : '';
            return "Halo{$nama}! Saya asisten SIMPERSA. Tanyakan seputar stok sampah, saldo tabungan, transaksi, atau fitur aplikasi. Apa yang bisa saya bantu?";
        }

        // ---- TERIMA KASIH ----
        if (self::cocok($pesan, '/(makasih|terima kasih|thanks|thank you)/')) {
            return 'Sama-sama! Senang bisa membantu. Ada lagi yang ingin Anda tanyakan?';
        }

        // ---- LUPA PASSWORD ----
        if (self::cocok($pesan, '/(lupa|reset|ganti).*(password|kata sandi)|(password|kata sandi).*(lupa|reset)/')) {
            return 'Untuk reset kata sandi, hubungi admin aplikasi. Admin dapat mengubah kata sandi pengguna dari menu "Akun Pengguna" di area Admin.';
        }

        // ---- SALDO SAYA ----
        if (self::cocok($pesan, '/(saldo|tabungan).*(saya|aku|ku)|(saya|aku).*(saldo|tabungan)/')) {
            if (! $user) {
                return 'Silakan login terlebih dahulu untuk melihat data saldo Anda.';
            }
            $warga = Warga::with('user')->where('user_id', $user->id)->first();
            if (! $warga) {
                return 'Akun Anda tidak terdaftar sebagai nasabah warga, jadi tidak ada saldo tabungan.';
            }

            return "Saldo tabungan Anda (no. Warga {$warga->no_warga}) sebesar Rp " . number_format((float) $warga->saldo_tabungan, 0, ',', '.') . '.';
        }

        // ---- SALDO WARGA BY NAMA ----
        if (preg_match('/saldo\s+(.+)/', $pesan, $m)) {
            $nama = self::bersihkanNama($m[1]);
            if ($nama === '') {
                return 'Saldo warga siapa yang ingin Anda ketahui? Contoh: "saldo Budi".';
            }

            if ($user && $role === 'warga' && mb_strtolower($user->name) !== $nama) {
                return 'Anda hanya dapat melihat saldo tabungan Anda sendiri. Ketik "saldo saya" untuk melihat saldo Anda.';
            }

            if (! $bolehData) {
                return 'Data saldo warga hanya dapat diakses setelah login oleh petugas, bendahara, admin, atau owner.';
            }

            $warga = Warga::with('user')
                ->whereHas('user', function ($q) use ($nama) {
                    $q->whereRaw('LOWER(name) LIKE ?', ["%{$nama}%"]);
                })
                ->first();

            if (! $warga && count(explode(' ', $nama)) > 1) {
                $kataPertama = explode(' ', $nama)[0];
                $warga = Warga::with('user')
                    ->whereHas('user', function ($q) use ($kataPertama) {
                        $q->whereRaw('LOWER(name) LIKE ?', ["%{$kataPertama}%"]);
                    })
                    ->first();
            }

            if (! $warga) {
                return "Warga dengan nama \"{$nama}\" tidak ditemukan. Coba dengan nama yang lebih lengkap.";
            }

            return "Saldo tabungan {$warga->user->name} (no. Warga {$warga->no_warga}) sebesar Rp " . number_format((float) $warga->saldo_tabungan, 0, ',', '.') . '.';
        }

        // ---- STOK PER JENIS ----
        if (self::cocok($pesan, '/stok/')) {
            $jenis = self::cocokJenis($pesan);

            if ($jenis) {
                $stok = StokSampah::stokTersedia($jenis->id);

                return "Stok sampah \"{$jenis->nama_jenis}\" saat ini " . number_format($stok, 2, ',', '.') . " kg (dari setoran dikurangi penjualan ke pengepul).";
            }

            $perJenis = StokSampah::perJenis()->filter(fn ($s) => $s->stok_kg > 0)->take(5);
            $total = StokSampah::total();

            if ($perJenis->isEmpty()) {
                return 'Stok sampah di gudang saat ini kosong (semua hasil setoran sudah terjual).';
            }

            $rincian = $perJenis->map(fn ($s) => "{$s->jenis}: " . number_format($s->stok_kg, 2, ',', '.') . ' kg')->implode(', ');

            return "Total stok sampah tersedia " . number_format($total, 2, ',', '.') . " kg. Rincian: {$rincian}.";
        }

        // ---- JUMLAH NASABAH ----
        if (self::cocok($pesan, '/(berapa\s+)?(nasabah|warga terdaftar|jumlah warga|total warga)/')) {
            return 'Jumlah nasabah warga terdaftar saat ini: ' . number_format(Warga::count(), 0, ',', '.') . ' warga.';
        }

        // ---- TRANSAKSI HARI INI ----
        if (self::cocok($pesan, '/hari ini/') && self::cocok($pesan, '/(transaksi|pembelian|setoran|timbang)/')) {
            $hariIni = now()->toDateString();
            $jml = SetoranSampah::whereDate('tanggal_setoran', $hariIni)->count();
            $kg = (float) SetoranSampah::whereDate('tanggal_setoran', $hariIni)->sum('berat_kg');
            $rupiah = (int) SetoranSampah::whereDate('tanggal_setoran', $hariIni)->sum('total_bayar');

            return "Transaksi pembelian sampah hari ini ({$hariIni}): {$jml} transaksi, " . number_format($kg, 2, ',', '.') . " kg, total belanja Rp " . number_format($rupiah, 0, ',', '.') . '.';
        }

        // ---- PENJUALAN BULAN INI ----
        if (self::cocok($pesan, '/(penjualan|hasil jual|jualan)/') && self::cocok($pesan, '/bulan ini/')) {
            $rupiah = (int) PenjualanSampah::whereMonth('tanggal_penjualan', now()->month)
                ->whereYear('tanggal_penjualan', now()->year)
                ->sum('total_harga');
            $kg = (float) PenjualanSampah::whereMonth('tanggal_penjualan', now()->month)
                ->whereYear('tanggal_penjualan', now()->year)
                ->sum('berat_kg');

            return "Penjualan sampah ke pengepul bulan ini (" . now()->format('F Y') . "): " . number_format($kg, 2, ',', '.') . " kg, pemasukan Rp " . number_format($rupiah, 0, ',', '.') . '.';
        }

        // ---- TOTAL TABUNGAN ----
        if (self::cocok($pesan, '/(total saldo|tabungan (semua|nasabah|warga)|jumlah tabungan)/')) {
            $total = (float) Warga::sum('saldo_tabungan');

            return 'Total saldo tabungan seluruh nasabah: Rp ' . number_format($total, 0, ',', '.') . '.';
        }

        // ---- PENARIKAN MENUNGGU ----
        if (self::cocok($pesan, '/penarikan/')) {
            if (! $bolehData) {
                return 'Informasi penarikan hanya dapat diakses oleh petugas, bendahara, admin, atau owner.';
            }
            $jml = PenarikanSaldo::where('status', 'Diproses')->count();
            $nominal = (int) PenarikanSaldo::where('status', 'Diproses')->sum('nominal');

            return "Penarikan tabungan berstatus Diproses (belum cair): {$jml} transaksi, total Rp " . number_format($nominal, 0, ',', '.') . '.';
        }

        // ---- GAJI ----
        if (self::cocok($pesan, '/(gaji|penggajian)/')) {
            if (! $bolehData) {
                return 'Informasi penggajian hanya dapat diakses oleh petugas, bendahara, admin, atau owner.';
            }
            $gajiPokok = (float) PengaturanGaji::ambil()->gaji_pokok;
            $terbayar = (int) Penggajian::where('status_pembayaran', 'Dibayar')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_gaji_bersih');

            return "Gaji pokok petugas saat ini Rp " . number_format($gajiPokok, 0, ',', '.') . "/bulan. Total gaji terbayar bulan ini Rp " . number_format($terbayar, 0, ',', '.') . '.';
        }

        // ---- FAQ: FITUR ----
        if (self::cocok($pesan, '/(fitur|bisa apa|fungsi|kegunaan)/')) {
            return self::jawabanFitur($role);
        }

        // ---- FAQ: CARA DAFTAR WARGA ----
        if (self::cocok($pesan, '/cara.*(daftar|mendaftarkan).*(warga|nasabah)/')) {
            if (! $user) {
                return 'Silakan login sebagai admin terlebih dahulu. Admin dapat mendaftarkan warga dari menu "Data Nasabah (Warga)".';
            }

            return 'Cara mendaftarkan warga baru: buka menu "Data Nasabah (Warga)" di area Admin, isi nama, email, nomor HP, dan alamat, lalu klik "Daftarkan Warga". Akun dengan role warga dibuat otomatis.';
        }

        // ---- FAQ: CARA ABSENSI ----
        if (self::cocok($pesan, '/cara.*(absen|absensi|clock|kehadiran)/')) {
            return 'Cara absensi bagi petugas: buka menu "Absensi", lalu klik tombol "Clock In" saat mulai bertugas dan "Clock Out" saat selesai. Rekap kehadiran bisa dilihat admin dan bendahara.';
        }

        // ---- FAQ: CARA PENJUALAN ----
        if (self::cocok($pesan, '/cara.*(jual|penjualan|pengepul)/')) {
            return 'Cara mencatat penjualan: buka menu "Penjualan" (area Petugas atau Bendahara), pilih jenis sampah, isi berat, lalu simpan. Sistem akan memeriksa stok tersedia — penjualan ditolak jika melebihi stok.';
        }

        // ---- FAQ: CARA PENARIKAN ----
        if (self::cocok($pesan, '/cara.*(tarik|penarikan)/')) {
            return 'Alur penarikan tabungan: Bendahara mencatat penarikan (status Diproses), lalu setelah dana diserahkan ke warga, konfirmasi "Cair" akan mengurangi saldo tabungan secara otomatis.';
        }

        // ---- FALLBACK ----
        return "Maaf, saya belum memahami pertanyaan itu. Coba tanya seperti:\n- \"Berapa stok sampah?\" / \"stok plastik PET\"\n- \"Saldo saya\" / \"saldo [nama warga]\"\n- \"Jumlah nasabah\" / \"transaksi hari ini\"\n- \"Fitur apa saja?\"";
    }

    private static function cocok(string $pesan, string $pola): bool
    {
        return preg_match($pola, $pesan) === 1;
    }

    /**
     * Cocokkan pesan dengan nama jenis: pesan mengandung nama jenis, atau kata penting di
     * pesan (panjang >= 4 huruf) muncul pada nama jenis, atau kata penting nama jenis muncul di pesan.
     */
    private static function cocokJenis(string $pesan): ?JenisSampah
    {
        $kataPesan = array_values(array_filter(
            preg_split('/\s+/', trim($pesan)) ?: [],
            fn ($k) => mb_strlen($k) >= 4
        ));

        return JenisSampah::all()->first(function ($j) use ($pesan, $kataPesan) {
            $nama = mb_strtolower($j->nama_jenis);

            if (str_contains($pesan, $nama)) {
                return true;
            }

            foreach ($kataPesan as $kata) {
                if (str_contains($nama, $kata)) {
                    return true;
                }
            }

            foreach (explode(' ', $nama) as $bagian) {
                if (mb_strlen($bagian) >= 4 && in_array($bagian, $kataPesan, true)) {
                    return true;
                }
            }

            return false;
        });
    }

    private static function bersihkanNama(string $raw): string
    {
        $raw = mb_strtolower(trim($raw));
        $raw = preg_replace('/\b(berapa|nya|sekarang|sekarang ini|ya|tolong|dong|lah)\b/u', ' ', $raw);
        $raw = trim(preg_replace('/\s+/', ' ', $raw));

        return mb_substr($raw, 0, 100);
    }

    private static function jawabanFitur(string $role): string
    {
        return match ($role) {
            'warga' => 'Sebagai nasabah warga, Anda dapat melihat profil, saldo tabungan, dan riwayat setoran sampah Anda. Setiap timbangan sampah yang disetor menambah saldo tabungan Anda.',
            'petugas_lapangan', 'petugas' => 'Sebagai petugas, Anda dapat mencatat pembelian sampah dari warga (multi-item), mencatat penjualan ke pengepul dengan cek stok otomatis, cetak nota, dan mengisi absensi harian.',
            'bendahara' => 'Sebagai bendahara, Anda dapat merekap pembelian & penjualan, mengelola tabungan dan penarikan warga, memproses penggajian, serta melihat laporan keuangan (neraca kas, arus kas, grafik).',
            'admin' => 'Sebagai admin, Anda dapat mengelola master data (kategori & jenis sampah beserta tarif), akun pengguna, data nasabah, rekap absensi, pengaturan gaji, stok sampah, dan utilitas backup database.',
            'owner' => 'Sebagai owner, Anda dapat memantau seluruh kinerja: laporan keuangan, pembelian & penjualan, penggajian, tabungan warga, stok sampah, dan data pengguna secara read-only.',
            default => 'SIMPERSA adalah sistem manajemen persampahan: pencatatan timbangan sampah harian, tabungan nasabah, penjualan ke pengepul, penggajian, stok sampah, hingga laporan keuangan untuk admin, owner, bendahara, dan petugas.',
        };
    }
}