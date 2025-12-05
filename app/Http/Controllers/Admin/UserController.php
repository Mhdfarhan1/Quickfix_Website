<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Tampilkan semua pelanggan + search + entries + pagination
    public function index(Request $request)
    {
        // Jumlah data per halaman (entries)
        $entries = $request->input('entries', 10); // default 10

        // Keyword pencarian
        $search = $request->input('search');

        $query = User::where('role', 'pelanggan'); // hanya pelanggan

        // Jika ada keyword search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('no_hp', 'like', '%' . $search . '%');
            });
        }

        // Pagination + bawa query string (entries, search) ke link berikutnya
        $users = $query->orderBy('nama')
            ->paginate($entries)
            ->appends($request->except('page'));

        return view('admin.user.index', compact('users', 'entries', 'search'));
    }

    // Hapus akun pelanggan
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role !== 'pelanggan') {
            return redirect()->route('admin.user.index')->with('error', 'Role tidak valid.');
        }

        $user->delete();

        return redirect()->route('admin.user.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}
