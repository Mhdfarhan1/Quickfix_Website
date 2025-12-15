<?php

namespace App\Models;
use App\Models\Teknisi;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    protected $table = 'payouts';

    protected $fillable = [
        'id_pemesanan',
        'id_teknisi',
        'status',
        'reference_id',
        'bank_code',
        'account_number', // <--- pakai ini (sama seperti migration)
        'account_name',
        'amount',
        'idempotency_key',
        'flip_id',
        'raw_response',
    ];


    // relasi ke order
    public function order()
    {
        return $this->belongsTo(\App\Models\Pemesanan::class, 'id_pemesanan');
    }

    public function teknisi()
    {
        return $this->belongsTo(Teknisi::class, 'id_teknisi', 'id_teknisi');
    }

    // accessor alias jika kamu menggunakan property lain di controller
    public function getRekeningAttribute()
    {
        return $this->account_number;
    }


    public function getBankAttribute()
    {
        return $this->bank_code;
    }

    public function getAmountTeknisiAttribute()
    {
        return $this->amount;
    }
}
