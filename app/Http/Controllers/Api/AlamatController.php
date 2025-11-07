<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alamat;
use Illuminate\Support\Facades\Auth;

class AlamatController extends Controller
{
    // 🔹 Get all alamat user (yang login)
    public function index()
    {
        $user = Auth::user();

        $alamat = Alamat::where('id_user', $user->id_user)->get();

        return response()->json([
            'status' => true,
            'data' => $alamat
        ]);
    }

    // 🔹 Tambah alamat baru
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'alamat_lengkap' => 'required|string|max:255',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_default' => 'boolean'
        ]);

        if (!empty($validated['is_default']) && $validated['is_default']) {
            // Reset alamat default lainnya
            Alamat::where('id_user', $user->id_user)->update(['is_default' => false]);
        }

        $alamat = Alamat::create(array_merge($validated, [
            'id_user' => $user->id_user
        ]));

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
        $alamat = Alamat::where('id_user', $user->id_user)->findOrFail($id);

        $validated = $request->validate([
            'label' => 'nullable|string|max:100',
            'alamat_lengkap' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_default' => 'boolean'
        ]);

        if (!empty($validated['is_default']) && $validated['is_default']) {
            Alamat::where('id_user', $user->id_user)->update(['is_default' => false]);
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
        $alamat = Alamat::where('id_user', $user->id_user)->findOrFail($id);

        $alamat->delete();

        return response()->json([
            'status' => true,
            'message' => 'Alamat berhasil dihapus'
        ]);
    }

    // 🔹 Atur alamat default
    public function setDefault($id)
    {
        $user = Auth::user();

        Alamat::where('id_user', $user->id_user)->update(['is_default' => false]);
        Alamat::where('id_user', $user->id_user)->where('id_alamat', $id)->update(['is_default' => true]);

        return response()->json([
            'status' => true,
            'message' => 'Alamat default berhasil diperbarui'
        ]);
    }
}
