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
        'id_alamat',
        'tanggal_booking',
        'keluhan',
        'harga',
        'gross_amount',
        'status',
        'payment_status',
        'payment_type',
        'midtrans_transaction_id',
        'payment_url',
        'snap_token',
    ];




    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'id_pelanggan', 'id_user');
    }

    public function teknisi()
    {
        return $this->belongsTo(Teknisi::class, 'id_teknisi', 'id_teknisi');
    }

    public function keahlian()
    {
        return $this->belongsTo(Keahlian::class, 'id_keahlian', 'id_keahlian');
    }

    public function alamat()
    {
        return $this->belongsTo(Alamat::class, 'id_alamat', 'id_alamat');
    }

    public function buktiPekerjaan()
    {
        return $this->hasMany(BuktiPekerjaan::class, 'id_pemesanan', 'id_pemesanan');
    }


}
