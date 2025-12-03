<?php

namespace App\Services;

use App\Models\Notifikasi;
use Pusher\Pusher;

class Notify {

    public static function send($userId, $title, $message){
        
        Notifikasi::create([
            'user_id' => $userId,
            'judul' => $title,
            'pesan' => $message
        ]);

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            ['cluster'=>env('PUSHER_APP_CLUSTER')]
        );

        $pusher->trigger("notifikasi.$userId", 'new-notification', [
            'judul' => $title,
            'pesan' => $message,
        ]);
    }

    public static function paymentSuccess($userId)
    {
        return self::send(
            $userId,
            "Pembayaran Berhasil",
            "Pembayaran kamu sudah dikonfirmasi, teknisi akan segera mengerjakan."
        );
    }

    public static function statusChanged($userId, $status)
    {
        $map = [
            'dijadwalkan'     => ["Pekerjaan Diterima", "Teknisi menerima pekerjaan Anda"],
            'menuju_lokasi'   => ["Teknisi Menuju Lokasi", "Teknisi sedang menuju lokasi Anda"],
            'sedang_bekerja'  => ["Pekerjaan Dimulai", "Teknisi sedang mengerjakan pekerjaan Anda"],
            'selesai'         => ["Pekerjaan Selesai", "Pekerjaan selesai! Silahkan berikan ulasan."],
        ];

        if(isset($map[$status])){
            self::send($userId, $map[$status][0], $map[$status][1]);
        }
    }


    public static function requestRating($userId){
        self::send($userId,
            "Beri Ulasan",
            "Pekerjaan selesai! Silahkan berikan ulasan."
        );
    }
}
