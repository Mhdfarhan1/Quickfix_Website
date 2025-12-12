<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VerifikasiTeknisi extends Model
{
    use HasFactory;

    protected $table = 'verifikasi_teknisi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_teknisi',
        'nik',
        'nama',
        'alamat',
        'rekening',
        'bank',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'foto_ktp',
        'foto_skck',
        'buku_tabungan',
        'skck_expired',
        'status'
    ];

    protected $casts = [
        'skck_expired' => 'date',
    ];

    /**
     * Relasi: Verifikasi dimiliki oleh satu teknisi
     */
    public function teknisi()
    {
        return $this->belongsTo(Teknisi::class, 'id_teknisi', 'id_teknisi');
    }
}
