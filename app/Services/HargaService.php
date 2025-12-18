<?php
namespace App\Services;

class HargaService
{
    public static function validHarga($harga): bool
    {
        return is_numeric($harga) && $harga >= 0;
    }

    public static function hitung($qty, $harga)
    {
        return $qty * $harga;
    }

}
