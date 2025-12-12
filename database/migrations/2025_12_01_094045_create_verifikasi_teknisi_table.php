<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * UP → Menambah kolom jika belum ada
     */
    public function up()
    {
        Schema::create('verifikasi_teknisi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_teknisi')->nullable();
            $table->string('nik')->nullable();
            $table->string('nama')->nullable();
            $table->string('rekening')->nullable();
            $table->string('bank')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('account_name_verified')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('foto_ktp')->nullable();
            $table->string('foto_skck')->nullable();
            $table->string('buku_tabungan')->nullable();
            $table->date('skck_expired')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps(); // creates created_at and updated_at nullable? by default not nullable; if you need nullable use ->nullableTimestamps pattern or explicit.
        });
    }

    public function down()
    {
        Schema::dropIfExists('verifikasi_teknisi');
    }

};
