<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notification::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('notifikasi.index', compact('notifikasi'));
    }

    public function tandaiBaca($id)
    {
        Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back();
    }

    public function tandaiSemuaBaca()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back();
    }
}