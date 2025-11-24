<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Otp;
use App\Models\User;
use App\Models\Teknisi;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class AuthOtpController extends Controller
{
    protected $otpExpiryMinutes = 5;

    // 1) Request OTP (simpan payload, jangan buat user)
    public function registerRequest(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'email' => ['required','string','email','max:255', Rule::unique('user','email')],
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

        $email = strtolower($request->email);

        // generate 6-digit secure OTP
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // prepare payload: simpan hashed password agar aman
        $payload = [
            'nama' => $request->nama,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'no_hp' => $request->no_hp,
        ];

        // if there's an existing unused otp for this email, mark it used or delete -> here we'll mark old used
        Otp::where('email', $email)->where('is_used', false)->update(['is_used' => true]);

        $otp = Otp::create([
            'email' => $email,
            'otp' => $otpCode,
            'payload' => $payload,
            'expires_at' => Carbon::now()->addMinutes($this->otpExpiryMinutes),
            'is_used' => false,
        ]);

        // send email (synchronously here; consider queueing for production)
        Mail::to($email)->send(new OtpMail($otpCode, $this->otpExpiryMinutes, config('app.name', 'QuickFix App')));

        return response()->json([
            'status' => true,
            'message' => 'OTP telah dikirim ke email kamu. Kode berlaku '.$this->otpExpiryMinutes.' menit.',
            'email' => $email,
        ]);
    }

    // 2) Verify OTP and create user
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        $email = strtolower($request->email);
        $otp = $request->otp;

        $otpRow = Otp::where('email', $email)
                    ->where('otp', $otp)
                    ->where('is_used', false)
                    ->orderBy('created_at', 'desc')
                    ->first();

        if (!$otpRow) {
            return response()->json([
                'status' => false,
                'message' => 'Kode OTP tidak valid atau sudah digunakan.'
            ], 422);
        }

        if ($otpRow->isExpired()) {
            // mark used to prevent reuse
            $otpRow->update(['is_used' => true]);
            return response()->json([
                'status' => false,
                'message' => 'Kode OTP sudah kadaluarsa. Silakan minta kode baru.'
            ], 422);
        }

        // create user from payload
        $payload = $otpRow->payload;

        // Double-check email uniqueness again (race condition)
        if (User::where('email', $email)->exists()) {
            // mark otp used anyway
            $otpRow->update(['is_used' => true]);
            return response()->json([
                'status' => false,
                'message' => 'Email sudah terdaftar. Silakan login.'
            ], 422);
        }

        $user = User::create([
            'nama' => $payload['nama'] ?? null,
            'email' => $email,
            'password' => $payload['password'] ?? Hash::make(Str::random(12)),
            'role' => $payload['role'] ?? 'pelanggan',
            'no_hp' => $payload['no_hp'] ?? null,
            'email_verified_at' => now(), // 🔥 FIX PENTING
        ]);


        // if teknisi role, create teknisi row
        if (($payload['role'] ?? null) === 'teknisi') {
            Teknisi::create([
                'id_user' => $user->id_user ?? $user->id,
                'deskripsi' => null,
                'rating_avg' => 0,
            ]);
        }

        // mark otp used
        $otpRow->update(['is_used' => true]);

        // create token
        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Registrasi berhasil. Akun sudah diverifikasi.',
            'token' => $token,
            'user' => $user,
        ]);
    }   

    // 3) Resend OTP
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = strtolower($request->email);

        // check that there is a pending registration payload for this email? optionally you could accept resend even if no payload
        // find latest used/unused OTP for that email and payload; we'll create a new one and mark old unused as used
        Otp::where('email', $email)->where('is_used', false)->update(['is_used' => true]);

        // In this approach, we need payload for registration to exist somewhere. If you want to allow resend without re-sending registration data,
        // require client to also pass the registration payload again if previous payload wasn't stored. For simplicity, we allow resend only if payload exists:
        // Find last otp that had payload (could be used as previously created)
        $lastPayloadRow = Otp::where('email', $email)->orderBy('created_at', 'desc')->first();

        if (!$lastPayloadRow || !$lastPayloadRow->payload) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada permintaan registrasi sebelumnya. Silakan kirim data registrasi terlebih dahulu.'
            ], 422);
        }

        $payload = $lastPayloadRow->payload;
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $newOtp = Otp::create([
            'email' => $email,
            'otp' => $otpCode,
            'payload' => $payload,
            'expires_at' => Carbon::now()->addMinutes($this->otpExpiryMinutes),
            'is_used' => false,
        ]);

        Mail::to($email)->send(new OtpMail($otpCode, $this->otpExpiryMinutes, config('app.name', 'QuickFix App')));

        return response()->json([
            'status' => true,
            'message' => 'OTP baru telah dikirim.',
        ]);
    }
}
