<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class TeknisiSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $fotoTeknisi = [
            'fluOt6C7VGFpPAZQO34ABLTxGLjs8RVEgZKhc3CI.jpg',
            'foto_teknisi1.jpeg',
            'foto_teknisi2.jpeg',
            'foto_teknisi3.jpeg',
            'foto_teknisi4.jpeg',
            'foto_teknisi5.jpeg',
            'foto_teknisi6.jpeg',
            'foto_teknisi7.jpeg',
        ];

        $buktiImages = [
            'bukti1.jpeg',
            'bukti2.jpeg',
            'bukti3.jpeg',
            'bukti4.jpeg',
            'bukti5.jpeg',
            'bukti6.jpeg',
            'bukti7.jpeg',
            'bukti8.jpeg',
            'bukti9.jpeg',
            'bukti10.jpeg',

        ];

        $gambarLayanan = [
            'gambar_layanan1.jpeg',
            'gambar_layanan2.jpeg',
            'gambar_layanan3.jpeg',
            'gambar_layanan4.jpeg',
            'gambar_layanan5.jpeg',
            'gambar_layanan6.jpeg',
            'gambar_layanan7.jpeg',
            'gambar_layanan8.jpeg',
        ];

        // Ambil semua alamat pelanggan (misal user.role='pelanggan')
        $alamatIds = DB::table('alamat')->pluck('id_alamat')->toArray();

        for ($i = 1; $i <= 12; $i++) {

            // 1️⃣ Buat user teknisi
            $foto = $fotoTeknisi[array_rand($fotoTeknisi)];
            $userId = DB::table('user')->insertGetId([
                'nama' => 'Teknisi ' . $i,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('password'),
                'role' => 'teknisi',
                'no_hp' => '08123' . rand(1000000, 9999999),
                'foto_profile' => $foto,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2️⃣ Buat data teknisi
            $idTeknisi = DB::table('teknisi')->insertGetId([
                'id_user' => $userId,
                'deskripsi' => 'Teknisi profesional dengan pengalaman ' . rand(1, 10) . ' tahun.',
                'rating_avg' => rand(30,50)/10, // 3.0 - 5.0
                'pengalaman' => rand(1,10),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3️⃣ Ambil 4 keahlian random
            $keahlianIds = DB::table('keahlian')->inRandomOrder()->limit(4)->pluck('id_keahlian')->toArray();

            foreach ($keahlianIds as $idKeahlian) {
                $gambar = $gambarLayanan[array_rand($gambarLayanan)];
                $hargaMin = rand(50000, 150000);
                $hargaMax = $hargaMin + rand(50000, 100000);

                DB::table('keahlian_teknisi')->insert([
                    'id_teknisi' => $idTeknisi,
                    'id_keahlian' => $idKeahlian,
                    'harga_min' => $hargaMin,
                    'harga_max' => $hargaMax,
                    'gambar_layanan' => $gambar,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 4️⃣ Buat 1-2 pemesanan dummy per teknisi
            $jumlahPemesanan = rand(1,2);

            for ($j=0; $j<$jumlahPemesanan; $j++) {
                $idAlamat = $alamatIds[array_rand($alamatIds)];
                $keahlian = $keahlianIds[array_rand($keahlianIds)];
                $harga = rand(100000, 500000);

                $idPemesanan = DB::table('pemesanan')->insertGetId([
                    'kode_pemesanan' => strtoupper(Str::random(10)),
                    'id_pelanggan' => DB::table('user')->where('role','pelanggan')->inRandomOrder()->first()->id_user,
                    'id_teknisi' => $idTeknisi,
                    'id_keahlian' => $keahlian,
                    'id_alamat' => $idAlamat,
                    'tanggal_booking' => now()->toDateString(),
                    'jam_booking' => now()->format('H:i:s'),
                    'keluhan' => 'Keluhan contoh untuk pemesanan ' . $j,
                    'harga' => $harga,
                    'payment_status' => 'settlement',
                    'payment_type' => 'bank_transfer',
                    'midtrans_transaction_id' => 'TRX' . rand(1000,9999),
                    'status_pekerjaan' => 'menunggu_diterima',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // 5️⃣ Buat bukti pekerjaan terkait pemesanan
                $bukti = $buktiImages[array_rand($buktiImages)];

                DB::table('bukti_pekerjaan')->insert([
                    'id_pemesanan' => $idPemesanan,
                    'id_teknisi' => $idTeknisi,
                    'id_keahlian' => $keahlian,
                    'deskripsi' => 'Bukti pekerjaan untuk pemesanan ' . $idPemesanan,
                    'foto_bukti' => $bukti,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
