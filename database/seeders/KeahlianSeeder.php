<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeahlianSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('keahlian')->insert([
            [
                'id_kategori' => 1,
                'nama_keahlian' => 'Service AC',
                'deskripsi' => 'Cuci & isi freon AC'
            ],
            [
                'id_kategori' => 2,
                'nama_keahlian' => 'Service atap',
                'deskripsi' => 'Pemasangan dan ganti atap genteng'
            ],
            [
                'id_kategori' => 3,
                'nama_keahlian' => 'Tambal Ban',
                'deskripsi' => 'Perbaikan ban bocor atau kempes'
            ],
            [
                'id_kategori' => 4,
                'nama_keahlian' => 'jumper aki',
                'deskripsi' => 'Menghidupkan mobil dengan aki tekor'
            ],
        ]);

        $teknisi = DB::table('teknisi')->get();
        $keahlian = DB::table('keahlian')->get();

        foreach ($teknisi as $tek) {
            foreach ($keahlian->random(rand(1,3)) as $skill) {
                DB::table('keahlian_teknisi')->insert([
                    'id_teknisi' => $tek->id_teknisi,
                    'id_keahlian' => $skill->id_keahlian,
                    'harga_min' => rand(50000, 100000),
                    'harga_max' => rand(100000, 300000),
                    'gambar_layanan' => 'dummy.jpg'
                ]);
            }
        }
    }
}
