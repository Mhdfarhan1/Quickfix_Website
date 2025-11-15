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
        Schema::create('admin_users', function (Blueprint $table) {
            $table->bigIncrements('id_admin');
            $table->string('nama', 150);
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->string('foto_profile')->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken(); // opsional tapi disarankan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_users');
    }
};
