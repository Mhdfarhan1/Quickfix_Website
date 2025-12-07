<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            // string karena bisa diisi angka atau ID pembayaran (misal: INV-00123)
            $table->string('nominal_id')
                  ->nullable()
                  ->after('metode_pembayaran'); // sesuaikan dengan kolom yang sudah ada
        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn('nominal_id');
        });
    }
};
