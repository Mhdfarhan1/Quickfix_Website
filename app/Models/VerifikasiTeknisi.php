<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifikasiTeknisi extends Model
{
    protected $table = 'verifikasi_teknisi';

    protected $fillable = [
        'id_teknisi',
        'alamat',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'nik',
        'nama',
        'rekening',
        'bank',
        'foto_ktp',
        'foto_skck',
        'buku_tabungan',
        'skck_expired',
        'status',
    ];
}
