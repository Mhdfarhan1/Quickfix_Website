<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user')->insert([
            [
                'nama' => 'Budi Pelanggan',
                'email' => 'budi@gmail.com',
                'password' => Hash::make('123456'),
                'role' => 'pelanggan',
                'no_hp' => '081234567890',
                'is_active' => true,
            ],
            [
                'nama' => 'Ahmad Teknisi',
                'email' => 'ahmad@gmail.com',
                'password' => Hash::make('123456'),
                'role' => 'teknisi',
                'no_hp' => '081299999999',
                'is_active' => true,
            ],
            [
                'nama' => 'Siti Teknisi',
                'email' => 'siti@gmail.com',
                'password' => Hash::make('123456'),
                'role' => 'teknisi',
                'no_hp' => '082222222222',
                'is_active' => true,
            ],
        ]);
    }
}
