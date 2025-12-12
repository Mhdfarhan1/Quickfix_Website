<?php

namespace App\Services;

use App\Models\Notifikasi;
use Pusher\Pusher;

class Notify
{
    /**
     * ===== FUNGSI UTAMA =====
     */
    public static function send($userId, $title, $message)
    {
        // Simpan ke database
        Notifikasi::create([
            'id_user' => $userId,
            'judul'   => $title,
            'pesan'   => $message,
            'tipe'    => 'pemberitahuan'
        ]);

        // Kirim realtime melalui Pusher
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            ['cluster' => env('PUSHER_APP_CLUSTER')]
        );

        $pusher->trigger("notifikasi.$userId", 'new-notification', [
            'judul' => $title,
            'pesan' => $message,
        ]);
    }

    /**
     * ========== NOTIFIKASI PAYMENT ==========
     */
    public static function paymentSuccess($userId)
    {
        return self::send(
            $userId,
            "Pembayaran Berhasil",
            "Pembayaran kamu sudah dikonfirmasi. Teknisi akan segera mengerjakan pesananmu."
        );
    }


    /**
     * ========== NOTIFIKASI STATUS PEKERJAAN ==========
     * Untuk pelanggan
     */
    public static function statusChanged($userId, $status)
    {
        $map = [
            'dijadwalkan'     => ["Pekerjaan Diterima", "Teknisi menerima pesanan Anda."],
            'menuju_lokasi'   => ["Teknisi Menuju Lokasi", "Teknisi sekarang sedang menuju lokasi Anda."],
            'sedang_bekerja'  => ["Pekerjaan Dimulai", "Teknisi sedang mengerjakan pesanan Anda."],
            'selesai_pending_verifikasi' => [
                "Menunggu Konfirmasi",
                "Teknisi telah menyelesaikan pekerjaan. Mohon cek kembali dan lakukan konfirmasi."
            ],
            'perbaikan' => [
                "Perbaikan Diajukan",
                "Pelanggan meminta perbaikan. Teknisi akan memberikan update selanjutnya."
            ],
            'in_dispute' => [
                "Dalam Sengketa",
                "Pesanan sedang dalam proses pemeriksaan oleh admin."
            ],
            'selesai_confirmed' => [
                "Pekerjaan Dikonfirmasi",
                "Terima kasih! Pesanan telah dikonfirmasi selesai."
            ],
            'selesai' => [
                "Pekerjaan Selesai",
                "Pekerjaan telah selesai sepenuhnya. Silakan berikan ulasan."
            ],
            'batal' => [
                "Pesanan Dibatalkan",
                "Pesanan dibatalkan."
            ],
        ];

        if (isset($map[$status])) {
            self::send($userId, $map[$status][0], $map[$status][1]);
        }
    }

    /**
     * ========== NOTIFIKASI UNTUK TEKNISI ==========
     */

    public static function technicianWorkConfirmed($techUserId)
    {
        self::send(
            $techUserId,
            "Pekerjaan Dikonfirmasi Pelanggan",
            "Pelanggan telah mengonfirmasi pekerjaan. Dana akan cair setelah masa verifikasi selesai."
        );
    }

    public static function technicianRefundRequested($techUserId)
    {
        self::send(
            $techUserId,
            "Permintaan Refund Diajukan",
            "Pelanggan meminta refund. Silakan unggah bukti tambahan atau hubungi admin."
        );
    }

    public static function technicianWorkDisputeResolved($techUserId)
    {
        self::send(
            $techUserId,
            "Sengketa Selesai",
            "Sengketa pesanan telah diselesaikan oleh admin."
        );
    }

    /**
     * ========== NOTIFIKASI LAINNYA ==========
     */
    public static function requestRating($userId)
    {
        self::send(
            $userId,
            "Beri Ulasan",
            "Pekerjaan telah selesai! Silakan berikan ulasan untuk teknisi."
        );
    }
}
