<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class PemesananModelTest extends TestCase
{
    public function test_dapat_membuat_pemesanan()
    {
        DB::table('pemesanan')->insert([
            'kode_pemesanan' => 'TEST-UNIT-001',
            'id_pelanggan' => 1,
            'id_teknisi' => 1,
            'id_keahlian' => 1,
            'id_alamat' => 1,
            'tanggal_booking' => now(),
            'jam_booking' => '09:00:00',
            'status_pekerjaan' => 'menunggu_diterima',
            'harga' => 10000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertDatabaseHas('pemesanan', [
            'kode_pemesanan' => 'TEST-UNIT-001'
        ]);

        $this->assertDatabaseCount('pemesanan', 1);

    }

    public function test_dapat_menghapus_pemesanan()
    {
        DB::table('pemesanan')->insert([
            'kode_pemesanan' => 'TEST-UNIT-002',
            'id_pelanggan' => 1,
            'id_teknisi' => 1,
            'id_keahlian' => 1,
            'id_alamat' => 1,
            'tanggal_booking' => now(),
            'jam_booking' => '10:00:00',
            'status_pekerjaan' => 'menunggu_diterima',
            'harga' => 15000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('pemesanan')
            ->where('kode_pemesanan', 'TEST-UNIT-002')
            ->delete();

        $this->assertDatabaseMissing('pemesanan', [
            'kode_pemesanan' => 'TEST-UNIT-002'
        ]);
    }
}
