<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    // nama tabel di database
    protected $table = 'pembayaran';

    // primary key di tabel
    protected $primaryKey = 'id_pembayaran';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_pemesanan',
        'metode',
        'jumlah',
        'status',
        'bukti_transfer',
    ];

    public function pemesanan()
    {
        // sesuaikan kalau PK di tabel pemesanan = id_pemesanan
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function complaints()
    {
        // relasi balik ke komplain
        return $this->hasMany(Complaint::class, 'pembayaran_id', 'id_pembayaran');
    }
}
