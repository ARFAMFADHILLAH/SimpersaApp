<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagerPengaduanController extends Controller
{
    public function index()
    {
        // Join laporan_kendalas ke users/petugas via petugas_id
        $pengaduan = DB::table('laporan_kendalas')
            ->leftJoin('users', 'laporan_kendalas.petugas_id', '=', 'users.id')
            ->select(
                'laporan_kendalas.*',
                'users.name as nama_petugas',
                'users.email as email_petugas'
            )
            ->latest('laporan_kendalas.created_at')
            ->paginate(15);

        // Karena di tabel laporan_kendalas tidak ada kolom status, kita hitung total laporan
        $totalKendala = DB::table('laporan_kendalas')->count();

        return view('manager.pengaduan.index', compact('pengaduan', 'totalKendala'));
    }
}