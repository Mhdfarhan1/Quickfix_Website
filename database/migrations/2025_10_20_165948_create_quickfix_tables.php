<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // =======================
        // 1️⃣ TABEL USER
        // =======================
        Schema::create('user', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nama', 150);
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->string('role', 20)->default('pelanggan'); // pelanggan / teknisi / admin
            $table->string('no_hp', 30)->nullable();
            $table->string('foto_profile')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // =======================
        // 2️⃣ TABEL TEKNISI
        // =======================
        Schema::create('teknisi', function (Blueprint $table) {
            $table->id('id_teknisi');
            $table->unsignedBigInteger('id_user');
            $table->text('deskripsi')->nullable();
            $table->float('rating_avg')->default(0);
            $table->integer('pengalaman')->default(0);
            $table->string('status', 50)->default('aktif');
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
        });

        // =======================
        // 3️⃣ TABEL KATEGORI
        // =======================
        Schema::create('kategori', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('nama_kategori', 100);
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // =======================
        // 4️⃣ TABEL KEAHLIAN
        // =======================
        Schema::create('keahlian', function (Blueprint $table) {
            $table->id('id_keahlian');
            $table->unsignedBigInteger('id_kategori');
            $table->string('nama_keahlian', 150);
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('cascade');
        });

        // =======================
        // 5️⃣ PIVOT KEAHLIAN_TEKNISI
        // =======================
        Schema::create('keahlian_teknisi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_teknisi');
            $table->unsignedBigInteger('id_keahlian');
            $table->timestamps();

            $table->foreign('id_teknisi')->references('id_teknisi')->on('teknisi')->onDelete('cascade');
            $table->foreign('id_keahlian')->references('id_keahlian')->on('keahlian')->onDelete('cascade');
        });

        // =======================
        // 6️⃣ TABEL PEMESANAN
        // =======================
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id('id_pemesanan');
            $table->string('kode_pemesanan', 20)->unique();
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_teknisi');
            $table->unsignedBigInteger('id_keahlian');
            $table->date('tanggal_booking');
            $table->text('keluhan')->nullable();
            $table->decimal('harga', 12, 2)->nullable();
            $table->string('status', 50)->default('menunggu'); // menunggu / diproses / selesai / dibatalkan
            $table->timestamps();

            $table->foreign('id_pelanggan')->references('id_user')->on('user')->onDelete('cascade');
            $table->foreign('id_teknisi')->references('id_teknisi')->on('teknisi')->onDelete('cascade');
            $table->foreign('id_keahlian')->references('id_keahlian')->on('keahlian')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
        Schema::dropIfExists('keahlian_teknisi');
        Schema::dropIfExists('keahlian');
        Schema::dropIfExists('kategori');
        Schema::dropIfExists('teknisi');
        Schema::dropIfExists('user');
    }
};
