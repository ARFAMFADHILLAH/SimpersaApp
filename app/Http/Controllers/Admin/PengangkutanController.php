<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengangkutan;
use App\Models\Warga;
use App\Models\JenisSampah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengangkutanController extends Controller
{
    public function index()
    {
        // Menggunakan Eloquent Model dengan relasi lengkap
        $dataPengangkutan = Pengangkutan::with(['warga.user', 'armada', 'jenisSampah', 'petugas'])->get();

        $dataWarga = Warga::with('user')->get();
        $dataArmada = DB::table('armada')->where('status_kondisi', 'aktif')->get();
        $dataJenisSampah = JenisSampah::all();

        // Ambil data petugas lapangan berdasarkan role
        $rolePetugas = DB::table('roles')->whereIn('name', ['petugas', 'petugas_lapangan'])->pluck('id');
        $dataPetugas = $rolePetugas->isNotEmpty()
            ? User::whereIn('role_id', $rolePetugas)->orderBy('name')->get()
            : collect();

        return view('admin.pengangkutan.index', compact(
            'dataPengangkutan', 
            'dataWarga', 
            'dataArmada', 
            'dataJenisSampah', 
            'dataPetugas'
        ));
    }

    public function store(Request $request)
    {
        // Validasi disesuaikan dengan inputan form lengkap pada view index.blade.php
        $request->validate([
            'warga_id'    => 'required|exists:warga,id',
            'petugas_id'      => 'required|exists:users,id',
            'armada_id'       => 'required|exists:armada,id',
            'jenis_sampah_id' => 'required|exists:jenis_sampah_dan_tarif,id',
            'tanggal_tugas'   => 'required|date',
            'volume_m3'       => 'required|numeric|min:0',
            'berat_kg'        => 'required|numeric|min:0',
            'status_tugas'    => 'required|in:Belum dikerjakan,Sedang dikerjakan,Selesai',
        ]);

        // Simpan data log operasional pengangkutan baru
        Pengangkutan::create($request->all());

        // Redirect kembali ke Dashboard petugas sesuai keinginan Anda
        return redirect()->route('admin.dashboard')->with('success', 'Log operasional & volume sampah berhasil disimpan!');
    }
}