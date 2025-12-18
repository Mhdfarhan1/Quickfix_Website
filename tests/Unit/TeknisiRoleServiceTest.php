<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TeknisiRoleService;

class TeknisiRoleServiceTest extends TestCase
{
    public function test_role_teknisi_valid()
    {
        $this->assertTrue(
            TeknisiRoleService::isTeknisi('teknisi')
        );
    }

    public function test_role_pelanggan_bremember_false()
    {
        $this->assertFalse(
            TeknisiRoleService::isTeknisi('pelanggan')
        );
    }

    public function test_role_null_false()
    {
        $this->assertFalse(
            TeknisiRoleService::isTeknisi(null)
        );
    }

    public function test_role_null_return_false()
    {
        $result = TeknisiRoleService::isTeknisi(null);

        $this->assertNotNull($result);
    }

}
