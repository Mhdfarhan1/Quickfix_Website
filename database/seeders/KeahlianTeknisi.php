<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeahlianTeknisiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('keahlian_teknisi')->insert([
            [
                'id_teknisi' => 1,
                'id_keahlian' => 1,
                'harga_min' => 50000,
                'harga_max' => 150000,
                'gambar_layanan' => 'service_ac.jpg'
            ]
        ]);
    }
}
