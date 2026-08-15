<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\PenarikanSaldo;
use App\Models\Warga;
use Illuminate\Http\Request;

class TabunganController extends Controller
{
    /**
     * Daftar saldo tabungan warga + riwayat penarikan.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', '');

        $dataWarga = Warga::with(['user', 'setoranSampah', 'penarikanSaldo'])
            ->orderBy('id')
            ->get()
            ->map(function ($warga) {
                return (object) [
                    'id'          => $warga->id,
                    'nama_warga'  => $warga->user->name ?? 'Warga',
                    'no_warga'    => $warga->no_warga,
                    'no_hp'       => $warga->no_hp,
                    'saldo'       => (float) $warga->saldo_tabungan,
                    'total_beli'  => (float) $warga->totalDisimpan(),
                    'total_ambil' => (float) $warga->totalDitarik(),
                ];
            });

        $riwayatPenarikan = PenarikanSaldo::with('warga.user')
            ->when($status !== '' && in_array($status, ['Diproses', 'Ditarik']), fn ($q) => $q->where('status', $status))
            ->latest('tanggal_penarikan')
            ->get();

        $rekapDiproses = PenarikanSaldo::where('status', 'Diproses')->sum('nominal');
        $rekapDitarik = PenarikanSaldo::where('status', 'Ditarik')->sum('nominal');

        return view('bendahara.tabungan.index', compact(
            'dataWarga',
            'riwayatPenarikan',
            'rekapDiproses',
            'rekapDitarik',
            'status'
        ));
    }

    /**
     * Catat transaksi penarikan dana atas pengajuan warga (status awal: Diproses).
     */
    public function storePenarikan(Request $request)
    {
        $validated = $request->validate([
            'warga_id'          => 'required|exists:warga,id',
            'nominal'           => 'required|numeric|min:1000',
            'tanggal_penarikan' => 'required|date',
            'catatan'           => 'nullable|string|max:255',
        ]);

        $warga = Warga::findOrFail($validated['warga_id']);

        if ($validated['nominal'] > (float) $warga->saldo_tabungan) {
            return redirect()->route('bendahara.tabungan.index')
                             ->with('error', 'Nominal penarikan melebihi saldo tabungan warga.');
        }

        PenarikanSaldo::create([
            'warga_id'          => $warga->id,
            'nominal'           => $validated['nominal'],
            'tanggal_penarikan' => $validated['tanggal_penarikan'],
            'status'            => 'Diproses',
            'catatan'           => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('bendahara.tabungan.index')
                         ->with('success', 'Penarikan saldo warga tercatat (status: Diproses).');
    }

    /**
     * Tandai penarikan sebagai Ditarik + kurangi saldo tabungan warga.
     */
    public function tandaiDitarik($id)
    {
        $penarikan = PenarikanSaldo::findOrFail($id);

        if ($penarikan->status === 'Ditarik') {
            return back()->with('info', 'Penarikan ini sudah berstatus Ditarik.');
        }

        $warga = $penarikan->warga;

        if ($penarikan->nominal > (float) $warga->saldo_tabungan) {
            return back()->with('error', 'Saldo tabungan warga tidak mencukupi untuk penarikan ini.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($penarikan, $warga) {
            $warga->decrement('saldo_tabungan', $penarikan->nominal);
            $penarikan->update(['status' => 'Ditarik']);
        });

        return redirect()->route('bendahara.tabungan.index')
                         ->with('success', 'Penarikan diproses: dana ditarik dan saldo warga diperbarui.');
    }
}