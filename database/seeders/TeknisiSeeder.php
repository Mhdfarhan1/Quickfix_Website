<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeknisiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('teknisi')->insert([
            [
                'id_user' => 2,
                'deskripsi' => 'Teknisi AC berpengalaman 5 tahun',
                'rating_avg' => 4.7,
                'pengalaman' => 5,
                'status' => 'aktif',
                'is_verified' => true,
            ],
            [
                'id_user' => 3,
                'deskripsi' => 'Teknisi listrik rumah tangga',
                'rating_avg' => 4.5,
                'pengalaman' => 3,
                'status' => 'aktif',
                'is_verified' => false,
            ]
        ]);
    }
}
