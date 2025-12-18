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
        if (Schema::hasTable('payouts')) {
            Schema::table('payouts', function (Blueprint $table) {
                if (! Schema::hasColumn('payouts', 'idempotency_key')) {
                    $table->string('idempotency_key')->nullable()->after('reference_id');
                }
            });
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('payouts') && Schema::hasColumn('payouts', 'idempotency_key')) {
            Schema::table('payouts', function (Blueprint $table) {
                $table->dropColumn('idempotency_key');
            });
        }
    }
};
