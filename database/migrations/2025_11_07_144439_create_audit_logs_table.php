<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('request_id', 100)->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable()->index();
            $table->string('route')->nullable()->index();
            $table->string('method', 10)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->string('device', 100)->nullable();
            $table->integer('status')->nullable()->index();
            $table->integer('latency_ms')->nullable()->index();
            $table->integer('request_size_bytes')->nullable();
            $table->integer('response_size_bytes')->nullable();
            $table->json('payload')->nullable(); // tanpa password
            $table->json('headers')->nullable();
            $table->timestamps();

            // Compound index for fast forensic queries
            $table->index(['route', 'ip_address', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
