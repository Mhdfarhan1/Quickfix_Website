<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\Notify; // <-- pastikan ada

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';
    protected $primaryKey = 'id_pemesanan';
    public $timestamps = true;

    protected $fillable = [
        'kode_pemesanan',
        'id_pelanggan',
        'id_teknisi',
        'id_keahlian',
        'id_alamat',
        'tanggal_booking',
        'jam_booking',
        'keluhan',
        'harga',
        'gross_amount',
        'status_pekerjaan',
        'payment_status',
        'payment_type',
        'midtrans_transaction_id',
        'payment_url',
        'snap_token',
        // kolom escrow/verifikasi
        'verifikasi_by_customer',
        'verifikasi_at',
        'payout_eligible_at',
        'payout_released_at',
        'dispute_id',
        'refund_requested_at',
        'visible_bukti_teknisi',
    ];

    protected $casts = [
        'verifikasi_by_customer' => 'boolean',
        'verifikasi_at' => 'datetime',
        'payout_eligible_at' => 'datetime',
        'payout_released_at' => 'datetime',
        'refund_requested_at' => 'datetime',
        'visible_bukti_teknisi' => 'array',
        'harga' => 'decimal:2',
        'gross_amount' => 'decimal:2',
    ];

    // relations (sama seperti yang sudah ada)
    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'id_pelanggan', 'id_user');
    }

    public function teknisi()
    {
        return $this->belongsTo(Teknisi::class, 'id_teknisi', 'id_teknisi');
    }

    public function keahlian()
    {
        return $this->belongsTo(Keahlian::class, 'id_keahlian', 'id_keahlian');
    }

    public function alamat()
    {
        return $this->belongsTo(Alamat::class, 'id_alamat', 'id_alamat');
    }

    public function buktiPekerjaan()
    {
        return $this->hasMany(BuktiPekerjaan::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function payout()
    {
        return $this->hasOne(Payout::class, 'id_pemesanan', 'id_pemesanan');
    }


    public function dispute()
    {
        return $this->hasOne(Dispute::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function updateStatus($newStatus)
    {
        $this->status_pekerjaan = $newStatus;
        $this->save();

        // gunakan method Notify yang ada: statusChanged or send
        Notify::statusChanged($this->id_pelanggan, $newStatus);
    }
}
