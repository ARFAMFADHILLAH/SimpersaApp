<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPetugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->format('Y-m'));
        $tahun = (int) substr($bulan, 0, 4);
        $bulanAngka = (int) substr($bulan, 5, 2);

        $rolePetugas = DB::table('roles')
            ->whereIn('name', ['petugas', 'petugas_lapangan'])
            ->pluck('id');

        $dataPetugas = User::whereIn('role_id', $rolePetugas)
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get();

        $dataAbsensi = [];

        foreach ($dataPetugas as $petugas) {
            $riwayat = AbsensiPetugas::where('user_id', $petugas->id)
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulanAngka)
                ->orderBy('tanggal')
                ->get();

            $dataAbsensi[] = (object) [
                'petugas' => $petugas,
                'total_hadir' => $riwayat->where('status', 'hadir')->count(),
                'total_izin' => $riwayat->where('status', 'izin')->count(),
                'total_sakit' => $riwayat->where('status', 'sakit')->count(),
                'total_alpha' => $riwayat->where('status', 'alpha')->count(),
                'riwayat' => $riwayat,
            ];
        }

        return view('admin.absensi.index', compact('bulan', 'dataAbsensi'));
    }

    // Koreksi status satu baris absensi
    public function updateStatus(Request $request, AbsensiPetugas $absensi)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $absensi->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan ?: null,
        ]);

        return redirect()->back()->with('success', 'Status absensi ' . $absensi->user->name . ' diperbarui menjadi ' . $request->status . '.');
    }

    // Tambah/catat absensi manual (backfill izin/sakit/alpha di hari tertentu)
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date|before_or_equal:today',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string|max:255',
        ]);

        AbsensiPetugas::updateOrCreate(
            ['user_id' => $request->user_id, 'tanggal' => $request->tanggal],
            [
                'status' => $request->status,
                'keterangan' => $request->keterangan ?: null,
            ]
        );

        return redirect()->back()->with('success', 'Absensi manual berhasil disimpan.');
    }
}
