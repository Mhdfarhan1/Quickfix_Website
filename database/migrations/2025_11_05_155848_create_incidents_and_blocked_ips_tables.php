<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->text('description')->nullable();
            $table->string('ip')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->json('metadata')->nullable();
            $table->string('status')->default('open');
            $table->timestamps();
        });

        Schema::create('blocked_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip')->unique();
            $table->timestamp('blocked_until')->nullable();
            $table->string('reason')->nullable();
            $table->timestamps();
        });

        // tambahkan kolom is_blocked ke user
        Schema::table('user', function (Blueprint $table) {
            if (!Schema::hasColumn('user', 'is_blocked')) {
                $table->boolean('is_blocked')->default(false);
            }
        });
    }

    public function down()
    {
        Schema::dropIfExists('incidents');
        Schema::dropIfExists('blocked_ips');
        Schema::table('user', function (Blueprint $table) {
            if (Schema::hasColumn('user', 'is_blocked')) {
                $table->dropColumn('is_blocked');
            }
        });
    }
};
