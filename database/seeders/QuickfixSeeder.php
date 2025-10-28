<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class QuickfixSeeder extends Seeder
{
    public function run(): void
    {
        // === USER ===
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

        // === TEKNISI ===
        $teknisiId = DB::table('teknisi')->insertGetId([
            'id_user' => $teknisiUserId,
            'deskripsi' => 'Spesialis servis AC dan alat rumah tangga.',
            'rating_avg' => 4.8,
            'pengalaman' => 5,
            'status' => 'aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // === KATEGORI ===
        $kategoriElektronikId = DB::table('kategori')->insertGetId([
            'nama_kategori' => 'Elektronik',
            'icon' => 'icon-electronic.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $kategoriRumahId = DB::table('kategori')->insertGetId([
            'nama_kategori' => 'Perbaikan Rumah',
            'icon' => 'icon-home.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // === KEAHLIAN ===
        $keahlianId = DB::table('keahlian')->insertGetId([
            'id_kategori' => $kategoriElektronikId,
            'nama_keahlian' => 'Servis AC',
            'deskripsi' => 'Perawatan dan perbaikan AC rumah tangga dan kantor.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // === RELASI TEKNISI - KEAHLIAN ===
        DB::table('keahlian_teknisi')->insert([
            'id_teknisi' => $teknisiId,
            'id_keahlian' => $keahlianId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // === PEMESANAN ===
        DB::table('pemesanan')->insert([
            'kode_pemesanan' => 'ORD-' . strtoupper(Str::random(6)),
            'id_pelanggan' => $pelangganId,
            'id_teknisi' => $teknisiId,
            'id_keahlian' => $keahlianId,
            'tanggal_booking' => now()->addDays(1),
            'keluhan' => 'AC tidak dingin dan berisik',
            'harga' => 250000,
            'status' => 'menunggu',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
