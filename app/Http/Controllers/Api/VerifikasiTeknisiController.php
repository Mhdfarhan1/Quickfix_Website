<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VerifikasiTeknisi;

class VerifikasiTeknisiController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'alamat'        => 'required|string',
            'provinsi'      => 'required|string',
            'kabupaten'     => 'required|string',
            'kecamatan'     => 'required|string',

            'nik'           => 'nullable|string',
            'nama'          => 'nullable|string',
            'rekening'      => 'nullable|string',
            'bank'          => 'nullable|string',
            'skck_expired'  => 'nullable|date',

            'ktp'           => 'required|file|mimes:jpg,png,jpeg',
            'skck'          => 'required|file|mimes:jpg,png,jpeg',
            'buku_tabungan' => 'required|file|mimes:jpg,png,jpeg',
        ]);

        $ver = new VerifikasiTeknisi();
        $ver->id_teknisi   = $user->id;

        $ver->nik          = $request->nik;
        $ver->nama         = $request->nama;
        $ver->alamat       = $request->alamat;
        $ver->provinsi     = $request->provinsi;
        $ver->kabupaten    = $request->kabupaten;
        $ver->kecamatan    = $request->kecamatan;

        $ver->rekening     = $request->rekening;
        $ver->bank         = $request->bank;
        $ver->skck_expired = $request->skck_expired;

        if ($request->hasFile('ktp')) {
            $ver->foto_ktp = $request->file('ktp')->store('verifikasi', 'public');
        }

        if ($request->hasFile('skck')) {
            $ver->foto_skck = $request->file('skck')->store('verifikasi', 'public');
        }

        if ($request->hasFile('buku_tabungan')) {
            $ver->buku_tabungan = $request->file('buku_tabungan')->store('verifikasi', 'public');
        }

        $ver->status = 'pending';
        $ver->save();

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi teknisi berhasil disimpan.',
            'data'    => $ver
        ], 201);
    }
}
