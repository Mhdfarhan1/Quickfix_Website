<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('otps_reset_password', function (Blueprint $table) {
            $table->id();

            $table->string('email')->index();

            // kode OTP 6 digit
            $table->string('otp', 6);

            // kapan kode kadaluarsa
            $table->dateTime('expires_at');

            // apakah OTP sudah dipakai
            $table->boolean('is_used')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otps_reset_password');
    }
};
