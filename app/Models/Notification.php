<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'tautan',
        'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Kirim notifikasi in-app ke seorang pengguna.
     */
    public static function kirim(int $userId, string $judul, string $pesan, ?string $tipe = null, ?string $tautan = null): self
    {
        return self::create([
            'user_id' => $userId,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'tautan' => $tautan,
            'is_read' => false,
        ]);
    }

    /**
     * Kirim notifikasi ke semua pengguna dengan role tertentu.
     */
    public static function kirimKeRole(array $roleNames, string $judul, string $pesan, ?string $tipe = null, ?string $tautan = null): int
    {
        $roleIds = \DB::table('roles')->whereIn('name', $roleNames)->pluck('id');

        $userIds = User::whereIn('role_id', $roleIds)->pluck('id');

        $count = 0;
        foreach ($userIds as $userId) {
            self::kirim($userId, $judul, $pesan, $tipe, $tautan);
            $count++;
        }

        return $count;
    }
}