<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teknisi;
use Illuminate\Http\Request;

class TeknisiController extends Controller
{
    // Tampilkan semua teknisi (dengan search + entries + pagination)
    public function index(Request $request)
    {
        $search = $request->search;
        $entries = $request->entries ?? 10; // default 10 item per halaman

        $teknisis = Teknisi::with('user')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('nama', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('no_hp', 'like', "%$search%");
                });
            })
            ->paginate($entries)
            ->appends(['search' => $search, 'entries' => $entries]);

        return view('admin.teknisi.index', compact('teknisis', 'search', 'entries'));
    }

    // Verifikasi teknisi
    public function verify($id)
    {
        $teknisi = Teknisi::findOrFail($id);
        $teknisi->is_verified = 1;
        $teknisi->save();

        return redirect()->route('admin.teknisi.index')->with('success', 'Teknisi berhasil diverifikasi.');
    }

    // Hapus akun teknisi
    public function destroy($id)
    {
        $teknisi = Teknisi::findOrFail($id);

        // Hapus user terkait
        $user = $teknisi->user;
        if ($user) {
            $user->delete();
        }

        // Hapus data teknisi
        $teknisi->delete();

        return redirect()->route('admin.teknisi.index')->with('success', 'Akun teknisi berhasil dihapus.');
    }

    // Preview dokumen teknisi (untuk modal)
    public function preview($id)
    {
        $teknisi = Teknisi::findOrFail($id);

        if (!$teknisi->document_path || !file_exists(storage_path("app/" . $teknisi->document_path))) {
            return response()->json(['error' => 'Dokumen tidak ditemukan.'], 404);
        }

        // URL publik untuk modal
        $url = asset('storage/' . $teknisi->document_path);

        return response()->json(['url' => $url]);
    }
}
