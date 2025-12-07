<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OtpReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class PasswordResetOtpController extends Controller
{
    protected $otpExpiryMinutes = 5;

    // 1️⃣ Kirim OTP
    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Email tidak ditemukan.'
            ], 404);
        }

        // Hapus OTP lama
        OtpReset::where('email', $request->email)->update(['is_used' => true]);

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $row = OtpReset::create([
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes($this->otpExpiryMinutes),
            'is_used' => false,
        ]);

        // Kirim OTP via email
        Mail::raw("Kode OTP reset password Anda: $otp. Berlaku {$this->otpExpiryMinutes} menit.", function($message) use ($request) {
            $message->to($request->email)
                    ->subject('Reset Password QuickFix');
        });

        return response()->json([
            'status' => true,
            'message' => 'Kode OTP telah dikirim ke email.'
        ]);
    }

    // 2️⃣ Verifikasi OTP & reset password
    public function verifyResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
            'password' => ['required','string','min:8','regex:/[A-Z]/','regex:/[0-9]/'],
        ]);

        $row = OtpReset::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('is_used', false)
            ->first();

        if (!$row) {
            return response()->json([
                'status' => false,
                'message' => 'OTP salah atau sudah digunakan.'
            ], 422);
        }

        if ($row->expires_at < now()) {
            $row->update(['is_used' => true]);
            return response()->json([
                'status' => false,
                'message' => 'OTP sudah kadaluarsa.'
            ], 422);
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        $row->update(['is_used' => true]);

        return response()->json([
            'status' => true,
            'message' => 'Password berhasil direset.'
        ]);
    }
}
