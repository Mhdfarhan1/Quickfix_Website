<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alamat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class AlamatController extends Controller
{
    // 🔹 Get all alamat user (yang login)
    public function index(Request $request)
    {
        $user = $request->user();

        $alamat = Alamat::where('id_user', $user->id_user)->get();

        return response()->json([
            'status' => true,
            'message' => 'Data alamat',
            'data' => $alamat // kalau kosong => []
        ]);
    }

    // 🔹 Tambah alamat baru
    public function store(Request $request)
    {
        Log::info("📥 Masuk ke endpoint /api/alamat");

        $user = Auth::user();

        if (!$user) {
            Log::error("🚫 User tidak terautentikasi (TOKEN INVALID / TIDAK TERBACA)");
            return response()->json([
                'status' => false,
                'message' => 'User tidak terautentikasi'
            ], 401);
        }

        Log::info("✅ User terautentikasi", [
            'id_user' => $user->id_user,
            'email' => $user->email ?? '-'
        ]);

        Log::info("📦 Data request", $request->all());

        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'alamat_lengkap' => 'required|string|max:255',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_default' => 'nullable|boolean'
        ]);

        Log::info("✅ Data tervalidasi", $validated);

        $hasDefault = Alamat::where('id_user', $user->id_user)
            ->where('is_default', true)
            ->exists();

        $isDefault = $validated['is_default'] ?? false;

        if (!$hasDefault) {
            $isDefault = true;
            Log::info("ℹ️ Alamat pertama → otomatis jadi default");
        }

        if ($isDefault) {
            Alamat::where('id_user', $user->id_user)->update([
                'is_default' => false
            ]);
            Log::info("🔄 Reset alamat default lama");
        }

        $alamat = Alamat::create([
            'id_user' => $user->id_user,
            'label' => $validated['label'],
            'alamat_lengkap' => $validated['alamat_lengkap'],
            'kota' => $validated['kota'] ?? null,
            'provinsi' => $validated['provinsi'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'is_default' => $isDefault,
        ]);

        Log::info("✅ Alamat berhasil disimpan", [
            'id_alamat' => $alamat->id_alamat
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Alamat berhasil ditambahkan',
            'data' => $alamat
        ], 201);
    }

    // 🔹 Update alamat
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $alamat = Alamat::where('id_user', $user->id_user)
            ->where('id_alamat', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'label' => 'nullable|string|max:100',
            'alamat_lengkap' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_default' => 'nullable|boolean'
        ]);

        if (isset($validated['is_default']) && $validated['is_default'] == true) {
            Alamat::where('id_user', $user->id_user)->update([
                'is_default' => false
            ]);
        }

        $alamat->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Alamat berhasil diperbarui',
            'data' => $alamat
        ]);
    }

    // 🔹 Hapus alamat
    public function destroy($id)
    {
        $user = Auth::user();

        $alamat = Alamat::where('id_user', $user->id_user)
            ->where('id_alamat', $id)
            ->firstOrFail();

        $isDefault = $alamat->is_default;

        $alamat->delete();

        // Kalau yang dihapus itu default, set alamat lain jadi default
        if ($isDefault) {
            $newDefault = Alamat::where('id_user', $user->id_user)->first();
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Alamat berhasil dihapus'
        ]);
    }

    // 🔹 Atur alamat default
    public function setDefault($id)
    {
        $user = Auth::user();

        $alamat = Alamat::where('id_user', $user->id_user)
            ->where('id_alamat', $id)
            ->firstOrFail();

        // Reset semua
        Alamat::where('id_user', $user->id_user)->update([
            'is_default' => false
        ]);

        // Set yang ini jadi default
        $alamat->update([
            'is_default' => true
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Alamat default berhasil diperbarui',
            'data' => $alamat
        ]);
    }
}
