<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use App\Events\NotifikasiEvent;

class NotifikasiController extends Controller
{
    // GET /notifications
    public function index(Request $request)
    {
        $userId = $request->user()->id_user;

        $data = Notifikasi::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }


    // POST /notifications/{id}/read
    public function markRead($id, Request $request)
    {
        $notif = Notifikasi::where('id', $id)
            ->where('user_id', $request->user()->id_user)
            ->first();


        if (!$notif) {
            return response()->json(['status' => false, 'message' => 'Notifikasi tidak ditemukan'], 404);
        }

        $notif->is_read = true;
        $notif->save();

        return response()->json(['status' => true, 'message' => 'Notifikasi dibaca']);
    }

    // POST /notifications (untuk membuat notifikasi baru)
    public function create(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer',
            'judul' => 'required|string',
            'pesan' => 'required|string',
            'tipe' => 'required|string',
        ]);

        $notif = Notifikasi::create($data);

        // 🔥 BROADCAST REALTIME
        broadcast(new NotifikasiEvent($notif))->toOthers();

        return response()->json([
            'status' => true,
            'data' => $notif
        ]);
    }
}
