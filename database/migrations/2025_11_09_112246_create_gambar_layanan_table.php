<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gambar_layanan', function (Blueprint $table) {
            $table->id('id_gambar');
            $table->unsignedBigInteger('id_teknisi');
            $table->unsignedBigInteger('id_keahlian');
            $table->string('url_gambar', 255);
            $table->timestamps();

            $table->foreign('id_teknisi')
                ->references('id_teknisi')
                ->on('teknisi')
                ->onDelete('cascade');

            $table->foreign('id_keahlian')
                ->references('id_keahlian')
                ->on('keahlian')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gambar_layanan');
    }
};
