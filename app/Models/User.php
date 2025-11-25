<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Nama tabel sesuai ERD.
     */
    protected $table = 'user';

    protected $connection = 'mysql'; // <--- tambahkan ini

    /**
     * Primary key sesuai ERD.
     */
    protected $primaryKey = 'id_user';

    /**
     * Kolom yang dapat diisi secara massal.
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'no_hp',
        'foto_profile',
        'is_active',
    ];

    /**
     * Kolom yang disembunyikan ketika dikonversi ke array/JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

  
    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    
    public function setPasswordAttribute($value)
    {
        if (!empty($value) && !str_starts_with($value, '$2y$')) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    public function alamat()
    {
        return $this->hasMany(\App\Models\Alamat::class, 'id_user', 'id_user');
    }

    public function teknisi()
    {
        return $this->hasOne(Teknisi::class, 'id_user', 'id_user');
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_pelanggan', 'id_user');
    }
}
