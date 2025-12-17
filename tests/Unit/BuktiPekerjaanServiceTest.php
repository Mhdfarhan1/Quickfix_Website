<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\BuktiPekerjaanService;

class BuktiPekerjaanServiceTest extends TestCase
{
    public function test_ada_bukti_jika_jumlah_lebih_dari_nol()
    {
        $this->assertTrue(
            BuktiPekerjaanService::hasEvidence(1)
        );
    }

    public function test_tidak_ada_bukti_jika_nol()
    {
        $this->assertFalse(
            BuktiPekerjaanService::hasEvidence(0)
        );
    }

    public function test_tidak_ada_bukti_jika_negatif()
    {
        $this->assertFalse(
            BuktiPekerjaanService::hasEvidence(-1)
        );
    }

    public function test_return_boolean_value()
    {
        $this->assertEquals(
            true,
            BuktiPekerjaanService::hasEvidence(5)
        );
    }

}
