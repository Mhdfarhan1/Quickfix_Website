<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        // incidents (di database utama)
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->text('description')->nullable();
            $table->string('ip', 45)->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->json('metadata')->nullable();
            $table->string('status', 50)->default('open')->index();
            $table->timestamps();

            $table->index(['ip', 'type', 'created_at'], 'incident_forensic_idx');
        });



        // blocked_ips (di database utama)
        Schema::create('blocked_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip', 45)->unique();
            $table->timestamp('blocked_until')->nullable()->index();
            $table->string('reason')->nullable();
            $table->timestamps();
        });



        // tambah is_blocked di DATABASE UTAMA (bukan audit)
        
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
