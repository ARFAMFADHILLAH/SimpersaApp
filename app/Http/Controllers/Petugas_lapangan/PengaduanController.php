<?php

namespace App\Http\Controllers\Petugas_lapangan;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengaduanController extends Controller
{
    public function index()
    {
        $dataPengaduan = Pengaduan::with('warga.user')
            ->latest()
            ->get();

        return view('petugas_lapangan.pengaduan.index', compact('dataPengaduan'));
    }

    public function show($id)
    {
        $pengaduan = Pengaduan::with('warga.user')->findOrFail($id);
        return view('petugas_lapangan.pengaduan.show', compact('pengaduan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_respon' => 'required|in:Belum Dikerjakan,Sedang Dikerjakan,Selesai',
            'catatan_petugas' => 'nullable|string|max:500',
            'foto_penyelesaian' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'status_respon' => $request->status_respon,
            'catatan_petugas' => $request->catatan_petugas,
        ];

        if ($request->hasFile('foto_penyelesaian')) {
            $file = $request->file('foto_penyelesaian');
            $filename = 'penyelesaian_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/pengaduan'), $filename);
            $data['foto_bukti'] = $filename;
        }

        Pengaduan::where('id', $id)->update($data);

        return redirect()->route('petugas.pengaduan.index')
            ->with('success', 'Status pengaduan berhasil diperbarui.');
    }
}
