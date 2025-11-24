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
        Schema::create('otps', function (Blueprint $table) {
            $table->id();

            // Email user yang meminta OTP (sebelum register)
            $table->string('email')->index();

            // 6-digit OTP
            $table->string('otp', 6);

            // Payload data registrasi (nama, password-hash, role, no_hp)
            // disimpan dalam bentuk JSON
            $table->json('payload')->nullable();

            // waktu kadaluarsa OTP
            $table->timestamp('expires_at');

            // apakah OTP sudah dipakai untuk verifikasi
            $table->boolean('is_used')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
