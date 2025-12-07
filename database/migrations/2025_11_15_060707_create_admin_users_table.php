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

        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_admin');
            $table->string('aksi');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_admin')
                ->references('id_admin')
                ->on('admin_users')
                ->onDelete('cascade');
        });


        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('pertanyaan');
            $table->text('jawaban');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('notifikasi_admin', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('pesan');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });




    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi_admin');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('admin_logs');
        Schema::dropIfExists('admin_users');
    }
};
