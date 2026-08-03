<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TemplateNotifikasi;
use App\Models\JadwalNotifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $templates = TemplateNotifikasi::with('jadwal')->get();
        $jadwalList = JadwalNotifikasi::with('template')->get();

        return view('admin.notifikasi.index', compact('templates', 'jadwalList'));
    }

    // SIMPAN TEMPLATE BARU
    public function storeTemplate(Request $request)
    {
        $validated = $request->validate([
            'kode_template'  => 'required|string|unique:template_notifikasi,kode_template',
            'judul_template' => 'required|string',
            'saluran'        => 'required|in:whatsapp,email,push',
            'subjek'         => 'nullable|string',
            'isi_pesan'      => 'required|string',
        ]);

        TemplateNotifikasi::create($validated);

        return redirect()->back()->with('success', 'Template Notifikasi berhasil disimpan!');
    }

    // UPDATE TEMPLATE
    public function updateTemplate(Request $request, $id)
    {
        $template = TemplateNotifikasi::findOrFail($id);

        $validated = $request->validate([
            'judul_template' => 'required|string',
            'saluran'        => 'required|in:whatsapp,email,push',
            'subjek'         => 'nullable|string',
            'isi_pesan'      => 'required|string',
            'is_aktif'       => 'boolean',
        ]);

        $validated['is_aktif'] = $request->has('is_aktif');
        $template->update($validated);

        return redirect()->back()->with('success', 'Template Notifikasi berhasil diperbarui!');
    }

    // SIMPAN JADWAL PENGIRIMAN
    public function storeJadwal(Request $request)
    {
        $validated = $request->validate([
            'template_id' => 'required|exists:template_notifikasi,id',
            'nama_jadwal' => 'required|string',
            'pemicu'      => 'required|in:harian,mingguan,bulanan,event',
            'waktu_kirim' => 'required',
            'hari_ke'     => 'nullable|numeric',
        ]);

        JadwalNotifikasi::create($validated);

        return redirect()->back()->with('success', 'Jadwal Pengiriman Otomatis berhasil ditambahkan!');
    }

    // UPDATE JADWAL
    public function updateJadwal(Request $request, $id)
    {
        $jadwal = JadwalNotifikasi::findOrFail($id);

        $validated = $request->validate([
            'template_id' => 'required|exists:template_notifikasi,id',
            'nama_jadwal' => 'required|string',
            'pemicu'      => 'required|in:harian,mingguan,bulanan,event',
            'waktu_kirim' => 'required',
            'hari_ke'     => 'nullable|numeric',
            'is_aktif'    => 'boolean',
        ]);

        $validated['is_aktif'] = $request->has('is_aktif');
        $jadwal->update($validated);

        return redirect()->back()->with('success', 'Jadwal Notifikasi berhasil diperbarui!');
    }

    // HAPUS TEMPLATE / JADWAL
    public function destroyTemplate($id)
    {
        TemplateNotifikasi::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Template Notifikasi berhasil dihapus!');
    }

    public function destroyJadwal($id)
    {
        JadwalNotifikasi::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Jadwal Notifikasi berhasil dihapus!');
    }
}