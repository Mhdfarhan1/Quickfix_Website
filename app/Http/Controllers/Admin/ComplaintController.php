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
                $query->where('jenis_masalah', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%")
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
        // ✅ VALIDASI (tanpa named parameter)
        $validated = $request->validate([
            'status'        => 'required|in:baru,diproses,selesai,ditolak',
            'balasan_admin' => 'nullable|string',
        ]);

        // ✅ UPDATE DATA
        $complaint->update([
            'status'        => $validated['status'],
            'balasan_admin' => $validated['balasan_admin'] ?? null,
            'admin_id'      => auth()->id(), // ini aman, warning "undefined method id" cuma dari extension, bukan error Laravel
        ]);

        // ✅ REDIRECT BALIK KE HALAMAN DETAIL
        return redirect()
            ->route('admin.complaints.show', $complaint->id)
            ->with('success', 'Komplain berhasil diperbarui.');
    }
}
