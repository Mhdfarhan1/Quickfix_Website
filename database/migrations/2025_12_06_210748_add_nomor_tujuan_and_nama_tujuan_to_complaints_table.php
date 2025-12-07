<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->string('nomor_tujuan')->nullable()->after('metode_pembayaran');
            $table->string('nama_tujuan')->nullable()->after('nomor_tujuan');
        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn(['nomor_tujuan', 'nama_tujuan']);
        });
    }
};
