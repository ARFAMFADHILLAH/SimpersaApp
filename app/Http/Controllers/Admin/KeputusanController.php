<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KriteriaDss;
use App\Models\Tps;
use App\Models\SkorAlternatifDss;
use Illuminate\Http\Request;

class KeputusanController extends Controller
{
    public function index()
    {
        $kriteria = KriteriaDss::all();
        $totalBobot = $kriteria->sum('bobot'); // Untuk validasi total bobot = 1 (100%)

        $tpsList = Tps::all();

        // Peta skor yang sudah tersimpan: [tps_id][kriteria_id] = nilai
        $skorMap = [];
        foreach (SkorAlternatifDss::all() as $skor) {
            $skorMap[$skor->tps_id][$skor->kriteria_id] = $skor->nilai;
        }

        // Kalkulasi Matriks Keputusan & Perhitungan SAW
        $semuaTps = $tpsList;
        $hasilKeputusan = [];

        if ($kriteria->count() > 0 && $semuaTps->count() > 0) {
            foreach ($semuaTps as $tps) {
                $totalSkorAkhir = 0;

                foreach ($kriteria as $k) {
                    $skor = $skorMap[$tps->id][$k->id] ?? 0;

                    // Nilai Maks / Min kriteria untuk normalisasi
                    if ($k->jenis == 'benefit') {
                        $maxNilai = SkorAlternatifDss::where('kriteria_id', $k->id)->max('nilai') ?: 1;
                        $normalisasi = $skor / $maxNilai;
                    } else {
                        $minNilai = SkorAlternatifDss::where('kriteria_id', $k->id)->min('nilai') ?: 1;
                        $normalisasi = $minNilai / ($skor ?: 1);
                    }

                    // Kalikan dengan Bobot
                    $totalSkorAkhir += ($normalisasi * $k->bobot);
                }

                $hasilKeputusan[] = [
                    'tps' => $tps,
                    'skor_akhir' => round($totalSkorAkhir, 4)
                ];
            }

            // Urutkan dari Skor Tertinggi ke Terendah (Ranking)
            usort($hasilKeputusan, fn($a, $b) => $b['skor_akhir'] <=> $a['skor_akhir']);
        }

        return view('admin.keputusan.index', compact('kriteria', 'totalBobot', 'hasilKeputusan', 'tpsList', 'skorMap'));
    }

    // Simpan / Perbarui Skor Alternatif (TPS per Kriteria)
    public function storeSkor(Request $request)
    {
        $request->validate([
            'skor' => 'required|array',
            'skor.*.*' => 'nullable|numeric|min:0',
        ]);

        $updated = 0;

        foreach ($request->input('skor', []) as $tpsId => $nilaiKriteria) {
            foreach ($nilaiKriteria as $kriteriaId => $nilai) {
                if ($nilai === null || $nilai === '') {
                    continue;
                }

                SkorAlternatifDss::updateOrCreate(
                    ['tps_id' => $tpsId, 'kriteria_id' => $kriteriaId],
                    ['nilai' => $nilai]
                );

                $updated++;
            }
        }

        return redirect()->back()->with('success', "Skor alternatif berhasil disimpan ({$updated} data).");
    }

    // Tambah Kriteria Baru
    public function storeKriteria(Request $request)
    {
        $validated = $request->validate([
            'kode_kriteria' => 'required|string|unique:kriteria_dss,kode_kriteria',
            'nama_kriteria' => 'required|string',
            'bobot'         => 'required|numeric|min:0|max:1',
            'jenis'         => 'required|in:benefit,cost',
        ]);

        KriteriaDss::create($validated);

        return redirect()->back()->with('success', 'Kriteria berhasil ditambahkan!');
    }

    // Update Kriteria / Bobot
    public function updateKriteria(Request $request, $id)
    {
        $kriteria = KriteriaDss::findOrFail($id);
        
        $validated = $request->validate([
            'kode_kriteria' => 'required|string|unique:kriteria_dss,kode_kriteria,' . $id,
            'nama_kriteria' => 'required|string',
            'bobot'         => 'required|numeric|min:0|max:1',
            'jenis'         => 'required|in:benefit,cost',
        ]);

        $kriteria->update($validated);

        return redirect()->back()->with('success', 'Kriteria & Bobot berhasil diperbarui!');
    }

    // Hapus Kriteria
    public function destroyKriteria($id)
    {
        KriteriaDss::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Kriteria berhasil dihapus!');
    }
}