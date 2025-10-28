<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiPekerjaan extends Model
{
    use HasFactory;

    protected $table = 'bukti_pekerjaan';
    protected $primaryKey = 'id_bukti';

    protected $fillable = [
        'id_pemesanan',
        'id_teknisi',
        'id_keahlian',
        'deskripsi',
        'foto_bukti',
    ];

    public function teknisi()
    {
        return $this->belongsTo(Teknisi::class, 'id_teknisi');
    }

    public function keahlian()
    {
        return $this->belongsTo(Keahlian::class, 'id_keahlian');
    }

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan');
    }
}
