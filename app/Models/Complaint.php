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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id', 'id_user');
    }

    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id', 'id_pemesanan');
    }

    public function pembayaran(): BelongsTo
    {
        return $this->belongsTo(Pembayaran::class, 'pembayaran_id', 'id_pembayaran');
    }

    // ============================
    // ⭐ HELPER METHODS TAMBAHAN
    // ============================

    /**
     * Cek apakah komplain ini punya pesanan terkait.
     */
    public function hasPemesanan(): bool
    {
        return $this->pemesanan !== null;
    }

    /**
     * Ubah status pemesanan menjadi batal melalui model.
     */
    public function cancelPemesanan(): bool
    {
        if (! $this->pemesanan) {
            return false;
        }

        return $this->pemesanan->update([
            'status_pekerjaan' => 'batal'
        ]);
    }

    /**
     * Set balasan admin secara cepat.
     */
    public function setAdminResponse(?string $text = null): void
    {
        $this->update([
            'admin_id'      => auth()->id(),
            'balasan_admin' => $text ?? $this->balasan_admin,
        ]);
    }

    // ============================
    // ⭐ SCOPE (opsional)
    // ============================

    /**
     * Filter hanya komplain kategori pemesanan.
     */
    public function scopeOrderComplaint($query)
    {
        return $query->where('kategori', 'pesanan');
    }

    /**
     * Komplain yang masih status "baru".
     */
    public function scopeBaru($query)
    {
        return $query->where('status', 'baru');
    }
}
