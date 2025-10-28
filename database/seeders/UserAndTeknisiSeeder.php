<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserAndTeknisiSeeder extends Seeder
{
    public function run(): void
    {
        // ====== USER PELANGGAN ======
        $pelangganId = DB::table('user')->insertGetId([
            'nama' => 'Andi Pratama',
            'email' => 'andi@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'pelanggan',
            'no_hp' => '081234567890',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ====== USER TEKNISI ======
        $teknisiUserId = DB::table('user')->insertGetId([
            'nama' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('bcrypt123'),
            'role' => 'teknisi',
            'no_hp' => '082112223333',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ====== DATA TEKNISI ======
        DB::table('teknisi')->insert([
            'id_user' => $teknisiUserId,
            'deskripsi' => 'Spesialis perbaikan AC dan alat rumah tangga.',
            'rating_avg' => 4.7,
            'pengalaman' => 5,
            'status' => 'aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
