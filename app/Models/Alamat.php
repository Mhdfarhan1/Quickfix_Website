<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    use HasFactory;

    protected $table = 'alamat';
    protected $primaryKey = 'id_alamat';

    protected $fillable = [
        'id_user',
        'label',
        'alamat_lengkap',
        'kota',
        'provinsi',
        'latitude',
        'longitude',
        'is_default',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public static function getDefaultForUser($userId)
    {
        return self::where('id_user', $userId)->where('is_default', true)->first();
    }

}
