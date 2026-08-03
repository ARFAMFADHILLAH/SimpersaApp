<?php

namespace App\Http\Controllers\Petugas_lapangan;

use App\Http\Controllers\Controller;
use App\Models\Pengangkutan;
use App\Models\Pelanggan;
use App\Models\JenisSampah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengangkutanController extends Controller
{
    public function index()
    {
        // Menggunakan Eloquent Model dengan relasi lengkap
        $dataPengangkutan = Pengangkutan::with(['pelanggan.user', 'armada', 'jenisSampah', 'petugas'])->get();

        $dataPelanggan = Pelanggan::with('user')->get();
        $dataArmada = DB::table('armada')->where('status_kondisi', 'aktif')->get();
        $dataJenisSampah = JenisSampah::all();

        // Ambil data petugas lapangan berdasarkan role
        $rolePetugas = DB::table('roles')->where('name', 'petugas_lapangan')->first();
        $dataPetugas = $rolePetugas 
            ? User::where('role_id', $rolePetugas->id)->orWhere('email', 'admin@sistemsampah.com')->get()
            : User::all();

        return view('petugas_lapangan.pengangkutan.index', compact(
            'dataPengangkutan', 
            'dataPelanggan', 
            'dataArmada', 
            'dataJenisSampah', 
            'dataPetugas'
        ));
    }

    public function store(Request $request)
    {
        // Validasi disesuaikan dengan inputan form lengkap pada view index.blade.php
        $request->validate([
            'pelanggan_id'    => 'required|exists:pelanggan,id',
            'petugas_id'      => 'required|exists:users,id',
            'armada_id'       => 'required|exists:armada,id',
            'jenis_sampah_id' => 'required|exists:jenis_sampah_dan_tarif,id', // Sesuaikan nama tabel jenis sampah Anda jika berbeda (misal: jenis_sampah_dan_tarif)
            'tanggal_tugas'   => 'required|date',
            'volume_m3'       => 'required|numeric|min:0',
            'berat_kg'        => 'required|numeric|min:0',
            'status_tugas'    => 'required|in:Belum dikerjakan,Sedang dikerjakan,Selesai',
        ]);

        // Simpan data log operasional pengangkutan baru
        Pengangkutan::create($request->all());

        // Redirect kembali ke Dashboard petugas sesuai keinginan Anda
        return redirect()->route('petugas.dashboard')->with('success', 'Log operasional & volume sampah berhasil disimpan!');
    }

    public function uploadFoto(Request $request, $id)
    {
        $request->validate([
            'foto_sebelum' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_sesudah' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'catatan'      => 'nullable|string',
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


        DB::table('pengangkutan')
            ->where('id', $id)
            ->update([
                'catatan'       => $request->catatan,
                'status_tugas'  => 'Selesai',
                'foto_sebelum'  => $namaFileSebelum,
                'foto_sesudah'  => $namaFileSesudah,
                'updated_at'    => now(),
            ]);

        return redirect()->back()->with('success', 'Dokumentasi foto berhasil diunggah dan disimpan!');
    }
}