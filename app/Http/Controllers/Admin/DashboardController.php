<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlahTeknisi = DB::table('teknisi')->count();
        $jumlahPengguna = DB::table('user')->where('role', 'pelanggan')->count();

        return view('admin.dashboard', compact('jumlahTeknisi', 'jumlahPengguna'));
    }
}
