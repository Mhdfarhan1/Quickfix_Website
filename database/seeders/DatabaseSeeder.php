<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ========================
        // 1️⃣ USERS
        // ========================
        DB::table('user')->insert([
            [
                'id_user' => 1,
                'nama' => 'Andi Setiawan',
                'email' => 'andi@example.com',
                'password' => Hash::make('password123'),
                'role' => 'pelanggan',
                'no_hp' => '081234567890',
                'foto_profile' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 2,
                'nama' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'password' => Hash::make('password123'),
                'role' => 'teknisi',
                'no_hp' => '081298765432',
                'foto_profile' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========================
        // 2️⃣ TEKNISI
        // ========================
        DB::table('teknisi')->insert([
            [
                'id_teknisi' => 1,
                'id_user' => 2,
                'deskripsi' => 'Spesialis perbaikan AC dan kulkas.',
                'rating_avg' => 4.8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========================
        // 3️⃣ KATEGORI
        // ========================
        DB::table('kategori')->insert([
            ['id_kategori' => 1, 'nama_kategori' => 'Elektronik', 'created_at' => now(), 'updated_at' => now()],
            ['id_kategori' => 2, 'nama_kategori' => 'Bangunan', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ========================
        // 4️⃣ KEAHLIAN
        // ========================
        DB::table('keahlian')->insert([
            [
                'id_keahlian' => 1,
                'id_kategori' => 1,
                'nama_keahlian' => 'Servis AC',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_keahlian' => 2,
                'id_kategori' => 2,
                'nama_keahlian' => 'Renovasi Rumah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========================
        // 5️⃣ KEAHLIAN_TEKNISI (Pivot)
        // ========================
        DB::table('keahlian_teknisi')->insert([
            [
                'id_teknisi' => 1,
                'id_keahlian' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========================
        // 6️⃣ PEMESANAN
        // ========================
        DB::table('pemesanan')->insert([
            [
                'id_pemesanan' => 1,
                'id_pelanggan' => 1,
                'id_teknisi' => 1,
                'id_keahlian' => 1,
                'kode_pemesanan' => 'QFX-001',
                'tanggal_booking' => now()->addDays(1),
                'keluhan' => 'AC tidak dingin sejak kemarin.',
                'status' => 'proses',
                'harga' => 250000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========================
        // 7️⃣ ALAMAT
        // ========================
        DB::table('alamat')->insert([
            [
                'id_alamat' => 1,
                'id_user' => 1,
                'label' => 'Rumah',
                'alamat_lengkap' => 'Jl. Mawar No. 12, Jakarta Selatan',
                'kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'latitude' => -6.2297,
                'longitude' => 106.6894,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========================
        // 8️⃣ NOTIFIKASI
        // ========================
        DB::table('notifikasi')->insert([
            [
                'id_notifikasi' => 1,
                'id_user' => 1,
                'judul' => 'Pemesanan Diterima',
                'pesan' => 'Teknisi telah menerima pemesanan kamu.',
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========================
        // 9️⃣ PEMBAYARAN
        // ========================
        DB::table('pembayaran')->insert([
            [
                'id_pembayaran' => 1,
                'id_pemesanan' => 1,
                'metode' => 'transfer',
                'jumlah' => 250000,
                'status' => 'sukses',
                'bukti_transfer' => 'bukti_transfer_001.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========================
        // 🔟 ULASAN
        // ========================
        DB::table('ulasan')->insert([
            [
                'id_ulasan' => 1,
                'id_pemesanan' => 1,
                'id_pelanggan' => 1,
                'id_teknisi' => 1,
                'rating' => 5,
                'komentar' => 'Pekerjaan cepat dan hasilnya bagus!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Log sukses
        echo "✅ DatabaseSeeder berhasil dijalankan dengan data dummy QuickFix!\n";
    }
}
