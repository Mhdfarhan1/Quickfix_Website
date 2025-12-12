<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    // LIST KOMPLAIN
    public function index(Request $request): View
    {
        // jumlah data per halaman (default 10)
        $perPage = (int) $request->input('entries', 10);
        if (! in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        // keyword pencarian
        $search = $request->input('search');

        $complaints = Complaint::with('user')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('jenis_masalah', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%")
                        ->orWhere('kategori', 'like', "%{$search}%")

                        // Tambahan filter terkait pesanan & pembayaran
                        ->orWhere('nomor_pesanan', 'like', "%{$search}%")
                        ->orWhere('metode_pembayaran', 'like', "%{$search}%")
                        ->orWhere('nominal_id', 'like', "%{$search}%")

                        // Tambahan baru: nomor & nama tujuan pembayaran
                        ->orWhere('nomor_tujuan', 'like', "%{$search}%")
                        ->orWhere('nama_tujuan', 'like', "%{$search}%");
                })
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate($perPage)
            ->appends([
                'search'  => $search,
                'entries' => $perPage,
            ]);

        return view('admin.complaints.index', compact('complaints', 'perPage', 'search'));
    }

    // DETAIL KOMPLAIN
    public function show(Complaint $complaint): View
    {
        $complaint->load(['user', 'pemesanan', 'pembayaran', 'admin']);

        return view('admin.complaints.show', compact('complaint'));
    }

    // UPDATE STATUS + BALASAN ADMIN
    public function update(Request $request, Complaint $complaint): RedirectResponse
    {
        $validated = $request->validate([
            'status'        => 'required|in:baru,diproses,selesai,ditolak',
            'balasan_admin' => 'nullable|string',
        ]);

        $complaint->update([
            'status'        => $validated['status'],
            'balasan_admin' => $validated['balasan_admin'] ?? null,
            'admin_id'      => Auth::id(),
        ]);

        return redirect()
            ->route('admin.complaints.show', $complaint->id)
            ->with('success', 'Komplain berhasil diperbarui.');
    }

    /**
     * Cancel the related pemesanan (set status_pekerjaan = 'batal')
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Complaint     $complaint
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Request $request, Complaint $complaint): RedirectResponse
    {
        // Pastikan ada pemesanan terkait
        if (! $complaint->pemesanan) {
            return back()->with('error', 'Data pemesanan tidak ditemukan untuk laporan ini.');
        }

        DB::transaction(function () use ($complaint) {
            $pemesanan = $complaint->pemesanan;

            // update status pekerjaan jadi 'batal'
            $pemesanan->update([
                'status_pekerjaan' => 'batal',
            ]);

            // opsional: catat admin yang melakukan cancel pada complaint
            $complaint->update([
                'admin_id' => Auth::id(),
                'balasan_admin' => $complaint->balasan_admin ?? 'Pesanan dibatalkan oleh admin #' . Auth::id(),
            ]);
        });

        return back()->with('success', 'Pesanan berhasil dibatalkan (status: batal).');
    }

    /**
     * Refund action: jika admin pilih Refund, kita ubah status pemesanan menjadi 'batal'
     * (sesuai permintaan: refund akan membuat status pesanan jadi 'batal').
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Complaint     $complaint
     * @return \Illuminate\Http\RedirectResponse
     */
    public function refund(Request $request, Complaint $complaint): RedirectResponse
    {
        // Jika ingin menyimpan alasan refund, validasi di sini
        $validated = $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        // Pastikan complaint terkait kategori 'pesanan' (opsional)
        if ($complaint->kategori !== 'pesanan') {
            return back()->with('error', 'Refund hanya dapat diajukan untuk kategori pemesanan.');
        }

        if (! $complaint->pemesanan) {
            return back()->with('error', 'Data pemesanan tidak ditemukan untuk laporan ini.');
        }

        DB::transaction(function () use ($complaint, $validated) {
            // update pemesanan jadi 'batal'
            $pemesanan = $complaint->pemesanan;
            $pemesanan->update([
                'status_pekerjaan' => 'batal',
            ]);

            // opsi: simpan info refund pada complaint (jika kolom refund_status ada)
            if (Schema::hasColumn('complaints', 'refund_status')) {
                $complaint->update([
                    'refund_status' => 'requested',
                    'admin_id'      => Auth::id(),
                    'balasan_admin' => $validated['reason'] ?? $complaint->balasan_admin,
                ]);
            } else {
                // tanpa kolom refund_status, kita tetap catat admin & alasan
                $complaint->update([
                    'admin_id'      => Auth::id(),
                    'balasan_admin' => $validated['reason'] ?? $complaint->balasan_admin ?? 'Refund diproses oleh admin #' . Auth::id(),
                ]);
            }
        });

        return redirect()
            ->route('admin.complaints.show', $complaint->id)
            ->with('success', 'Refund diproses: status pesanan diperbarui menjadi batal.');
    }
}
