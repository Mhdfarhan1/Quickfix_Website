<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admin_users';
    protected $primaryKey = 'id_admin';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'foto_profile',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Pastikan timestamps aktif
    public $timestamps = true;
}
