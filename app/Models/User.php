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

    /**
     * Konversi tipe data otomatis.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Hash password otomatis sebelum disimpan.
     */
    /**
 * Hash password otomatis sebelum disimpan.
 */
    public function setPasswordAttribute($value)
    {
        // Cek apakah value sudah di-hash atau belum
        if (!empty($value) && !str_starts_with($value, '$2y$')) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }


    /**
     * Relasi ke tabel teknisi (1 user = 1 teknisi)
     */
    public function teknisi()
    {
        return $this->hasOne(Teknisi::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke tabel pelanggan (1 user = 1 pelanggan)
     */
    public function pelanggan()
    {
        return $this->hasOne(Pelanggan::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke pemesanan (jika dibutuhkan)
     */
    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_pelanggan', 'id_user');
    }
}
