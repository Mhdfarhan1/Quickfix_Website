<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifikasiTeknisi extends Model
{
    use HasFactory;

    protected $table = 'verifikasi_teknisi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_teknisi',
        
        // Identitas
        'nik',
        'nama',
        'alamat',      // Dari blok bawah
        
        // Keuangan
        'rekening',
        'bank',

        // Wilayah
        'provinsi',
        'kabupaten',
        'kecamatan',

        // Dokumen
        'foto_ktp',
        'foto_skck',
        'buku_tabungan',

        // Status SKCK (Saya masukkan keduanya agar aman)
        'skck_status',  // Dari blok atas
        'skck_expired', // Dari blok bawah

        // Status Verifikasi
        'status',
    ];

    protected $casts = [
        'skck_expired' => 'date',
    ];

    /**
     * Relasi: Verifikasi dimiliki oleh satu teknisi
     */
    public function teknisi()
    {
        // Pastikan model Teknisi ada di namespace yang sama atau import jika beda
        return $this->belongsTo(Teknisi::class, 'id_teknisi', 'id_teknisi');
    }
}