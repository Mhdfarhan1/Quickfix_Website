<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class KeahlianTeknisi extends Model
{
    protected $table = 'keahlian_teknisi';
    protected $guarded = [];

    public function keahlian()
    {
        return $this->belongsTo(Keahlian::class, 'id_keahlian', 'id_keahlian');
    }
}
