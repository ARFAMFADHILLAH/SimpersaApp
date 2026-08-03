<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use App\Models\Armada;

class LogistikController extends Controller
{
    public function index()
    {
        $pengeluaran = Pengeluaran::with('armada')
            ->latest('tanggal_pengeluaran')
            ->paginate(15);
        $armada = Armada::all();
        return view('administrasi.logistik.index', compact('pengeluaran', 'armada'));
    }

    public function create()
    {
        $armada = Armada::all();
        return view('administrasi.logistik.create', compact('armada'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'armada_id' => 'required|exists:armada,id',
            'tanggal_pengeluaran' => 'required|date',
            'kategori_biaya' => 'required|in:BBM,Servis,Ban,Alat,Lainnya',
            'jumlah_biaya' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'bukti_foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('bukti_foto');
        $data['status_verifikasi'] = 'Menunggu';

        if ($request->hasFile('bukti_foto')) {
            $data['bukti_foto'] = $request->file('bukti_foto')->store('logistik', 'public');
        }

        Pengeluaran::create($data);

        return redirect()->route('administrasi.logistik.index')
            ->with('success', 'Pengeluaran operasional berhasil dicatat.');
    }
}
