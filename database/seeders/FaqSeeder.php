<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('faqs')->insert([
            [
                'pertanyaan' => 'Bagaimana cara memesan layanan?',
                'jawaban' => 'Anda bisa memilih layanan lalu memilih teknisi yang tersedia.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pertanyaan' => 'Bagaimana jika teknisi tidak datang?',
                'jawaban' => 'Silakan kirim aduan melalui menu Bantuan.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
