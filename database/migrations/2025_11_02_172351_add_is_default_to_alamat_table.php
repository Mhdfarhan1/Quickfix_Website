<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::table('alamat', function (Blueprint $table) {
            $table->unique(['id_user', 'is_default'], 'unique_default_per_user');
        });

    }
    public function down() {
        Schema::table('alamat', function (Blueprint $table) {
            $table->dropColumn('is_default');
        });
    }

};
