<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Tampilkan semua pelanggan
    public function index()
    {
        $users = User::where('role', 'pelanggan')->get(); // hanya pelanggan
        return view('admin.user.index', compact('users'));
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
