<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\PengaturanGaji;
use App\Models\Penggajian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        $gajiPokok = (int) $pengaturan->gaji_pokok;

        foreach ($petugasAktif as $petugas) {
            $exists = Penggajian::where('petugas_id', $petugas->id)
                ->where('bulan_gaji', $bulan)
                ->exists();

            if ($exists) {
                continue;
            }

            Penggajian::create([
                'petugas_id' => $petugas->id,
                'bulan_gaji' => $bulan,
                'gaji_pokok' => $gajiPokok,
                'insentif_lembur' => 0,
                'potongan' => 0,
                'total_gaji_bersih' => $gajiPokok,
                'status_pembayaran' => 'Pending',
            ]);

            $countProses++;
        }

        return redirect()->route('bendahara.penggajian.index')
            ->with('success', "Berhasil memproses gaji {$countProses} petugas untuk bulan {$bulan}.");
    }

    public function bayarGaji(Request $request, $id)
    {
        $validated = $request->validate([
            'insentif_lembur' => 'nullable|integer|min:0',
        ]);

        $gaji = Penggajian::with('petugas')->findOrFail($id);

        if ($gaji->status_pembayaran === 'Dibayar') {
            return redirect()->route('bendahara.penggajian.index')
                ->with('error', 'Gaji petugas ini sudah dibayarkan sebelumnya.');
        }

        $bonus = (int) ($validated['insentif_lembur'] ?? 0);
        $totalBersih = (int) $gaji->gaji_pokok + $bonus;

        $gaji->update([
            'insentif_lembur' => $bonus,
            'total_gaji_bersih' => $totalBersih,
            'status_pembayaran' => 'Dibayar',
        ]);

        // Notifikasi ke petugas bahwa gaji telah dibayarkan
        if ($gaji->petugas) {
            Notification::kirim(
                $gaji->petugas->id,
                'Gaji Telah Dibayarkan',
                "Gaji periode {$gaji->bulan_gaji} sebesar Rp ".number_format($gaji->total_gaji_bersih, 0, ',', '.').' telah dibayarkan.',
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
