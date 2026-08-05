<?php

namespace App\Http\Controllers\Admin;

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
        return view('admin.logistik.index', compact('pengeluaran', 'armada'));
    }
}
