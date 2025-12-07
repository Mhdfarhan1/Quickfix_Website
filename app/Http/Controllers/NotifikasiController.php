<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use App\Events\NotificationEvent;

class NotifikasiController extends Controller
{
    // GET /notifications
    public function index(Request $request)
    {
        $userId = $request->user()->id_user;

        $data = Notifikasi::where('id_user', $userId)
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
            ->where('id_user', $request->user()->id_user)
            ->first();

        if (!$notif) {
            return response()->json(['status' => false, 'message' => 'Notifikasi tidak ditemukan'], 404);
        }

        $notif->is_read = 1;
        $notif->save();

        return response()->json(['status' => true, 'message' => 'Notifikasi dibaca']);
    }


    // POST /notifications
    public function create(Request $request)
    {
        $data = $request->validate([
            'id_user' => 'required|integer',
            'judul'   => 'required|string',
            'pesan'   => 'required|string',
            'tipe'    => 'required|string',
        ]);

        $notif = Notifikasi::create($data);

        // PUSHER
        broadcast(new NotificationEvent(
            $notif->id_user,
            $notif->judul,
            $notif->pesan,
            $notif->tipe
        ))->toOthers();

        return response()->json([
            'status' => true,
            'data' => $notif
        ]);
    }
}
