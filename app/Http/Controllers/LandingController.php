<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index()
    {
        // Hitung teknisi dari tabel teknisi
        $jumlahTeknisi = DB::table('teknisi')->count();

        // Hitung pengguna (pelanggan)
        $jumlahPengguna = DB::table('user')->where('role', 'pelanggan')->count();

        return view('welcome', compact('jumlahTeknisi', 'jumlahPengguna'));
    }
}
