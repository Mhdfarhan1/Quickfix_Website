<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keahlian extends Model
{
    use HasFactory;

    protected $table = 'keahlian';
    protected $primaryKey = 'id_keahlian';
    public $timestamps = false;

    protected $fillable = ['nama_keahlian', 'deskripsi'];

    public function teknisi()
    {
        return $this->hasMany(Teknisi::class, 'id_keahlian', 'id_keahlian');
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_keahlian', 'id_keahlian');
    }
}
