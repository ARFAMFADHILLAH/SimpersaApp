<?php

namespace App\Http\Controllers\Petugas_lapangan;

use App\Http\Controllers\Controller;
use App\Models\Rute;
use App\Models\Pengangkutan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuteController extends Controller
{
    public function index()
    {
        $ruteIds = $this->assignedRuteIds();

        $dataRute = Rute::withCount('warga')
            ->whereIn('id', $ruteIds)
            ->get();

        return view('petugas_lapangan.rute.index', compact('dataRute'));
    }

    public function show($id)
    {
        abort_unless($this->assignedRuteIds()->contains($id), 404);

        $rute = Rute::with(['warga' => fn ($q) => $q->orderBy('urutan')->orderBy('id'), 'warga.user'])->findOrFail($id);

        $pengangkutan = Pengangkutan::where('petugas_id', auth()->id())
            ->whereIn('warga_id', $rute->warga->pluck('id'))
            ->get()
            ->keyBy('warga_id');

        return view('petugas_lapangan.rute.detail', compact('rute', 'pengangkutan'));
    }

    /**
     * ID rute yang ditugaskan admin ke petugas yang sedang login.
     */
    private function assignedRuteIds()
    {
        return DB::table('pengangkutan')
            ->join('warga', 'warga.id', '=', 'pengangkutan.warga_id')
            ->where('pengangkutan.petugas_id', auth()->id())
            ->whereNotNull('warga.rute_id')
            ->distinct()
            ->pluck('warga.rute_id');
    }

    public function tugasHarian()
    {
        $userId = auth()->id();

        $tugasHariIni = Pengangkutan::with('warga.user', 'warga.rute')
            ->where('petugas_id', $userId)
            ->whereDate('tanggal_tugas', now()->toDateString())
            ->get();

        // Jika belum ada jadwal hari ini, tampilkan penugasan milik petugas ini
        $daftarTugas = $tugasHariIni->isNotEmpty()
            ? $this->mapTugas($tugasHariIni)
            : $this->mapTugas(Pengangkutan::with('warga.user', 'warga.rute')
                ->where('petugas_id', $userId)
                ->get());

        // Urutkan per rute, lalu per urutan angkut warga (nilai-nilai diindeks ulang agar penomoran kartu & peta berurutan)
        $daftarTugas = $daftarTugas->sortBy('rute_id')->sortBy('urutan')->values();

        return view('petugas_lapangan.rute', compact('daftarTugas'));
    }

    public function updateStatus(Request $request, $id)
    {
        $pengangkutan = Pengangkutan::find($id);
        abort_unless($pengangkutan && $pengangkutan->petugas_id === auth()->id(), 404);

        $request->validate([
            'status' => 'required|in:proses,selesai,lewat',
        ]);

        $statusMap = [
            'proses' => 'Sedang dikerjakan',
            'selesai' => 'Selesai',
            'lewat' => 'Selesai',
        ];

        DB::table('pengangkutan')
            ->where('id', $id)
            ->update([
                'status_tugas' => $statusMap[$request->status] ?? 'Belum dikerjakan',
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Status tugas berhasil diperbarui!');
    }

    private function mapTugas($items)
    {
        return $items->map(function ($item) {
            $warga = $item->warga;
            return (object) [
                'pengangkutan_id' => $item->id,
                'rute_id' => $warga->rute_id,
                'urutan' => (int) ($warga->urutan ?? 0),
                'nama_rute' => $warga->rute->nama_rute ?? '-',
                'nama_warga' => $warga->user->name ?? 'Warga',
                'alamat_lengkap' => $warga->alamat_lengkap ?? '-',
                'latitude' => $warga->latitude,
                'longitude' => $warga->longitude,
                'status_tugas' => $item->status_tugas === 'Selesai' ? 'selesai' : ($item->status_tugas === 'Sedang dikerjakan' ? 'proses' : 'menunggu'),
                'foto_sebelum' => $item->foto_sebelum,
                'foto_sesudah' => $item->foto_sesudah,
                'volume_m3' => $item->volume_m3,
                'berat_kg' => $item->berat_kg,
                'catatan' => $item->catatan,
            ];
        });
    }
}
