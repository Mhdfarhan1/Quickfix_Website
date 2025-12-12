<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        /**
         * =======================
         * 1. PEMESANAN (updated)
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

            // Status Pekerjaan (extended)
            $table->enum('status_pekerjaan', [
                'menunggu_diterima',
                'dijadwalkan',
                'menuju_lokasi',
                'sedang_bekerja',
                'selesai_pending_verifikasi',
                'selesai_confirmed',
                'perbaikan',
                'in_dispute',
                'selesai',
                'batal'
            ])->default('menunggu_diterima');

            // Escrow / verification fields
            $table->boolean('verifikasi_by_customer')->nullable()->default(null)->comment('1 = customer confirmed');
            $table->timestamp('verifikasi_at')->nullable();
            $table->timestamp('payout_eligible_at')->nullable();
            $table->timestamp('payout_released_at')->nullable();
            $table->unsignedBigInteger('dispute_id')->nullable();
            $table->timestamp('refund_requested_at')->nullable();
            $table->text('visible_bukti_teknisi')->nullable(); // JSON array of URLs or metadata

            // existing
            $table->timestamp('released_at')->nullable();

            $table->timestamps();

            // Foreign Key
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
         * 7. DISPUTES
         * =======================
         */
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pemesanan');
            $table->enum('tipe', ['refund', 'complaint'])->default('refund');
            $table->decimal('amount', 12, 2)->nullable();
            $table->enum('status', ['open', 'customer_refunded', 'technician_repaid', 'resolved', 'rejected'])->default('open');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('id_pemesanan')
                ->references('id_pemesanan')
                ->on('pemesanan')
                ->onDelete('cascade');
        });

        /**
         * =======================
         * 8. PAYOUTS
         * =======================
         */
        
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_pemesanan');
            $table->unsignedBigInteger('id_teknisi');

            $table->string('reference_id')->unique();
            $table->string('flip_id')->nullable();

            $table->string('bank_code');
            $table->string('account_number');
            $table->string('account_name');

            $table->integer('amount');

            $table->string('status')->default('pending'); // pending, processing, success, failed
            $table->longText('raw_response')->nullable();

            $table->timestamps();
        });

        /**
         * =======================
         * 9. ULASAN
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
        Schema::dropIfExists('payouts');
        Schema::dropIfExists('disputes');
        Schema::dropIfExists('tracking_gps');
        Schema::dropIfExists('layanan_tambahan');
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('bukti_pekerjaan');
        Schema::dropIfExists('foto_keluhan');
        Schema::dropIfExists('pemesanan');
    }
};
