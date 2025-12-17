<?php

namespace Tests\Feature;

use Tests\TestCase;

class TeknisiPesananControllerTest extends TestCase
{
    /**
     * Endpoint pesanan baru teknisi TANPA LOGIN
     * Route: GET /api/teknisi/pesanan/baru
     */
    public function test_pesanan_baru_teknisi_tanpa_auth()
    {
        $response = $this->getJson('/api/teknisi/pesanan/baru');

        $response->assertStatus(401);
    }
}
