<?php

namespace App\Http\Controllers\Petugas_lapangan;

use App\Http\Controllers\Controller;
use App\Models\Rute;
use App\Models\Pelanggan;
use App\Models\Pengangkutan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuteController extends Controller
{
    public function index()
    {
        $dataRute = Rute::withCount('pelanggan')->get();
        return view('petugas_lapangan.rute.index', compact('dataRute'));
    }

    public function show($id)
    {
        $rute = Rute::with('pelanggan.user')->findOrFail($id);
        return view('petugas_lapangan.rute.detail', compact('rute'));
    }

    public function tugasHarian()
    {
        $userId = auth()->id();

        $daftarTugas = Pengangkutan::with('pelanggan.user', 'pelanggan.rute')
            ->where('petugas_id', $userId)
            ->whereDate('tanggal_tugas', now()->toDateString())
            ->get()
            ->map(function ($item) {
                $pelanggan = $item->pelanggan;
                return (object) [
                    'pengangkutan_id' => $item->id,
                    'nama_rute' => $pelanggan->rute->nama_rute ?? '-',
                    'nama_pelanggan' => $pelanggan->user->name ?? 'Warga',
                    'alamat_lengkap' => $pelanggan->alamat_lengkap ?? '-',
                    'latitude' => $pelanggan->latitude,
                    'longitude' => $pelanggan->longitude,
                    'status_tugas' => $item->status_tugas === 'Selesai' ? 'selesai' : ($item->status_tugas === 'Sedang dikerjakan' ? 'proses' : 'menunggu'),
                ];
            });

        if ($daftarTugas->isEmpty()) {
            $daftarTugas = Pelanggan::with('user', 'rute')
                ->take(10)
                ->get()
                ->map(function ($p) {
                    return (object) [
                        'pengangkutan_id' => null,
                        'nama_rute' => $p->rute->nama_rute ?? 'Rute Default',
                        'nama_pelanggan' => $p->user->name ?? 'Warga',
                        'alamat_lengkap' => $p->alamat_lengkap ?? '-',
                        'latitude' => $p->latitude,
                        'longitude' => $p->longitude,
                        'status_tugas' => 'menunggu',
                    ];
                });
        }

        return view('petugas_lapangan.rute', compact('daftarTugas'));
    }

    public function updateStatus(Request $request, $id)
    {
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

    public function uploadFoto(Request $request, $id)
    {
        $request->validate([
            'foto_sebelum' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_sesudah' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'catatan' => 'nullable|string',
        ]);

        $path = public_path('storage/dokumentasi');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $namaFileSebelum = null;
        $namaFileSesudah = null;

        if ($request->hasFile('foto_sebelum')) {
            $file = $request->file('foto_sebelum');
            $namaFileSebelum = 'sebelum_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($path, $namaFileSebelum);
        }

        if ($request->hasFile('foto_sesudah')) {
            $file = $request->file('foto_sesudah');
            $namaFileSesudah = 'sesudah_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($path, $namaFileSesudah);
        }

        DB::table('pengangkutan')->updateOrInsert(
            ['id' => $id],
            [
                'catatan' => $request->catatan,
                'status_tugas' => 'Selesai',
                'foto_sebelum' => $namaFileSebelum,
                'foto_sesudah' => $namaFileSesudah,
                'updated_at' => now(),
            ]
        );

        return redirect()->back()->with('success', 'Dokumentasi foto berhasil diunggah!');
    }
}
