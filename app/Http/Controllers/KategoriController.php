<?php
namespace App\Http\Controllers;

use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index()
    {
        $list = Kategori::select('id_kategori','nama_kategori')->get();
        return response()->json(['success'=>true,'data'=>$list]);
    }
}
