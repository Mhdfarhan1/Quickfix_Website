<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('keahlian_teknisi', function (Blueprint $table) {
            $table->string('nama')->nullable()->after('id_teknisi');
            // Make id_keahlian nullable
            $table->unsignedBigInteger('id_keahlian')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keahlian_teknisi', function (Blueprint $table) {
            $table->dropColumn('nama');
            // Revert id_keahlian to not null (might fail if there are nulls)
            // $table->unsignedBigInteger('id_keahlian')->nullable(false)->change();
        });
    }
};
