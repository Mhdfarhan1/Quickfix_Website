<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $table = 'ulasan';
    protected $primaryKey = 'id_ulasan';

    protected $fillable = [
        'id_pemesanan',
        'id_pelanggan',
        'id_teknisi',
        'rating',
        'komentar'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'id_pelanggan');
    }

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'id_teknisi');
    }

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan');
    }
}
