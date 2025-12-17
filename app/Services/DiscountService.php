<?php

namespace App\Services;

class DiscountService
{
    public function calculateDiscount(float $discount, float $price): float
    {
        return $price - ($price * ($discount / 100));
    }
}
