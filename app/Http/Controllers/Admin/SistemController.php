<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warga;
use App\Models\JenisSampah;

class SistemController extends Controller
{
    public function index()
    {
        $totalWarga = Warga::count();
        $totalJenis = JenisSampah::count();
        return view('admin.sistem.index', compact('totalWarga', 'totalJenis'));
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
