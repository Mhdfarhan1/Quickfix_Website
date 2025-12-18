<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\StatusPesananService;

class StatusPesananServiceTest extends TestCase
{
    public function test_pesanan_bisa_diterima_jika_menunggu()
    {
        $this->assertTrue(
            StatusPesananService::canAccept('menunggu_diterima')
        );
    }

    public function test_pesanan_tidak_bisa_diterima_jika_bukan_menunggu()
    {
        $this->assertFalse(
            StatusPesananService::canAccept('dijadwalkan')
        );
    }

    public function test_pesanan_bisa_mulai_jika_dijadwalkan()
    {
        $this->assertTrue(
            StatusPesananService::canStart('dijadwalkan')
        );
    }

    public function test_selesai_wajib_ada_bukti()
    {
        $this->assertFalse(
            StatusPesananService::canFinish('sedang_bekerja', false)
        );

        $this->assertTrue(
            StatusPesananService::canFinish('sedang_bekerja', true)
        );
    }

    public function test_jumlah_status_valid()
    {
        $this->assertCount(
            3,
            StatusPesananService::allowedStatuses()
        );
    }



}
