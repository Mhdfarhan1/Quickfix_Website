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
    $this->call([
        AdminSeeder::class,
        UserSeeder::class,
        TeknisiSeeder::class,
        KategoriSeeder::class,
        KeahlianSeeder::class,
        AlamatSeeder::class,
        BuktiPekerjaanSeeder::class,
        FaqSeeder::class,
        BannerSeeder::class,
        
    ]);
    }
}
