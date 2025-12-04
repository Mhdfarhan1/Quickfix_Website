<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keahlian;

class KeahlianController extends Controller
{
    public function index(Request $request)
    {
        $q = Keahlian::query();
        if ($request->has('kategori_id') && $request->kategori_id) {
            $q->where('id_kategori', $request->kategori_id);
        }
        $list = $q->select('id_keahlian','id_kategori','nama_keahlian','deskripsi')->get();
        return response()->json(['success'=>true,'data'=>$list]);
    }
}
