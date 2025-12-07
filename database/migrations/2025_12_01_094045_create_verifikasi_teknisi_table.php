<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * UP → Menambah kolom jika belum ada
     */
    public function up()
    {
        Schema::table('verifikasi_teknisi', function (Blueprint $table) {

            if (!Schema::hasColumn('verifikasi_teknisi', 'nik'))
                $table->string('nik')->nullable();

            if (!Schema::hasColumn('verifikasi_teknisi', 'nama'))
                $table->string('nama')->nullable();

            if (!Schema::hasColumn('verifikasi_teknisi', 'rekening'))
                $table->string('rekening')->nullable();

            if (!Schema::hasColumn('verifikasi_teknisi', 'bank'))
                $table->string('bank')->nullable();

            if (!Schema::hasColumn('verifikasi_teknisi', 'provinsi'))
                $table->string('provinsi')->nullable();

            if (!Schema::hasColumn('verifikasi_teknisi', 'kabupaten'))
                $table->string('kabupaten')->nullable();

            if (!Schema::hasColumn('verifikasi_teknisi', 'kecamatan'))
                $table->string('kecamatan')->nullable();

            if (!Schema::hasColumn('verifikasi_teknisi', 'foto_ktp'))
                $table->string('foto_ktp')->nullable();

            if (!Schema::hasColumn('verifikasi_teknisi', 'foto_skck'))
                $table->string('foto_skck')->nullable();

            if (!Schema::hasColumn('verifikasi_teknisi', 'buku_tabungan'))
                $table->string('buku_tabungan')->nullable();

            if (!Schema::hasColumn('verifikasi_teknisi', 'skck_expired'))
                $table->date('skck_expired')->nullable();

            if (!Schema::hasColumn('verifikasi_teknisi', 'status'))
                $table->string('status')->default('pending');
        });
    }

    /**
     * DOWN → Menghapus kolom jika ada
     */
    public function down()
    {
        Schema::table('verifikasi_teknisi', function (Blueprint $table) {

            if (Schema::hasColumn('verifikasi_teknisi', 'nik')) $table->dropColumn('nik');
            if (Schema::hasColumn('verifikasi_teknisi', 'nama')) $table->dropColumn('nama');
            if (Schema::hasColumn('verifikasi_teknisi', 'rekening')) $table->dropColumn('rekening');
            if (Schema::hasColumn('verifikasi_teknisi', 'bank')) $table->dropColumn('bank');
            if (Schema::hasColumn('verifikasi_teknisi', 'provinsi')) $table->dropColumn('provinsi');
            if (Schema::hasColumn('verifikasi_teknisi', 'kabupaten')) $table->dropColumn('kabupaten');
            if (Schema::hasColumn('verifikasi_teknisi', 'kecamatan')) $table->dropColumn('kecamatan');
            if (Schema::hasColumn('verifikasi_teknisi', 'foto_ktp')) $table->dropColumn('foto_ktp');
            if (Schema::hasColumn('verifikasi_teknisi', 'foto_skck')) $table->dropColumn('foto_skck');
            if (Schema::hasColumn('verifikasi_teknisi', 'buku_tabungan')) $table->dropColumn('buku_tabungan');
            if (Schema::hasColumn('verifikasi_teknisi', 'skck_expired')) $table->dropColumn('skck_expired');
            if (Schema::hasColumn('verifikasi_teknisi', 'status')) $table->dropColumn('status');
        });
    }
};
