<?php
namespace App\Helpers;

use App\Models\Notifikasi;
use App\Events\NotificationEvent;

class Notify {

    public static function send($userId, $judul, $pesan, $tipe='general')
    {
        // save to DB
        $notif = Notifikasi::create([
            'id_user' => $userId,
            'judul'   => $judul,
            'pesan'   => $pesan,
            'tipe'    => $tipe,
        ]);

        // broadcast
        broadcast(new NotificationEvent(
            $userId,
            $judul,
            $pesan,
            $tipe
        ))->toOthers();

        return $notif;
    }
}
