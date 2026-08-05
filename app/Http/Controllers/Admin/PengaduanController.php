<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    public function index()
    {
        $pengaduan = Pengaduan::with('warga.user')
            ->latest()
            ->paginate(15);

        return view('admin.pengaduan.index', compact('pengaduan'));
    }

    public function show($id)
    {
        $pengaduan = Pengaduan::with(['warga.user', 'petugas'])->findOrFail($id);
        $petugasLapangan = User::whereIn('role_id', function ($q) {
            $q->select('id')->from('roles')->whereIn('name', ['petugas', 'petugas_lapangan']);
        })->get();

        return view('admin.pengaduan.show', compact('pengaduan', 'petugasLapangan'));
    }

    public function verifikasi(Request $request, $id)
    {
        $pengaduan = Pengaduan::findOrFail($id);
        $pengaduan->update([
            'status_respon' => 'Sedang Dikerjakan',
            'catatan_petugas' => $request->catatan_verifikasi,
        ]);

        return redirect()->route('admin.pengaduan.show', $id)
            ->with('success', 'Pengaduan berhasil diverifikasi.');
    }

    public function dispatch(Request $request, $id)
    {
        $request->validate([
            'petugas_id' => 'required|exists:users,id',
            'catatan_dispatch' => 'nullable|string',
        ]);

        $pengaduan = Pengaduan::findOrFail($id);
        $pengaduan->update([
            'status_respon' => 'Sedang Dikerjakan',
            'petugas_id' => $request->petugas_id,
            'catatan_petugas' => $request->catatan_dispatch,
        ]);

        return redirect()->route('admin.pengaduan.show', $id)
            ->with('success', 'Pengaduan berhasil diteruskan ke petugas lapangan.');
    }
}
