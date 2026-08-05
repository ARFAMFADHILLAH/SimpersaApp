<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Warga;
use App\Models\Pengaduan;
use App\Models\Notification;

class PengaduanController extends Controller
{
    public function index()
    {
        $warga = Warga::where('user_id', Auth::id())->firstOrFail();
        $pengaduan = Pengaduan::where('warga_id', $warga->id)
            ->latest()
            ->paginate(10);
        return view('warga.pengaduan.index', compact('warga', 'pengaduan'));
    }

    public function create()
    {
        $warga = Warga::where('user_id', Auth::id())->firstOrFail();
        return view('warga.pengaduan.create', compact('warga'));
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

        $warga = Warga::where('user_id', Auth::id())->first();

        if (!$warga) {
            return redirect()->back()->with('error', 'Profil warga tidak ditemukan.');
        }

        $pengaduan = new Pengaduan();
        $pengaduan->warga_id = $warga->id;
        $pengaduan->tipe_kendala = $request->tipe_kendala;
        $pengaduan->catatan_lokasi = $request->catatan_lokasi;
        $pengaduan->latitude = $request->latitude ?: ($warga->latitude ?? null);
        $pengaduan->longitude = $request->longitude ?: ($warga->longitude ?? null);

        if ($request->hasFile('foto_bukti')) {
            $pengaduan->foto_bukti = $request->file('foto_bukti')->store('pengaduan', 'public');
        }

        $pengaduan->status_respon = 'Belum Dikerjakan';
        $pengaduan->save();

        // Notifikasi pengaduan baru ke seluruh role admin (gabungan super admin & administrasi)
        $namaPelapor = $warga->user->name ?? 'Warga';
        Notification::kirimKeRole(
            ['admin', 'administrator', 'administrasi', 'petugas_administrasi'],
            'Pengaduan Baru Masuk',
            "Pengaduan \"{$pengaduan->tipe_kendala}\" dari {$namaPelapor} menunggu penanganan.",
            'pengaduan_baru',
            route('admin.pengaduan.show', $pengaduan->id)
        );

        return redirect()->route('warga.pengaduan.index')
            ->with('success', 'Pengaduan berhasil dikirim! Tim kami akan segera merespon.');
    }
}
