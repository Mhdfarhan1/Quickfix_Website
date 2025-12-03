<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;



class AlamatSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');
        $users = DB::table('user')->get();

        foreach ($users as $user) {
            $jumlah = rand(1,3);

            for ($i = 0; $i < $jumlah; $i++) {
                DB::table('alamat')->insert([
                    'id_user' => $user->id_user,
                    'label' => $i == 0 ? 'Rumah' : 'Alamat Tambahan',
                    'alamat_lengkap' => $faker->address,
                    'kota' => $faker->city,
                    'provinsi' => $faker->state,
                    'latitude' => $faker->latitude(-6.3, -6.1),
                    'longitude' => $faker->longitude(106.6, 106.9),
                    'is_default' => $i == 0
                ]);
            }
        }
    }

}
