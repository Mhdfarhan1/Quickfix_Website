<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdditionalSeeder extends Seeder
{
    public function run(): void
    {
        // Alamat
        DB::table('alamat')->insert([
            'id_user' => 1,
            'label' => 'Rumah',
            'alamat_lengkap' => 'Jl. Mawar No. 12, Jakarta Selatan',
            'kota' => 'Jakarta',
            'provinsi' => 'DKI Jakarta',
            'latitude' => -6.2297,
            'longitude' => 106.6894,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Notifikasi
        DB::table('notifikasi')->insert([
            'id_user' => 1,
            'judul' => 'Pemesanan Baru',
            'pesan' => 'Pemesanan kamu telah diterima oleh teknisi.',
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Pembayaran
        DB::table('pembayaran')->insert([
            'id_pemesanan' => 1,
            'metode' => 'transfer',
            'jumlah' => 250000,
            'status' => 'sukses',
            'bukti_transfer' => 'bukti_transfer_001.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Ulasan
        DB::table('ulasan')->insert([
            'id_pemesanan' => 1,
            'id_pelanggan' => 1,
            'id_teknisi' => 1,
            'rating' => 5,
            'komentar' => 'Pelayanan cepat dan ramah!',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
