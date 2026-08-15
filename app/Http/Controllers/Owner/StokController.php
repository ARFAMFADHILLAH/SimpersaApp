<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Support\StokSampah;

class StokController extends Controller
{
    public function index()
    {
        $perJenis = StokSampah::perJenis();
        $totalStok = $perJenis->sum('stok_kg');

        $perKategori = $perJenis->groupBy('kategori')->map(function ($grup) {
            return (object) [
                'kategori'  => $grup->first()->kategori,
                'masuk_kg'  => round($grup->sum('masuk_kg'), 2),
                'keluar_kg' => round($grup->sum('keluar_kg'), 2),
                'stok_kg'   => round($grup->sum('stok_kg'), 2),
            ];
        })->values();

        return view('owner.stok.index', compact('perJenis', 'perKategori', 'totalStok'));
    }
}