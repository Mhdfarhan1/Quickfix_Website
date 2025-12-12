<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('payouts', function (Blueprint $table) {
            $table->string('idempotency_key')->nullable()->after('reference_id')->index();
        });
    }

    public function down()
    {
        Schema::table('payouts', function (Blueprint $table) {
            $table->dropColumn('idempotency_key');
        });
    }

};
