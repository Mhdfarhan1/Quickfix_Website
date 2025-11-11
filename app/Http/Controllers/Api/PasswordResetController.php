<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    // 📧 Kirim link reset ke email
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email tidak terdaftar.'], 404);
        }

        // Buat token reset unik
        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Kirim email reset (pakai Mail facade)
        $resetLink = "quickfix://reset-password?token={$token}&email={$request->email}";

        Mail::raw("Klik link berikut untuk reset password kamu: $resetLink", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Reset Password QuickFix');
        });

        return response()->json(['message' => 'Link reset password telah dikirim ke email kamu.']);
    }

    // 🔑 Reset password dengan token
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record) {
            return response()->json(['message' => 'Token tidak ditemukan.'], 400);
        }

        // Token valid 30 menit
        if (Carbon::parse($record->created_at)->addMinutes(30)->isPast()) {
            return response()->json(['message' => 'Token sudah kedaluwarsa.'], 400);
        }

        // Verifikasi token
        if (!Hash::check($request->token, $record->token)) {
            return response()->json(['message' => 'Token tidak valid.'], 400);
        }

        // Update password user
        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        // Hapus token setelah dipakai
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password berhasil direset.']);
    }
}
