<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        /**
         * =======================
         * 1. PEMESANAN
         * =======================
         */
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id('id_pemesanan');
            $table->string('kode_pemesanan', 20)->unique();

            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_teknisi')->nullable();
            $table->unsignedBigInteger('id_keahlian');
            $table->unsignedBigInteger('id_alamat')->nullable();

            $table->date('tanggal_booking')->nullable();
            $table->time('jam_booking')->nullable();
            $table->text('keluhan')->nullable();

            $table->decimal('harga', 12, 2)->nullable();
            $table->decimal('gross_amount', 12, 2)->nullable();

            // Payment
            $table->enum('payment_status', ['pending', 'hold', 'settlement', 'failed'])->default('pending');
            $table->string('payment_type', 50)->nullable();
            $table->string('midtrans_transaction_id', 50)->nullable();
            $table->string('payment_url', 255)->nullable();
            $table->string('snap_token', 100)->nullable();

            // Status Pekerjaan
            $table->enum('status_pekerjaan', [
                'menunggu_diterima',
                'dijadwalkan',
                'menuju_lokasi',
                'sedang_bekerja',
                'selesai',
                'batal'
            ])->default('menunggu_diterima');

            // ✅ HAPUS after() karena bikin error di CREATE TABLE
            $table->timestamp('released_at')->nullable();

            $table->timestamps();

            // ✅ Foreign Key
            $table->foreign('id_pelanggan')->references('id_user')->on('user')->onDelete('cascade');
            $table->foreign('id_teknisi')->references('id_teknisi')->on('teknisi')->onDelete('set null');
            $table->foreign('id_keahlian')->references('id_keahlian')->on('keahlian')->onDelete('cascade');
            $table->foreign('id_alamat')->references('id_alamat')->on('alamat')->onDelete('set null');
        });

        /**
         * =======================
         * 2. FOTO KELUHAN
         * =======================
         */
        Schema::create('foto_keluhan', function (Blueprint $table) {
            $table->id('id_foto_keluhan');
            $table->unsignedBigInteger('id_pemesanan');
            $table->string('foto_keluhan');
            $table->timestamps();

            $table->foreign('id_pemesanan')
                ->references('id_pemesanan')
                ->on('pemesanan')
                ->onDelete('cascade');
        });

        /**
         * =======================
         * 3. BUKTI PEKERJAAN
         * =======================
         */
        Schema::create('bukti_pekerjaan', function (Blueprint $table) {
            $table->id('id_bukti');
            $table->unsignedBigInteger('id_pemesanan');
            $table->unsignedBigInteger('id_teknisi');
            $table->unsignedBigInteger('id_keahlian');

            $table->text('deskripsi')->nullable();
            $table->string('foto_bukti');
            $table->timestamps();

            $table->foreign('id_pemesanan')->references('id_pemesanan')->on('pemesanan')->onDelete('cascade');
            $table->foreign('id_teknisi')->references('id_teknisi')->on('teknisi')->onDelete('cascade');
            $table->foreign('id_keahlian')->references('id_keahlian')->on('keahlian')->onDelete('cascade');
        });

        /**
         * =======================
         * 4. PEMBAYARAN
         * =======================
         */
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->unsignedBigInteger('id_pemesanan');

            $table->string('metode', 50)->default('transfer');
            $table->decimal('jumlah', 12, 2);
            $table->string('status', 50)->default('pending');

            $table->boolean('is_released')->default(false);
            $table->string('bukti_transfer')->nullable();
            $table->timestamps();

            $table->foreign('id_pemesanan')
                ->references('id_pemesanan')
                ->on('pemesanan')
                ->onDelete('cascade');
        });

        /**
         * =======================
         * 5. LAYANAN TAMBAHAN
         * =======================
         */
        Schema::create('layanan_tambahan', function (Blueprint $table) {
            $table->id('id_layanan_tambahan');
            $table->unsignedBigInteger('id_pemesanan');

            $table->string('nama_layanan');
            $table->integer('harga');
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->foreign('id_pemesanan')
                ->references('id_pemesanan')
                ->on('pemesanan')
                ->onDelete('cascade');
        });

        /**
         * =======================
         * 6. TRACKING GPS
         * =======================
         */
        Schema::create('tracking_gps', function (Blueprint $table) {
            $table->id('id_tracking');
            $table->unsignedBigInteger('id_pemesanan')->unique();
            $table->unsignedBigInteger('id_teknisi');

            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('id_pemesanan')
                ->references('id_pemesanan')
                ->on('pemesanan')
                ->onDelete('cascade');

            $table->foreign('id_teknisi')
                ->references('id_teknisi')
                ->on('teknisi')
                ->onDelete('cascade');
        });

        /**
         * =======================
         * 7. NOTIFIKASI
         * =======================

        
         * =======================
         * 8. ULASAN
         * =======================
         */
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id('id_ulasan');

            $table->unsignedBigInteger('id_pemesanan');
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_teknisi');

            $table->tinyInteger('rating')->default(5);
            $table->text('komentar')->nullable();
            $table->timestamps();

            $table->foreign('id_pemesanan')->references('id_pemesanan')->on('pemesanan')->onDelete('cascade');
            $table->foreign('id_pelanggan')->references('id_user')->on('user')->onDelete('cascade');
            $table->foreign('id_teknisi')->references('id_teknisi')->on('teknisi')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan');
        Schema::dropIfExists('tracking_gps');
        Schema::dropIfExists('layanan_tambahan');
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('bukti_pekerjaan');
        Schema::dropIfExists('foto_keluhan');
        Schema::dropIfExists('pemesanan');
    }
};
