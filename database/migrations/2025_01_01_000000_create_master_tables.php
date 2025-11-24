<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // =======================
        // 1. USER
        // =======================
        Schema::create('user', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nama', 150);
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->string('role', 20)->default('pelanggan');
            $table->string('no_hp', 30)->nullable();
            $table->string('foto_profile')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // =======================
        // 2. TEKNISI
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
        // 3. KATEGORI & KEAHLIAN
        // =======================
        Schema::create('kategori', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('nama_kategori', 100);
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('keahlian', function (Blueprint $table) {
            $table->id('id_keahlian');
            $table->unsignedBigInteger('id_kategori');
            $table->string('nama_keahlian', 150);
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('cascade');
        });

        // =======================
        // 4. ALAMAT (dengan default)
        // =======================
        Schema::create('alamat', function (Blueprint $table) {
            $table->id('id_alamat');
            $table->unsignedBigInteger('id_user');
            $table->string('label', 100)->nullable();
            $table->string('alamat_lengkap', 255);
            $table->string('kota', 100)->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->foreign('id_user')
                ->references('id_user')
                ->on('user')
                ->onDelete('cascade');
        });

        // =======================
        // 5. KEAHLIAN_TEKNISI
        // =======================
        Schema::create('keahlian_teknisi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_teknisi');
            $table->unsignedBigInteger('id_keahlian');

            // ✅ Tambahkan kolom harga di sini
            $table->integer('harga_min')->nullable();
            $table->integer('harga_max')->nullable();
            $table->string('gambar_layanan')->nullable();

            $table->timestamps();

            // Relasi ke tabel lain
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
        Schema::dropIfExists('keahlian_teknisi');
        Schema::dropIfExists('alamat');
        Schema::dropIfExists('keahlian');
        Schema::dropIfExists('kategori');
        Schema::dropIfExists('teknisi');
        Schema::dropIfExists('user');
    }
};
