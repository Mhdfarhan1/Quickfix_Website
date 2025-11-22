<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teknisi;
use App\Models\User;
use Illuminate\Http\Request;

class TeknisiController extends Controller
{
    // Tampilkan semua teknisi
    public function index()
    {
        $teknisis = Teknisi::with('user')->get(); // ambil data user terkait teknisi
        return view('admin.teknisi.index', compact('teknisis'));
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

        // Hapus user terkait agar tidak bisa login lagi
        $user = $teknisi->user;
        if($user) {
            $user->delete();
        }

        // Hapus data teknisi
        $teknisi->delete();

        return redirect()->route('admin.teknisi.index')->with('success', 'Akun teknisi berhasil dihapus.');
    }
}
