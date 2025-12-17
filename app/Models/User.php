<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user';

    protected $primaryKey = 'id_user';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'no_hp',
        'foto_profile',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    // ========================
    // MUTATOR PASSWORD
    // ========================
    public function setPasswordAttribute($value)
    {
        if (!empty($value) && !str_starts_with($value, '$2y$')) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    // ========================
    // RELATIONSHIPS
    // ========================
    public function alamat()
    {
        return $this->hasMany(\App\Models\Alamat::class, 'id_user', 'id_user');
    }

    public function teknisi()
    {
        return $this->hasOne(\App\Models\Teknisi::class, 'id_user', 'id_user');
    }

    public function pemesanan()
    {
        return $this->hasMany(\App\Models\Pemesanan::class, 'id_pelanggan', 'id_user');
    }
}
