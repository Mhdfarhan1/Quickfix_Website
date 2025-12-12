<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifikasiTeknisi extends Model
{
    protected $table = 'verifikasi_teknisi';

    protected $fillable = [
        'id_teknisi',
        
        // Identitas
        'nik',
        'nama',

        // Rekening
        'rekening',
        'bank',

        // Wilayah
        'provinsi',
        'kabupaten',
        'kecamatan',

        // File upload
        'foto_ktp',
        'foto_skck',
        'buku_tabungan',

        // Status SKCK (baru)
        'skck_status',

        // Status verifikasi
        'status',
    ];
}
