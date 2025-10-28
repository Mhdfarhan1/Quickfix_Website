<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teknisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'email' => 'required|string|email|unique:user,email', // tabel di ERD
            'password' => 'required|string|min:6',
            'role' => 'required|in:pelanggan,teknisi',
            'no_hp' => 'nullable|string|max:30',
        ]);

        // Simpan user
        $user = User::create([
            'nama' => $request->nama,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'no_hp' => $request->no_hp,
        ]);

        // Jika role teknisi, buat record di tabel teknisi
        if ($request->role === 'teknisi') {
            Teknisi::create([
                'id_user' => $user->id_user,
                'deskripsi' => null,
                'rating_avg' => 0,
            ]);
        }

        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Registrasi berhasil!',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', strtolower($request->email))->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Email atau password salah.',
            ], 401);
        }

        if (!in_array($user->role, ['pelanggan', 'teknisi'])) {
            return response()->json([
                'status' => false,
                'message' => 'Akses ditolak. Role tidak valid untuk aplikasi mobile.',
            ], 403);
        }

        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil!',
            'token' => $token,
            'user' => $user,
        ]);
    }
}
