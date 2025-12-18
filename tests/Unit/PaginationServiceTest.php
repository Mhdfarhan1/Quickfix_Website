<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\PaginationService;

class PaginationServiceTest extends TestCase
{
    public function test_offset_halaman_pertama()
    {
        $this->assertEquals(
            0,
            PaginationService::offset(1, 10)
        );
    }

    public function test_offset_halaman_kedua()
    {
        $this->assertEquals(
            10,
            PaginationService::offset(2, 10)
        );
    }

    public function test_offset_halaman_ketiga()
    {
        $this->assertEquals(
            20,
            PaginationService::offset(3, 10)
        );
    }
}
