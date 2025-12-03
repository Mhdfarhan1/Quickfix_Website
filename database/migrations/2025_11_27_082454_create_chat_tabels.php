<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id('id_chat');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_teknisi');
            $table->text('last_message')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            $table->unique(['id_user', 'id_teknisi']);

            $table->foreign('id_user')
                ->references('id_user')->on('user')
                ->onDelete('cascade');

            $table->foreign('id_teknisi')
                ->references('id_teknisi')->on('teknisi')
                ->onDelete('cascade');
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id('id_message');
            $table->unsignedBigInteger('id_chat');

            $table->unsignedBigInteger('sender_user_id')->nullable();
            $table->unsignedBigInteger('sender_teknisi_id')->nullable();

            $table->text('message')->nullable();
            
            $table->enum('type', ['text', 'image', 'video', 'file'])
                ->default('text');

            $table->boolean('is_read')->default(false);

            $table->enum('type', ['text','image','file','video'])->default('text')->change();

            $table->timestamps();

            $table->foreign('id_chat')
                ->references('id_chat')->on('chats')
                ->onDelete('cascade');
        });


        Schema::create('message_attachments', function (Blueprint $table) {
            $table->id('id_attachment');
            $table->unsignedBigInteger('id_message');

            $table->string('filename')->nullable();
            $table->string('path');
            $table->string('thumbnail')->nullable(); // TANPA after
            $table->string('url')->nullable();
            $table->string('mime')->nullable();
            $table->enum('type', ['image', 'video', 'file'])->default('file');
            $table->bigInteger('size')->nullable();
            $table->timestamps();

            $table->foreign('id_message')
                ->references('id_message')
                ->on('messages')
                ->onDelete('cascade');
        });


    }

    public function down(): void
    {
        Schema::dropIfExists('message_attachments');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('chats');
    }
};
