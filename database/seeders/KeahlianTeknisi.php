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
                'harga' => 15000,
                'gambar_layanan' => 'service_ac.jpg'
            ]
        ]);
    }
}
