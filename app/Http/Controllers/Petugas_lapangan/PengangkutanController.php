<?php

namespace App\Http\Controllers\Petugas_lapangan;

use App\Http\Controllers\Controller;
use App\Models\Pengangkutan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengangkutanController extends Controller
{
    public function uploadFoto(Request $request, $id)
    {
        // Hanya petugas yang ditugaskan yang boleh mengisi hasil titik ini
        $pengangkutan = Pengangkutan::find($id);
        abort_unless($pengangkutan && $pengangkutan->petugas_id === auth()->id(), 404);

        $request->validate([
            'foto_sebelum' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_sesudah' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'catatan'      => 'nullable|string',
            'volume_m3'    => 'nullable|numeric|min:0',
            'berat_kg'     => 'nullable|numeric|min:0',
        ]);

        $namaFileSebelum = null;
        $namaFileSesudah = null;

        $path = public_path('storage/dokumentasi');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

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

        $update = [
            'catatan'       => $request->catatan,
            'status_tugas'  => 'Selesai',
            'foto_sebelum'  => $namaFileSebelum,
            'foto_sesudah'  => $namaFileSesudah,
            'updated_at'    => now(),
        ];

        if ($request->filled('volume_m3')) {
            $update['volume_m3'] = $request->volume_m3;
        }
        if ($request->filled('berat_kg')) {
            $update['berat_kg'] = $request->berat_kg;
        }

        DB::table('pengangkutan')
            ->where('id', $id)
            ->update($update);

        return redirect()->back()->with('success', 'Dokumentasi & hasil pengangkutan berhasil disimpan!');
    }
}
