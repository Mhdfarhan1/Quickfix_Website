<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuktiPekerjaanSeeder extends Seeder
{
    public function run()
    {
        $orders = DB::table('pemesanan')
            ->where('status_pekerjaan', 'selesai')
            ->get();

        foreach ($orders as $order) {

            // Ambil keahlian teknisi
            $keahlian = DB::table('teknisi')
                ->where('id_teknisi', $order->id_teknisi)
                ->value('id_keahlian');

            // Skip kalau data tidak lengkap
            if (!$order->id_teknisi || !$keahlian) {
                continue;
            }

            DB::table('bukti_pekerjaan')->insert([
                'id_pemesanan'   => $order->id_pemesanan,
                'id_teknisi'     => $order->id_teknisi,
                'id_keahlian'  => $order->id_keahlian,
                'foto_bukti'     => 'bukti_dummy.jpg',
            ]);
        }
    }
}
