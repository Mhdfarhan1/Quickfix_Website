<?php

namespace Tests\Unit;

use App\Services\WordCountService;
use PHPUnit\Framework\TestCase;

class WordCountServiceTest extends TestCase
{
    protected WordCountService $wordCountService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->wordCountService = new WordCountService();
    }

    public function test_menghitung_4_kata(): void
    {
        $sentence = "my name is joko";
        $result = $this->wordCountService->countWords($sentence);

        $this->assertEquals(4, $result);
    }

    public function test_kalimat_kosong_menghasilkan_nol(): void
    {
        $this->assertEquals(0, $this->wordCountService->countWords(""));
    }

    public function test_satu_kata(): void
    {
        $this->assertEquals(1, $this->wordCountService->countWords("Hello"));
    }

    public function test_kalimat_bahasa_indonesia(): void
    {
        $sentence = "Saya sedang belajar Laravel";
        $this->assertEquals(4, $this->wordCountService->countWords($sentence));
    }
}
