<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiTeknisi extends Model
{
    use HasFactory;

    protected $table = 'lokasi_teknisi';

    protected $fillable = [
        'id_teknisi',
        'latitude',
        'longitude'
    ];
}
