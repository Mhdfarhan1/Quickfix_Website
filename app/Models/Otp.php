<?php
// app/Models/Otp.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Otp extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'email',
        'otp',
        'payload',
        'expires_at',
        'is_used',
    ];

    protected $casts = [
        'payload' => 'array',
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at->lt(now());
    }
}
