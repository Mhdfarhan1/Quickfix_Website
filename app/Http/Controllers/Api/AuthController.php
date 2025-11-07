<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teknisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Alamat;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'email' => 'required|string|email|unique:user,email', // tabel di ERD
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',    
                'regex:/[0-9]/'  
            ],

            'role' => 'required|in:pelanggan,teknisi',
            'no_hp' => 'nullable|string|max:30',
        ],
        [
        'password.min' => 'Password harus minimal 8 karakter.',
        'password.regex' => 'Password harus mengandung huruf besar, angka, dan simbol unik.',
        'email.unique' => 'Email sudah digunakan. Silakan login atau gunakan email lain.',
        ]);

        // Simpan user
        $user = User::create([
            'nama' => $request->nama,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'no_hp' => $request->no_hp,
        ]);

        if ($request->role === 'teknisi') {
            Teknisi::create([
                'id_user' => $user->id_user,
                'deskripsi' => null,
                'rating_avg' => 0,
            ]);
        }

        $token = $user->createToken('mobile_token')->plainTextToken;

        if (! $user || ! Hash::check($request->password, $user->password)) {
            Log::warning('Login gagal', ['email' => $request->email, 'ip' => $request->ip()]);
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

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

        if (!$user || !Hash::check($request->password, $user->password)) {
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

        // 🔍 Ambil alamat default (khusus pelanggan)
        $alamatDefault = null;
        $idAlamatDefault = null;

        if ($user->role === 'pelanggan') {
            $alamat = Alamat::where('id_user', $user->id_user)
                ->where('is_default', 1)
                ->first();

            if ($alamat) {
                $alamatDefault = $alamat->alamat_lengkap;
                $idAlamatDefault = $alamat->id_alamat;
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil!',
            'token' => $token,
            'user' => [
                'id_user' => $user->id_user,
                'nama' => $user->nama,
                'email' => $user->email,
                'role' => $user->role,
                'no_hp' => $user->no_hp,
                'alamat_default' => $alamatDefault,
                'id_alamat_default' => $idAlamatDefault,
            ],
        ]);
    }
}
