<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->enum('refund_status', [
                'none',        // belum ada refund
                'requested',   // user/admin meminta refund
                'approved',    // refund disetujui
                'processed',   // refund sudah dikembalikan
                'rejected'     // refund ditolak
            ])->default('none')->after('status'); 
            // sesuaikan `after('status')` dengan kolom terakhir yang kamu punya
        });
    }

    public function down()
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn('refund_status');
        });
    }
};
