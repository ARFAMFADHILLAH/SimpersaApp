<?php

namespace App\Http\Controllers\Petugas_lapangan;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPetugas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    // Halaman Absensi: status hari ini + riwayat 30 hari
    public function index()
    {
        $userId = Auth::id();

        $absensiHariIni = AbsensiPetugas::where('user_id', $userId)
            ->where('tanggal', Carbon::today()->toDateString())
            ->first();

        $riwayatAbsensi = AbsensiPetugas::where('user_id', $userId)
            ->orderBy('tanggal', 'desc')
            ->take(30)
            ->get();

        $totalHadir = AbsensiPetugas::where('user_id', $userId)
            ->where('status', 'hadir')
            ->count();

        return view('petugas_lapangan.absensi.index', compact('absensiHariIni', 'riwayatAbsensi', 'totalHadir'));
    }

    // Aksi Clock-In (Masuk)
    public function clockIn(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $userId = Auth::id();

        // Cek apakah sudah absen masuk hari ini
        $absensi = AbsensiPetugas::where('user_id', $userId)
            ->where('tanggal', $today)
            ->first();

        if ($absensi && $absensi->jam_masuk) {
            return redirect()->back()->with('error', 'Anda sudah melakukan Clock-In hari ini.');
        }

        $request->validate([
            'foto_masuk' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $fotoMasuk = $request->file('foto_masuk')->store('absensi', 'public');

        AbsensiPetugas::updateOrCreate(
            ['user_id' => $userId, 'tanggal' => $today],
            [
                'jam_masuk' => Carbon::now()->toTimeString(),
                'status' => 'hadir',
                'foto_masuk' => $fotoMasuk,
            ]
        );

        return redirect()->back()->with('success', 'Berhasil Clock-In! Selamat bekerja.');
    }

    // Aksi Clock-Out (Pulang)
    public function clockOut(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $userId = Auth::id();

        $absensi = AbsensiPetugas::where('user_id', $userId)
            ->where('tanggal', $today)
            ->first();

        if (! $absensi || ! $absensi->jam_masuk) {
            return redirect()->back()->with('error', 'Anda belum melakukan Clock-In hari ini.');
        }

        if ($absensi->jam_pulang) {
            return redirect()->back()->with('error', 'Anda sudah melakukan Clock-Out hari ini.');
        }

        $request->validate([
            'foto_pulang' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fotoPulang = $request->file('foto_pulang')->store('absensi', 'public');

        $absensi->update([
            'jam_pulang' => Carbon::now()->toTimeString(),
            'foto_pulang' => $fotoPulang,
        ]);

        return redirect()->back()->with('success', 'Berhasil Clock-Out! Hati-hati di jalan.');
    }
}
