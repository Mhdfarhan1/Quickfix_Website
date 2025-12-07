<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teknisi extends Model
{
    protected $table = 'teknisi';
    protected $primaryKey = 'id_teknisi';
    protected $fillable = [
        'id_user', // ✅ ubah ini
        'pengalaman',
        'sertifikat',
        'rating_avg', // ✅ pastikan sama dengan kolom di DB
        'status'
    ];

    public $timestamps = false;

    public function user()
    {
        // ✅ ubah relasi ke id_user
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function verifikasi()
    {
        return $this->hasOne(VerifikasiTeknisi::class, 'id_teknisi', 'id_teknisi');
    }


    public function keahlian()
    {
        return $this->belongsToMany(Keahlian::class, 'teknisi_keahlian', 'id_teknisi', 'id_keahlian');
    }
}
