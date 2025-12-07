<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();

            // ID user yang lapor (tanpa foreign key dulu)
            $table->unsignedBigInteger('user_id');

            // Relasi ke pemesanan (kalau ada)
            $table->unsignedBigInteger('pemesanan_id')->nullable();

            // Relasi ke pembayaran (kalau ada)
            $table->unsignedBigInteger('pembayaran_id')->nullable();

            // Kategori utama komplain
            $table->enum('kategori', [
                'pesanan',
                'pembayaran',
                'aplikasi',
                'akun',
            ]);

            // Jenis masalah spesifik (dropdown di app)
            $table->string('jenis_masalah');

            // Nomor/kode pesanan yang user tulis
            $table->string('nomor_pesanan')->nullable();

            // Metode pembayaran (opsional)
            $table->string('metode_pembayaran')->nullable();

            // Isi laporan user
            $table->text('deskripsi');

            // Lampiran bukti (path file)
            $table->string('lampiran')->nullable();

            // Status komplain
            $table->enum('status', [
                'baru',
                'diproses',
                'selesai',
                'ditolak',
            ])->default('baru');

            // Balasan admin
            $table->text('balasan_admin')->nullable();

            // Admin yang menangani (tanpa FK dulu)
            $table->unsignedBigInteger('admin_id')->nullable();

            $table->timestamps();

            // Index untuk pencarian
            $table->index(['kategori', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
