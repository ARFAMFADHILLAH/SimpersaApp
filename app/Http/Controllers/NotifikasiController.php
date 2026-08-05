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

        // Remap defensif: role admin (gabungan) tidak punya halaman administrasi,
        // jadi alihkan tautan pengaduan lama yang masih menuju ke sana.
        $isAdmin = in_array(strtolower(auth()->user()->role->name ?? ''), ['admin', 'administrator', 'administrasi', 'petugas_administrasi']);
        if ($isAdmin) {
            $notifikasi->getCollection()->transform(function ($item) {
                if ($item->tautan && str_contains($item->tautan, '/administrasi/pengaduan/')) {
                    $item->tautan = preg_replace(
                        '#administrasi/pengaduan/(\d+)#',
                        'admin/pengaduan/$1',
                        $item->tautan
                    );
                }
                return $item;
            });
        }

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