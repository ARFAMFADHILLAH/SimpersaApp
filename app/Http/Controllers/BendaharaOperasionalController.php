<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Armada;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BendaharaOperasionalController extends Controller
{
    public function index(Request $request)
    {
        $kategoriFilter = $request->get('kategori', '');
        $bulanFilter = $request->get('bulan', Carbon::now()->format('Y-m'));

        $query = Pengeluaran::with('armada');

        if ($kategoriFilter) {
            $query->where('kategori_biaya', $kategoriFilter);
        }

        $query->whereYear('tanggal_pengeluaran', substr($bulanFilter, 0, 4))
            ->whereMonth('tanggal_pengeluaran', substr($bulanFilter, 5, 2));

        $dataPengeluaran = $query->latest('tanggal_pengeluaran')->get();
        $dataArmada = Armada::all();

        $totalBiaya = $dataPengeluaran->sum('jumlah_biaya');
        $totalBbm = $dataPengeluaran->where('kategori_biaya', 'BBM')->sum('jumlah_biaya');
        $totalServis = $dataPengeluaran->where('kategori_biaya', 'Servis Kendaraan')->sum('jumlah_biaya');
        $totalLainnya = $totalBiaya - $totalBbm - $totalServis;

        return view('bendahara.pengeluaran.index', compact(
            'dataPengeluaran',
            'dataArmada',
            'kategoriFilter',
            'bulanFilter',
            'totalBiaya',
            'totalBbm',
            'totalServis',
            'totalLainnya'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'armada_id' => 'nullable|exists:armada,id',
            'tanggal_pengeluaran' => 'required|date',
            'kategori_biaya' => 'required|string|max:100',
            'jumlah_biaya' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'bukti_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status_verifikasi' => 'sometimes|in:Menunggu,Disetujui,Ditolak',
        ]);

        $data = $request->except('_token', 'bukti_foto');

        if ($request->hasFile('bukti_foto')) {
            $data['bukti_foto'] = $request->file('bukti_foto')->store('pengeluaran-bukti', 'public');
        }

        $data['status_verifikasi'] = $request->status_verifikasi ?? 'Disetujui';

        Pengeluaran::create($data);

        return redirect()->route('bendahara.operasional.index')
            ->with('success', 'Pengeluaran operasional berhasil dicatat.');
    }

    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:Disetujui,Ditolak',
            'catatan_verifikasi' => 'nullable|string|max:500',
        ]);

        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->update([
            'status_verifikasi' => $request->status_verifikasi,
            'catatan_verifikasi' => $request->catatan_verifikasi,
        ]);

        $message = $request->status_verifikasi === 'Disetujui'
            ? 'Klaim biaya operasional berhasil disetujui.'
            : 'Klaim biaya operasional ditolak.';

        return redirect()->route('bendahara.operasional.index')
            ->with('success', $message);
    }

    public function destroy($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->delete();

        return redirect()->route('bendahara.operasional.index')
            ->with('success', 'Data pengeluaran berhasil dihapus.');
    }
}
