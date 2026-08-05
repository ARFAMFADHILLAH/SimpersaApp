<?php

namespace App\Http\Controllers;

use App\Models\Penggajian;
use App\Models\PengaturanGaji;
use App\Models\User;
use App\Models\AbsensiPetugas;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BendaharaGajiController extends Controller
{
    public function index(Request $request)
    {
        $bulanFilter = $request->get('bulan', Carbon::now()->format('Y-m'));

        $rolePetugas = \DB::table('roles')
            ->whereIn('name', ['petugas', 'petugas_lapangan'])
            ->pluck('id');

        $dataPetugas = User::whereIn('role_id', $rolePetugas)
            ->where('status', 'aktif')
            ->get();

        $dataGaji = Penggajian::with('petugas')
            ->where('bulan_gaji', $bulanFilter)
            ->orderBy('created_at', 'desc')
            ->get();

        $daftarBulan = Penggajian::select('bulan_gaji')
            ->distinct()
            ->orderBy('bulan_gaji', 'desc')
            ->pluck('bulan_gaji');

        $pengaturan = PengaturanGaji::ambil();

        return view('bendahara.penggajian.index', compact(
            'dataGaji',
            'dataPetugas',
            'bulanFilter',
            'daftarBulan',
            'pengaturan'
        ));
    }

    public function prosesGaji(Request $request)
    {
        $request->validate([
            'bulan_gaji' => 'required|date_format:Y-m',
        ]);

        $bulan = $request->bulan_gaji;
        $rolePetugas = \DB::table('roles')
            ->whereIn('name', ['petugas', 'petugas_lapangan'])
            ->pluck('id');

        $petugasAktif = User::whereIn('role_id', $rolePetugas)
            ->where('status', 'aktif')
            ->get();

        $countProses = 0;
        $pengaturan = PengaturanGaji::ambil();

        foreach ($petugasAktif as $petugas) {
            $exists = Penggajian::where('petugas_id', $petugas->id)
                ->where('bulan_gaji', $bulan)
                ->exists();

            if ($exists) continue;

            $absenBulanIni = AbsensiPetugas::where('user_id', $petugas->id)
                ->whereYear('tanggal', substr($bulan, 0, 4))
                ->whereMonth('tanggal', substr($bulan, 5, 2))
                ->get();

            $totalHadir = $absenBulanIni->where('status', 'hadir')->count();
            $totalAlpha = $absenBulanIni->where('status', 'alpha')->count();
            $totalIzin = $absenBulanIni->where('status', 'izin')->count();
            $totalSakit = $absenBulanIni->where('status', 'sakit')->count();

            $gajiPokok = (int) $pengaturan->gaji_pokok;
            $insentifKehadiran = $totalHadir * (int) $pengaturan->insentif_per_hadir;
            $bonus = ($totalHadir >= (int) $pengaturan->minimal_hadir_bonus) ? (int) $pengaturan->bonus_amount : 0;
            $potonganAbsensi = ($totalAlpha * (int) $pengaturan->potongan_alpha_per_hari) + ($totalIzin * (int) $pengaturan->potongan_izin_per_hari);
            $totalBersih = $gajiPokok + $insentifKehadiran + $bonus - $potonganAbsensi;

            Penggajian::create([
                'petugas_id' => $petugas->id,
                'bulan_gaji' => $bulan,
                'gaji_pokok' => $gajiPokok,
                'insentif_lembur' => $insentifKehadiran + $bonus,
                'potongan' => $potonganAbsensi,
                'total_gaji_bersih' => max($totalBersih, 0),
                'status_pembayaran' => 'Pending',
            ]);

            $countProses++;
        }

        return redirect()->route('bendahara.penggajian.index')
            ->with('success', "Berhasil memproses gaji {$countProses} petugas untuk bulan {$bulan}.");
    }

    public function bayarGaji($id)
    {
        $gaji = Penggajian::with('petugas')->findOrFail($id);
        $gaji->update(['status_pembayaran' => 'Dibayar']);

        // Notifikasi ke petugas bahwa gaji telah dibayarkan
        if ($gaji->petugas) {
            Notification::kirim(
                $gaji->petugas->id,
                'Gaji Telah Dibayarkan',
                "Gaji periode {$gaji->bulan_gaji} sebesar Rp " . number_format($gaji->total_gaji_bersih, 0, ',', '.') . " telah dibayarkan.",
                'gaji_dibayar',
                route('petugas.gaji.index')
            );
        }

        return redirect()->route('bendahara.penggajian.index')
            ->with('success', 'Gaji petugas berhasil dibayarkan.');
    }

    public function cetakSlip($id)
    {
        $gaji = Penggajian::with('petugas')->findOrFail($id);

        return view('bendahara.penggajian.slip', compact('gaji'));
    }

    public function rekapGaji(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->format('Y-m'));

        $rekap = Penggajian::with('petugas')
            ->where('bulan_gaji', $bulan)
            ->get();

        $totalGajiPokok = $rekap->sum('gaji_pokok');
        $totalInsentif = $rekap->sum('insentif_lembur');
        $totalPotongan = $rekap->sum('potongan');
        $totalBersih = $rekap->sum('total_gaji_bersih');

        return view('bendahara.penggajian.rekap', compact(
            'rekap',
            'bulan',
            'totalGajiPokok',
            'totalInsentif',
            'totalPotongan',
            'totalBersih'
        ));
    }
}
