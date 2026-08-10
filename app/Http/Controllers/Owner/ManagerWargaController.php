<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use Illuminate\Http\Request;

class ManagerWargaController extends Controller
{
    /**
     * Monitoring data warga (read-only): nama, no warga, total setor, saldo tabungan.
     */
    public function index(Request $request)
    {
        $keyword = $request->get('q', '');

        $dataWarga = Warga::with('user')
            ->withCount('setoranSampah')
            ->when($keyword, function ($q) use ($keyword) {
                $q->whereHas('user', function ($u) use ($keyword) {
                    $u->where('name', 'like', "%{$keyword}%");
                })->orWhere('no_warga', 'like', "%{$keyword}%");
            })
            ->orderBy('id')
            ->get()
            ->map(function ($warga) {
                return (object) [
                    'id'             => $warga->id,
                    'nama_warga'     => $warga->user->name ?? 'Warga',
                    'no_warga'       => $warga->no_warga,
                    'no_hp'          => $warga->no_hp,
                    'saldo'          => (float) $warga->saldo_tabungan,
                    'total_setoran'  => $warga->setoranSampah->sum('berat_kg'),
                    'jumlah_setoran' => $warga->setoranSampah->count(),
                ];
            });

        $totalNasabah = $dataWarga->count();
        $totalSaldo = (float) $dataWarga->sum('saldo');
        $totalBerat = (float) $dataWarga->sum('total_setoran');

        return view('owner.warga.index', compact('dataWarga', 'keyword', 'totalNasabah', 'totalSaldo', 'totalBerat'));
    }
}