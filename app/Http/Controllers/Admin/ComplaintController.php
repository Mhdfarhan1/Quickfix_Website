<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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

                      // 🔎 Tambahan filter terkait pesanan & pembayaran
                      ->orWhere('nomor_pesanan', 'like', "%{$search}%")
                      ->orWhere('metode_pembayaran', 'like', "%{$search}%")
                      ->orWhere('nominal_id', 'like', "%{$search}%")

                      // 🔥 Tambahan baru: nomor & nama tujuan pembayaran
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
            'admin_id'      => auth()->id(),
        ]);

        return redirect()
            ->route('admin.complaints.show', $complaint->id)
            ->with('success', 'Komplain berhasil diperbarui.');
    }
}
