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
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_alamat')->nullable()->after('id_keahlian');
            $table->foreign('id_alamat')->references('id_alamat')->on('alamat')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->dropForeign(['id_alamat']);
            $table->dropColumn('id_alamat');
        });
    }

};
