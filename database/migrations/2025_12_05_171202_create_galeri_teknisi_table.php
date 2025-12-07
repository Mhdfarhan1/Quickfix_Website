<?php

// database/migrations/xxxx_xx_xx_create_galeri_teknisi_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('galeri_teknisi', function (Blueprint $table) {
            $table->id('id_galeri');
            $table->unsignedBigInteger('id_teknisi');
            $table->string('gambar_galeri'); 
            $table->timestamps();

            $table->foreign('id_teknisi')->references('id_teknisi')->on('teknisi')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeri_teknisi');
    }
};
