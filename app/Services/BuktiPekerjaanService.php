<?php

namespace App\Services;

class BuktiPekerjaanService
{
    public static function hasEvidence(int $count): bool
    {
        return $count > 0;
    }
}
