<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * REGISTER API (hanya untuk pelanggan & teknisi)
     */
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:pelanggan,teknisi', // hanya dua role ini
            'phone' => 'nullable|string|max:30'
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => strtolower($request->email), // simpan email lowercase
            'password' => $request->password, // model akan auto-hash
            'role' => $request->role,
            'phone' => $request->phone,
        ]);

        // buat token untuk mobile
        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Registrasi berhasil!',
            'token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * LOGIN API (hanya pelanggan & teknisi)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = strtolower($request->email); // cocokkan email lowercase
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Email atau password salah.'
            ], 401);
        }

        // hanya pelanggan & teknisi
        if (!in_array($user->role, ['pelanggan', 'teknisi'])) {
            return response()->json([
                'status' => false,
                'message' => 'Akses ditolak. Hanya pelanggan atau teknisi yang bisa login di aplikasi mobile.'
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

    /**
     * PROFILE
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        if (!in_array($user->role, ['pelanggan', 'teknisi'])) {
            return response()->json([
                'status' => false,
                'message' => 'Akses ditolak. Hanya pelanggan dan teknisi yang bisa melihat profil ini.'
            ], 403);
        }

        return response()->json([
            'status' => true,
            'user' => $user,
        ]);
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout berhasil!',
        ]);
    }
}
