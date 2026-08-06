<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use Illuminate\Http\Request;

class MitraController extends Controller
{
    public function index()
    {
        $mitra = Mitra::current();

        return view('admin.mitra.index', compact('mitra'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_mitra'    => 'required|string|max:255',
            'no_hp'         => 'nullable|string|max:20',
            'alamat_kontak' => 'nullable|string|max:255',
        ]);

        Mitra::current()->update($validated);

        return redirect()->route('admin.mitra.index')
            ->with('success', 'Profil mitra berhasil diperbarui!');
    }
}