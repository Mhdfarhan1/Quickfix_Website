<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';
    protected $primaryKey = 'id_pemesanan';
    public $timestamps = true; // ✅ aktifkan agar created_at & updated_at otomatis terisi

    protected $fillable = [
        'kode_pemesanan',
        'id_pelanggan',
        'id_teknisi',
        'id_keahlian',
        'id_alamat',
        'tanggal_booking',
        'jam_booking',              // ✅ tambahkan karena ada di tabel
        'keluhan',
        'harga',
        'gross_amount',
        'status_pekerjaan',
        'payment_status',
        'payment_type',
        'midtrans_transaction_id',
        'payment_url',
        'snap_token',
    ];

    /**
     * 🔹 Relasi ke tabel pengguna (pelanggan)
     * Pastikan nama model User benar-benar menunjuk ke tabel 'users' atau 'pelanggan'
     */
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

    public function updateStatus($newStatus)
    {
        $this->status_pekerjaan = $newStatus;
        $this->save();

        Notify::statusChanged($this->id_pelanggan, $newStatus);
    }

}
