<?php

namespace App\Services;


class TeknisiRoleService
{
    public static function isTeknisi(?string $role): bool
    {
        return $role === 'teknisi';
    }
}
