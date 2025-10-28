<?php

// app/Models/Pemesanan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';
    protected $primaryKey = 'id_pemesanan';
    public $timestamps = false;

    protected $fillable = [
        'kode_pemesanan',
        'id_pelanggan',
        'id_teknisi',
        'id_keahlian',
        'tanggal_booking',
        'keluhan',
        'status',
        'harga'
    ];


    /**
     * Relasi ke pelanggan (setiap pemesanan dimiliki oleh 1 pelanggan)
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    /**
     * Relasi ke teknisi (setiap pemesanan ditangani oleh 1 teknisi)
     */
    public function teknisi()
    {
        return $this->belongsTo(Teknisi::class, 'id_teknisi', 'id_teknisi');
    }

    /**
     * Relasi ke keahlian
     */
    public function keahlian()
    {
        return $this->belongsTo(Keahlian::class, 'id_keahlian', 'id_keahlian');
    }
}
