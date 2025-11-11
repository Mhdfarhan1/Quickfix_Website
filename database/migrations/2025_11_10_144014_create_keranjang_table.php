<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('keranjang', function (Blueprint $table) {
            $table->id('id_keranjang');
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_teknisi');
            $table->unsignedBigInteger('id_keahlian');
            $table->integer('harga');
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('id_pelanggan')->references('id_user')->on('user')->onDelete('cascade');
            $table->foreign('id_teknisi')->references('id_teknisi')->on('teknisi')->onDelete('cascade');
            $table->foreign('id_keahlian')->references('id_keahlian')->on('keahlian')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('keranjang');
    }
};
