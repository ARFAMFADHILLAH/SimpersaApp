<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notification::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('notifikasi.index', compact('notifikasi'));
    }
}
