<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    /**
     * Proses login admin
     */
    public function login(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cari admin berdasarkan email
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->with('error', 'Email atau password salah!');
        }

        // Cek aktif atau tidak
        if ($admin->is_active != 1) {
            return back()->with('error', 'Akun Anda tidak aktif!');
        }

        // Simpan sesi
        session([
            'admin_id'    => $admin->id_admin,
            'admin_nama'  => $admin->nama,
            'admin_email' => $admin->email,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Berhasil login!');
    }

    /**
     * Logout admin
     */
    public function logout()
    {
        session()->flush();
        return redirect()->route('admin.login')->with('success', 'Anda telah logout.');
    }
}
