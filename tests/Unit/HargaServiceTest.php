<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\HargaService;

class HargaServiceTest extends TestCase
{
    public function test_harga_valid_jika_positif()
    {
        $this->assertTrue(
            HargaService::validHarga(10000)
        );
    }

    public function test_harga_valid_jika_nol()
    {
        $this->assertTrue(
            HargaService::validHarga(0)
        );
    }

    public function test_harga_tidak_valid_jika_negatif()
    {
        $this->assertFalse(
            HargaService::validHarga(-500)
        );
    }

    public function test_harga_tidak_valid_jika_string()
    {
        $this->assertFalse(
            HargaService::validHarga('abc')
        );
    }

    public function test_hitung_harga_benar()
    {
        $this->assertEquals(
            20000,
            HargaService::hitung(2, 10000)
        );
    }

}
