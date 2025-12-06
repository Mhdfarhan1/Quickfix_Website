<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\KeahlianTeknisi;
use App\Models\Teknisi;
use Illuminate\Validation\Rule;

class KeahlianTeknisiController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_keahlian' => ['nullable', 'integer', 'exists:keahlian,id_keahlian'],
            'nama' => ['required_without:id_keahlian', 'nullable', 'string', 'max:255'],
            'harga_min' => ['nullable', 'integer', 'min:0'],
            'harga_max' => ['nullable', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
            'gambar_layanan' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
        ]);

        // cari teknisi berdasarkan user terautentikasi
        $teknisi = Teknisi::where('id_user', $request->user()->id_user)->first();
        if (!$teknisi) {
            return response()->json(['success' => false, 'message' => 'Profil teknisi belum ada.'], 403);
        }

        $path = null;
        if ($request->hasFile('gambar_layanan')) {
            $path = $request->file('gambar_layanan')->store('keahlian_teknisi', 'public');
        }

        $record = KeahlianTeknisi::create([
            'id_teknisi' => $teknisi->id_teknisi,
            'id_keahlian' => $data['id_keahlian'] ?? null,
            'nama' => $data['nama'] ?? null,
            'harga_min' => $data['harga_min'] ?? null,
            'harga_max' => $data['harga_max'] ?? null,
            'deskripsi' => $data['deskripsi'] ?? null,
            'gambar_layanan' => $path ? ('/storage/' . $path) : null,
        ]);

        return response()->json([
            'success' => true,
            'data' => $record,
            'message' => 'Layanan berhasil ditambahkan'
        ], 201);
    }

    public function getByTeknisi(Request $request)
    {
        $teknisi = Teknisi::where('id_user', $request->user()->id_user)->first();
        if (!$teknisi) {
            return response()->json(['success' => false, 'message' => 'Profil teknisi belum ada.'], 403);
        }

        $services = KeahlianTeknisi::where('id_teknisi', $teknisi->id_teknisi)
            ->with('keahlian')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $services,
        ], 200);
    }

    public function getByTeknisiId($id_teknisi)
        {
            $services = KeahlianTeknisi::where('id_teknisi', $id_teknisi)
                ->with('keahlian')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $services
            ]);
        }


    public function update(Request $request, $id)
    {
        $service = KeahlianTeknisi::find($id);
        if (!$service) {
            return response()->json(['success' => false, 'message' => 'Layanan tidak ditemukan'], 404);
        }

        $teknisi = Teknisi::where('id_user', $request->user()->id_user)->first();
        if (!$teknisi || $service->id_teknisi != $teknisi->id_teknisi) {
            return response()->json(['success' => false, 'message' => 'Tidak authorized'], 403);
        }

        $data = $request->validate([
            'id_keahlian' => ['nullable', 'integer', 'exists:keahlian,id_keahlian'],
            'nama' => ['nullable', 'string', 'max:255'],
            'harga_min' => ['nullable', 'integer', 'min:0'],
            'harga_max' => ['nullable', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
            'gambar_layanan' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
        ]);

        if ($request->hasFile('gambar_layanan')) {
            if ($service->gambar_layanan) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $service->gambar_layanan));
            }
            $path = $request->file('gambar_layanan')->store('keahlian_teknisi', 'public');
            $data['gambar_layanan'] = '/storage/' . $path;
        }

        $service->update($data);

        return response()->json([
            'success' => true,
            'data' => $service,
            'message' => 'Layanan berhasil diperbarui'
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $service = KeahlianTeknisi::find($id);
        if (!$service) {
            return response()->json(['success' => false, 'message' => 'Layanan tidak ditemukan'], 404);
        }

        $teknisi = Teknisi::where('id_user', $request->user()->id_user)->first();
        if (!$teknisi || $service->id_teknisi != $teknisi->id_teknisi) {
            return response()->json(['success' => false, 'message' => 'Tidak authorized'], 403);
        }

        if ($service->gambar_layanan) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $service->gambar_layanan));
        }

        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Layanan berhasil dihapus'
        ], 200);
    }
}
