<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * Kolom yang bisa diisi secara massal.
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'phone',
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

    /**
     * Konversi tipe data otomatis.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Enkripsi password otomatis sebelum disimpan.
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value) && substr($value, 0, 7) !== '$2y$10$') {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    /**
     * Contoh relasi (opsional, aktifkan nanti kalau tabelnya sudah ada)
     */
    // public function alamat()
    // {
    //     return $this->hasMany(AlamatPengguna::class, 'user_id');
    // }

    // public function teknisiDetail()
    // {
    //     return $this->hasOne(TeknisiDetail::class, 'user_id');
    // }
}
