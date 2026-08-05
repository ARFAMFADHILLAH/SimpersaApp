<?php

namespace App\Http\Controllers\Petugas_lapangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AbsensiPetugas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
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

        $fotoMasuk = null;
        if ($request->hasFile('foto_masuk')) {
            $fotoMasuk = $request->file('foto_masuk')->store('absensi', 'public');
        }

        AbsensiPetugas::updateOrCreate(
            ['user_id' => $userId, 'tanggal' => $today],
            [
                'jam_masuk' => Carbon::now()->toTimeString(),
                'status' => 'hadir',
                'foto_masuk' => $fotoMasuk
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

        if (!$absensi || !$absensi->jam_masuk) {
            return redirect()->back()->with('error', 'Anda belum melakukan Clock-In hari ini.');
        }

        if ($absensi->jam_pulang) {
            return redirect()->back()->with('error', 'Anda sudah melakukan Clock-Out hari ini.');
        }

        $fotoPulang = null;
        if ($request->hasFile('foto_pulang')) {
            $fotoPulang = $request->file('foto_pulang')->store('absensi', 'public');
        }

        $absensi->update([
            'jam_pulang' => Carbon::now()->toTimeString(),
            'foto_pulang' => $fotoPulang,
        ]);

        return redirect()->back()->with('success', 'Berhasil Clock-Out! Hati-hati di jalan.');
    }
}
