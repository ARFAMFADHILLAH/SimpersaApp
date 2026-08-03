<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pelanggan;
use App\Models\Pengaduan;
use App\Models\Notification;

class PengaduanController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::where('user_id', Auth::id())->firstOrFail();
        $pengaduan = Pengaduan::where('pelanggan_id', $pelanggan->id)
            ->latest()
            ->paginate(10);
        return view('pelanggan.pengaduan.index', compact('pelanggan', 'pengaduan'));
    }

    public function create()
    {
        $pelanggan = Pelanggan::where('user_id', Auth::id())->firstOrFail();
        return view('pelanggan.pengaduan.create', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_kendala' => 'required|string',
            'catatan_lokasi' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'foto_bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $pelanggan = Pelanggan::where('user_id', Auth::id())->first();

        if (!$pelanggan) {
            return redirect()->back()->with('error', 'Profil pelanggan tidak ditemukan.');
        }

        $pengaduan = new Pengaduan();
        $pengaduan->pelanggan_id = $pelanggan->id;
        $pengaduan->tipe_kendala = $request->tipe_kendala;
        $pengaduan->catatan_lokasi = $request->catatan_lokasi;
        $pengaduan->latitude = $request->latitude ?: ($pelanggan->latitude ?? null);
        $pengaduan->longitude = $request->longitude ?: ($pelanggan->longitude ?? null);

        if ($request->hasFile('foto_bukti')) {
            $pengaduan->foto_bukti = $request->file('foto_bukti')->store('pengaduan', 'public');
        }

        $pengaduan->status_respon = 'Belum Dikerjakan';
        $pengaduan->save();

        // Notifikasi pengaduan baru ke admin & petugas administrasi
        $namaPelapor = $pelanggan->user->name ?? 'Warga';
        Notification::kirimKeRole(
            ['administrator', 'admin', 'petugas_administrasi', 'administrasi'],
            'Pengaduan Baru Masuk',
            "Pengaduan \"{$pengaduan->tipe_kendala}\" dari {$namaPelapor} menunggu penanganan.",
            'pengaduan_baru',
            route('administrasi.pengaduan.show', $pengaduan->id)
        );

        return redirect()->route('pelanggan.pengaduan.index')
            ->with('success', 'Pengaduan berhasil dikirim! Tim kami akan segera merespon.');
    }
}
