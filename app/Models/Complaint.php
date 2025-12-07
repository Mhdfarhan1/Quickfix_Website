<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pemesanan_id',
        'pembayaran_id',
        'kategori',
        'jenis_masalah',

        'nomor_pesanan',
        'metode_pembayaran',
        'nominal_id',

        // 🔥 Tambahan baru
        'nomor_tujuan',
        'nama_tujuan',

        'deskripsi',
        'lampiran',
        'status',
        'balasan_admin',
        'admin_id',
    ];


    // ============================
    // 🔗 RELATIONS
    // ============================

    // User yang membuat komplain
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    // Admin yang menangani komplain
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id', 'id_user');
    }

    // Relasi ke pemesanan
    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id', 'id_pemesanan');
    }

    // Relasi ke pembayaran
    public function pembayaran(): BelongsTo
    {
        return $this->belongsTo(Pembayaran::class, 'pembayaran_id', 'id_pembayaran');
    }
}
