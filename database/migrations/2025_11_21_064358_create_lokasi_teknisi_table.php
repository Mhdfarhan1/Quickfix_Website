<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lokasi_teknisi', function (Blueprint $table) {
            $table->id();

            // relasi ke teknisi
            $table->unsignedBigInteger('id_teknisi');

            // lokasi gps
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);

            $table->timestamps();

            // FOREIGN KEY yang benar
            $table->foreign('id_teknisi')
                ->references('id_teknisi')
                ->on('teknisi')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasi_teknisi');
    }
};
