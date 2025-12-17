<?php

namespace App\Services;

class WordCountService
{
    public function countWords(string $sentence): int
    {
        if (empty(trim($sentence))) {
            return 0;
        }

        return count(explode(" ", trim($sentence)));
    }
}
