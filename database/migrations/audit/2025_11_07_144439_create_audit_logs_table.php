<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('audit')->create('audit_logs', function (Blueprint $table) {
            $table->id();

            $table->string('request_id', 100)->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable()->index();
            $table->string('route', 255)->nullable()->index();
            $table->string('method', 10)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->string('device', 100)->nullable();

            $table->integer('status')->nullable()->index();
            $table->integer('latency_ms')->nullable()->index();

            $table->integer('request_size_bytes')->nullable();
            $table->integer('response_size_bytes')->nullable();

            $table->json('payload')->nullable();
            $table->json('headers')->nullable();

            $table->timestamps();

            $table->index(['route', 'ip_address', 'created_at'], 'audit_forensic_idx');
        });

        Schema::connection('audit')->create('payout_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payout_id');
            $table->string('status');
            $table->text('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('audit')->dropIfExists('audit_logs');
        Schema::connection('audit')->dropIfExists('payout_logs');
    }
};
