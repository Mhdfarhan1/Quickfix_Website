<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kategori')->insert([
            ['nama_kategori' => 'Electronik', 'icon' => 'electronik.png'],
            ['nama_kategori' => 'Renovasi', 'icon' => 'rumah.png'],
            ['nama_kategori' => 'Montir Motor', 'icon' => 'motor.png'],
            ['nama_kategori' => 'Montir Mobil', 'icon' => 'mobil.png'],
        ]);
    }
}
