<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teknisi;
use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Failed;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // 🔒 Fungsi untuk cek apakah IP/email sedang dikunci
    protected function checkLock(string $email, string $ip): array
    {
        $keyIpLockUntil = "login_lock_until:ip:{$ip}";
        $keyEmailLockUntil = "login_lock_until:email:{$email}";

        $ipLockedUntil = Cache::get($keyIpLockUntil);
        $emailLockedUntil = Cache::get($keyEmailLockUntil);

        $lockedUntil = null;
        if ($ipLockedUntil) $lockedUntil = Carbon::parse($ipLockedUntil);
        if ($emailLockedUntil && Carbon::parse($emailLockedUntil)->gt($lockedUntil ?? Carbon::minValue())) {
            $lockedUntil = Carbon::parse($emailLockedUntil);
        }

        if ($lockedUntil && $lockedUntil->isFuture()) {
            return [
                'locked' => true,
                'until' => $lockedUntil,
                'seconds_left' => $lockedUntil->diffInSeconds(now()),
            ];
        }

        return ['locked' => false];
    }

    // 🧩 REGISTER USER BARU
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'email' => 'required|string|email|unique:user,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/'
            ],
            'role' => 'required|in:pelanggan,teknisi',
            'no_hp' => 'nullable|string|max:30',
        ], [
            'password.min' => 'Password harus minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf besar dan angka.',
            'email.unique' => 'Email sudah digunakan. Silakan login atau gunakan email lain.',
        ]);

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

        return response()->json([
            'status' => true,
            'message' => 'Registrasi berhasil!',
            'token' => $token,
            'user' => $user,
        ]);
    }

    // 🔑 LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // pastikan variabel email dan ip ada sebelum dipakai
        $email = strtolower($request->email);
        $ip = $request->ip();

        // 1️⃣ cek apakah sedang dikunci
        $lockStatus = $this->checkLock($email, $ip);
        if ($lockStatus['locked']) {
            $minutes = ceil($lockStatus['seconds_left'] / 60);
            return response()->json([
                'status' => false,
                'message' => "Terlalu banyak percobaan login gagal. Coba lagi dalam {$minutes} menit.",
                'locked_until' => $lockStatus['until']->toDateTimeString(),
            ], 423);
        }

        // 2️⃣ cari user dan verifikasi password
        $user = User::where('email', $email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            // kirim event agar listener HandleFailedLogin bekerja
            Event::dispatch(new Failed('web', $user, ['email' => $email]));

            return response()->json([
                'status' => false,
                'message' => 'Email atau password salah.',
            ], 401);
        }

        // 3️⃣ cek role user
        if (!in_array($user->role, ['pelanggan', 'teknisi'])) {
            return response()->json([
                'status' => false,
                'message' => 'Akses ditolak. Role tidak valid untuk aplikasi mobile.',
            ], 403);
        }

        // 4️⃣ login sukses -> hapus semua counter & lock
        Cache::forget("login_failed_count:ip:{$ip}");
        Cache::forget("login_failed_count:email:{$email}");
        Cache::forget("login_lock_until:ip:{$ip}");
        Cache::forget("login_lock_until:email:{$email}");
        Cache::forget("login_lock_rounds:ip:{$ip}");
        Cache::forget("login_lock_rounds:email:{$email}");

        // 5️⃣ buat token
        $token = $user->createToken('mobile_token')->plainTextToken;

        // 6️⃣ ambil alamat default (khusus pelanggan)
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
