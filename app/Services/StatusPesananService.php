<?php

namespace App\Services;

class StatusPesananService
{
    public static function canAccept(string $status): bool
    {
        return $status === 'menunggu_diterima';
    }

    public static function canStart(string $status): bool
    {
        return $status === 'dijadwalkan';
    }

    public static function canArrive(string $status): bool
    {
        return $status === 'menuju_lokasi';
    }

    public static function canFinish(string $status, bool $hasEvidence): bool
    {
        return $status === 'sedang_bekerja' && $hasEvidence;
    }

    public static function allowedStatuses()
    {
        return ['menunggu_diterima', 'dijadwalkan', 'sedang_bekerja'];
    }
}

