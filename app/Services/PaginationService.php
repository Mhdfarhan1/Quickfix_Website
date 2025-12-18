<?php

namespace App\Services;


class PaginationService
{
    public static function offset(int $page, int $limit): int
    {
        return ($page - 1) * $limit;
    }
}
