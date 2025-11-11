<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    protected $table = 'keranjang';
    protected $primaryKey = 'id_keranjang';

    protected $fillable = [
        'id_pelanggan',
        'id_teknisi',
        'id_keahlian',
        'harga',
        'catatan',
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
}
