<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Iuran;
use Illuminate\Support\Facades\Log;

class SistemController extends Controller
{
    public function index()
    {
        // Hitung jumlah tagihan yang belum dibayar untuk informasi di panel
        $totalTunggakan = Iuran::where('status_pembayaran', 'Belum Bayar')->count();
        return view('admin.sistem.index', compact('totalTunggakan'));
    }

    // Modul 13: Simulasi Blasting Notifikasi Pengingat Tagihan
    public function kirimPengingat(Request $request)
    {
        $tunggakan = Iuran::with('pelanggan.user')
            ->where('status_pembayaran', 'Belum Bayar')
            ->get();

        $terkirim = 0;
        foreach ($tunggakan as $iur) {
            $nama = $iur->pelanggan->user->name;
            $nomor = $iur->pelanggan->no_pelanggan;
            $bulan = $iur->bulan_tagihan;
            $nominal = number_format($iur->jumlah_tagihan, 0, ',', '.');

            // Konten Pesan Pengingat
            $pesan = "Halo $nama (No: $nomor), iuran sampah Anda untuk periode $bulan sebesar Rp $nominal belum terbayar. Mohon segera melakukan pelunasan. Terima kasih.";

            // 1. Simpan ke file log lokal (storage/logs/laravel.log) sebagai bukti simulasi
            Log::info("WA/Email BLAST ke Pelanggan: " . $pesan);

            // 2. Opsi Integrasi WhatsApp API (Fonnte / Jasa WA Gateway Lainnya)
            /*
            Http::withHeaders(['Authorization' => 'TOKEN_ANDA'])->post('https://api.fonnte.com/send', [
                'target' => $iur->pelanggan->user->phone_number, // pastikan ada kolom nomor hp
                'message' => $pesan,
            ]);
            */

            $terkirim++;
        }

        return redirect()->route('admin.sistem.index')->with('success', "Berhasil menyiarkan $terkirim notifikasi pengingat iuran ke sistem log!");
    }

    // Modul 14: Fitur Backup Database Instan via Dashboard
    public function backupDatabase()
    {
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        $dbHost = config('database.connections.mysql.host');

        $namaFile = "backup_simpersa_" . date('Y-m-d_H-i-s') . ".sql";

        // Header respons untuk langsung mengunduh file teks mentah SQL
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"$namaFile\"");

        // Perintah CLI untuk melakukan dump skema & isi tabel (mysqldump)
        // Jika password kosong, jangan sertakan argumen -p
        $passArg = $dbPass ? "-p" . escapeshellarg($dbPass) : "";
        $command = "mysqldump -h " . escapeshellarg($dbHost) . " -u " . escapeshellarg($dbUser) . " $passArg " . escapeshellarg($dbName);

        // Eksekusi perintah secara native
        passthru($command);
        exit;
    }
}
