<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Database\Seeders\AdminSeeder;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan tabel agar tidak bentrok saat di-seed ulang
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('ulasan')->truncate();
        DB::table('notifikasi')->truncate();
        DB::table('bukti_pekerjaan')->truncate();
        DB::table('pembayaran')->truncate();
        DB::table('pemesanan')->truncate();
        DB::table('keahlian_teknisi')->truncate();
        DB::table('alamat')->truncate();
        DB::table('keahlian')->truncate();
        DB::table('kategori')->truncate();
        DB::table('teknisi')->truncate();
        DB::table('user')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ========================
        // 1️⃣ USER
        // ========================
        $idPelanggan = DB::table('user')->insertGetId([
            'nama' => 'Andi Pelanggan',
            'email' => 'pelanggan@example.com',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
            'no_hp' => '081234567890',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $idTeknisiUser = DB::table('user')->insertGetId([
            'nama' => 'Budi Teknisi',
            'email' => 'teknisi@example.com',
            'password' => Hash::make('password'),
            'role' => 'teknisi',
            'no_hp' => '081298765432',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ========================
        // 2️⃣ TEKNISI
        // ========================
        $idTeknisi = DB::table('teknisi')->insertGetId([
            'id_user' => $idTeknisiUser,
            'deskripsi' => 'Teknisi profesional dengan pengalaman lebih dari 5 tahun.',
            'rating_avg' => 4.8,
            'pengalaman' => 5,
            'status' => 'aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ========================
        // 3️⃣ KATEGORI & KEAHLIAN
        // ========================

        $categories = [
            'Renovasi' => ['Cat Dinding', 'Perbaikan Atap', 'Pasang Keramik', 'Instalasi Pipa'],
            'Elektronik' => ['Servis AC', 'Servis Kulkas', 'Servis TV', 'Mesin Cuci'],
            'Teknisi Mobil' => ['Ganti Oli Mobil', 'Tune Up Mobil', 'Servis Rem Mobil', 'Ganti Aki Mobil'],
            'Teknisi Motor' => ['Servis Ringan Motor', 'Ganti Ban Motor', 'Ganti Oli Motor', 'Servis CVT'],
        ];

        $keahlianIds = [];

        foreach ($categories as $catName => $skills) {
            $idKategori = DB::table('kategori')->insertGetId([
                'nama_kategori' => $catName,
                'icon' => 'icon-' . Str::slug($catName) . '.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($skills as $skillName) {
                $idKeahlian = DB::table('keahlian')->insertGetId([
                    'id_kategori' => $idKategori,
                    'nama_keahlian' => $skillName,
                    'deskripsi' => 'Layanan profesional untuk ' . $skillName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $keahlianIds[] = $idKeahlian;
            }
        }

        // ========================
        // 4️⃣ KEAHLIAN TEKNISI
        // ========================

        // Assign random skills to the default technician
        $randomSkills = array_rand(array_flip($keahlianIds), 4); // Pick 4 random skill IDs

        foreach ($randomSkills as $kId) {
            DB::table('keahlian_teknisi')->insert([
                'id_teknisi' => $idTeknisi,
                'id_keahlian' => $kId,
                'harga_min' => rand(50000, 100000),
                'harga_max' => rand(150000, 500000),
                'gambar_layanan' => null, // Let it use default or we can add dummy images later
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ========================
        // 5️⃣ ALAMAT
        // ========================

        // Alamat 1 - Default
        $idAlamatRumah = DB::table('alamat')->insertGetId([
            'id_user' => $idPelanggan,
            'label' => 'Rumah Utama',
            'alamat_lengkap' => 'Jl. Mawar No. 123, Jakarta Selatan',
            'kota' => 'Jakarta Selatan',
            'provinsi' => 'DKI Jakarta',
            'latitude' => -6.2607181,
            'longitude' => 106.7816398,
            'is_default' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Alamat 2 - Tidak default
        $idAlamatKantor = DB::table('alamat')->insertGetId([
            'id_user' => $idPelanggan,
            'label' => 'Kantor',
            'alamat_lengkap' => 'Jl. Jend. Sudirman Kav. 10, Jakarta Pusat',
            'kota' => 'Jakarta Pusat',
            'provinsi' => 'DKI Jakarta',
            'latitude' => -6.2145285,
            'longitude' => 106.8203823,
            'is_default' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ========================
        // 6️⃣ PEMESANAN
        // ========================
        $statusPekerjaan = [
            'menunggu_diterima',
            'dijadwalkan',
            'menuju_lokasi',
            'sedang_bekerja',
            'selesai'
        ];

        $pemesananIds = [];
        for ($i = 1; $i <= 10; $i++) {
            $pemesananIds[] = DB::table('pemesanan')->insertGetId([
                'kode_pemesanan' => strtoupper(Str::random(10)),
                'id_pelanggan' => $idPelanggan,
                'id_teknisi' => $idTeknisi,
                'id_keahlian' => $idKeahlian,
                'id_alamat' => rand(0, 1) == 1 ? $idAlamatRumah : $idAlamatKantor,
                'tanggal_booking' => now()->addDays(rand(0, 5)),
                'keluhan' => 'AC tidak dingin, mohon dicek ulang.',
                'harga' => rand(150000, 500000),
                'gross_amount' => rand(150000, 500000),
                'payment_status' => 'pending',
                'payment_type' => 'bank_transfer',
                'midtrans_transaction_id' => 'TRX' . rand(10000, 99999),
                'status_pekerjaan' => $statusPekerjaan[array_rand($statusPekerjaan)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ========================
        // 7️⃣ PEMBAYARAN
        // ========================
        foreach ($pemesananIds as $idPemesanan) {
            DB::table('pembayaran')->insert([
                'id_pemesanan' => $idPemesanan,
                'metode' => 'transfer',
                'jumlah' => rand(150000, 500000),
                'status' => 'pending',
                'bukti_transfer' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ========================
        // 8️⃣ BUKTI PEKERJAAN
        // ========================
        foreach ($pemesananIds as $idPemesanan) {
            DB::table('bukti_pekerjaan')->insert([
                'id_pemesanan' => $idPemesanan,
                'id_teknisi' => $idTeknisi,
                'id_keahlian' => $idKeahlian,
                'deskripsi' => 'Foto hasil servis pekerjaan',
                'foto_bukti' => 'bukti' . $idPemesanan . '.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ========================
        // 9️⃣ NOTIFIKASI
        // ========================
        DB::table('notifikasi')->insert([
            [
                'id_user' => $idPelanggan,
                'judul' => 'Pesanan Diterima',
                'pesan' => 'Pesanan Anda telah diterima oleh teknisi.',
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => $idTeknisiUser,
                'judul' => 'Pesanan Baru Masuk',
                'pesan' => 'Anda memiliki pesanan baru untuk diperiksa.',
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // ========================
        // 🔟 ULASAN
        // ========================
        foreach ($pemesananIds as $idPemesanan) {
            DB::table('ulasan')->insert([
                'id_pemesanan' => $idPemesanan,
                'id_pelanggan' => $idPelanggan,
                'id_teknisi' => $idTeknisi,
                'rating' => rand(3, 5),
                'komentar' => 'Pelayanan baik dan cepat!',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ========================
        // ✅ Selesai
        // ========================
        $this->command->info('✅ Database seed lengkap berhasil! Semua data relasi telah diisi.');


        $this->call([
            AdminSeeder::class,
        ]);
    }
}
