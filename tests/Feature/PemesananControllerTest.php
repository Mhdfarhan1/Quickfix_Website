<?php

namespace Tests\Feature;

use Tests\TestCase;

class PemesananControllerTest extends TestCase
{
    /**
     * GET ALL PEMESANAN
     * Route: GET /api/get_pemesanan
     */
    public function test_get_semua_pemesanan()
    {
        $response = $this->getJson('/api/get_pemesanan');

        $response->assertStatus(200);
    }

    /**
     * GET DETAIL PEMESANAN (ID TIDAK ADA)
     * Route: GET /api/pemesanan/{id}
     */
    public function test_get_detail_pemesanan_tanpa_auth()
    {
        $response = $this->getJson('/api/pemesanan/999999');

        $response->assertStatus(401);
    }


    /**
     * GET PEMESANAN BY USER TANPA LOGIN
     * Route: GET /api/get_pemesanan_by_user
     */
    public function test_get_pemesanan_by_user_tanpa_auth()
    {
        $response = $this->getJson('/api/get_pemesanan_by_user');

        $response->assertStatus(401);
    }

    /**
     * POST ADD PEMESANAN (VALIDASI GAGAL)
     * Route: POST /api/add_pemesanan
     */
    public function test_add_pemesanan_validasi_gagal()
    {
        $response = $this->postJson('/api/add_pemesanan', []);

        $response->assertStatus(500);
    }

}
