<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VerifikasiTeknisi;
use App\Models\Teknisi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail; // Untuk kirim email
use App\Mail\VerifikasiDisetujui;      // Mailable Disetujui
use App\Mail\NotifikasiSkckExpired;    // Mailable SKCK Expired
use Carbon\Carbon;                     // Untuk tanggal

class AdminVerifikasiController extends Controller
{
    /**
     * ==========================================
     * 1. HALAMAN DAFTAR (LIST)
     * ==========================================
     */
    public function index(Request $request)
    {
        // 1. Ambil Query Dasar dengan Relasi User
        $query = VerifikasiTeknisi::with('teknisi.user');

        // 2. Fitur Pencarian (Search)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('account_name_verified', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhereHas('teknisi.user', function ($subQ) use ($search) {
                        $subQ->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        // 3. Filter Jumlah Entries
        $entries = $request->get('entries', 10);

        // Urutkan dari yang terbaru
        $pengajuan = $query->orderBy('created_at', 'desc')->paginate($entries);
        $pengajuan->appends($request->all());

        // 4. Data Statistik Dashboard Kecil
        $totalPengajuan = VerifikasiTeknisi::count();
        $pendingCount   = VerifikasiTeknisi::where('status', 'pending')->count();
        $todayCount     = VerifikasiTeknisi::whereDate('created_at', now())->count();
        
        // Statistik SKCK Expired (Opsional)
        $expiredSkckCount = VerifikasiTeknisi::where('status', 'disetujui')
                                ->whereDate('skck_expired', '<', now())
                                ->count();

        return view('admin.verifikasi.index', compact(
            'pengajuan',
            'entries',
            'totalPengajuan',
            'pendingCount',
            'todayCount',
            'expiredSkckCount'
        ));
    }

    /**
     * ==========================================
     * 2. HALAMAN DETAIL (SHOW)
     * ==========================================
     */
    public function show($id)
    {
        // Load relasi teknisi & user
        $data = VerifikasiTeknisi::with('teknisi.user')->findOrFail($id);
        
        // Cek status kadaluarsa SKCK untuk alert di view
        $isSkckExpired = false;
        if($data->skck_expired && Carbon::parse($data->skck_expired)->isPast()) {
            $isSkckExpired = true;
        }

        return view('admin.verifikasi.show', compact('data', 'isSkckExpired'));
    }

    /**
     * ==========================================
     * 3. PROSES EKSEKUSI (TERIMA / TOLAK)
     * ==========================================
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:terima,tolak'
        ]);

        $verifikasi = VerifikasiTeknisi::with('teknisi.user')->findOrFail($id);

        DB::beginTransaction();

        try {
            if ($request->action == 'terima') {
                // Update Status Verifikasi
                $verifikasi->status = 'disetujui';
                $verifikasi->save();

                // Update Status Teknisi jadi Aktif
                $teknisi = Teknisi::where('id_teknisi', $verifikasi->id_teknisi)->first();
                if ($teknisi) {
                    $teknisi->status = 'aktif';
                    $teknisi->save();
                }

                // === KIRIM EMAIL NOTIFIKASI DISETUJUI ===
                if ($verifikasi->teknisi && $verifikasi->teknisi->user) {
                    $emailUser = $verifikasi->teknisi->user->email;
                    $namaUser  = $verifikasi->teknisi->user->nama;
                    
                    if (!empty($emailUser)) {
                        // Pastikan Mail Config di .env sudah benar
                        try {
                            Mail::to($emailUser)->send(new VerifikasiDisetujui($namaUser));
                        } catch (\Exception $e) {
                            // Abaikan error email agar transaksi DB tidak rollback
                            // Log::error("Gagal kirim email: " . $e->getMessage());
                        }
                    }
                }
                // ========================================

                $pesan = 'Teknisi berhasil diverifikasi dan notifikasi email terkirim!';
            } else {
                // Jika Ditolak
                $verifikasi->status = 'ditolak';
                $verifikasi->save();
                $pesan = 'Pengajuan verifikasi ditolak.';
            }

            DB::commit();
            return redirect()->route('admin.verifikasi.index')->with('success', $pesan);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan server: ' . $e->getMessage());
        }
    }

    /**
     * ==========================================
     * 4. KIRIM NOTIFIKASI SKCK EXPIRED (MANUAL)
     * ==========================================
     */
    public function notifySkck($id)
    {
        try {
            $verifikasi = VerifikasiTeknisi::with('teknisi.user')->findOrFail($id);

            if (!$verifikasi->teknisi || !$verifikasi->teknisi->user) {
                return back()->with('error', 'Data user teknisi tidak ditemukan.');
            }

            $emailUser = $verifikasi->teknisi->user->email;
            $namaUser  = $verifikasi->teknisi->user->nama;
            
            // Format tanggal Indonesia
            $tglExpired = Carbon::parse($verifikasi->skck_expired)->translatedFormat('d F Y');

            // Kirim Email
            Mail::to($emailUser)->send(new NotifikasiSkckExpired($namaUser, $tglExpired));

            return back()->with('success', 'Notifikasi peringatan SKCK berhasil dikirim ke email teknisi.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }

    /**
     * ==========================================
     * 5. HAPUS DATA (DESTROY)
     * ==========================================
     */
    public function destroy($id)
    {
        $verifikasi = VerifikasiTeknisi::findOrFail($id);

        try {
            // Hapus File Fisik
            if ($verifikasi->foto_ktp && Storage::exists('public/' . $verifikasi->foto_ktp)) {
                Storage::delete('public/' . $verifikasi->foto_ktp);
            }
            if ($verifikasi->foto_skck && Storage::exists('public/' . $verifikasi->foto_skck)) {
                Storage::delete('public/' . $verifikasi->foto_skck);
            }

            $verifikasi->delete();

            return redirect()->route('admin.verifikasi.index')
                ->with('success', 'Data pengajuan berhasil dihapus permanen.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}