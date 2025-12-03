<?php
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{id_chat}', function ($user, $id_chat) {
    // validasi: user boleh akses channel jika dia pemilik (user) atau teknisi terkait
    $chat = \App\Models\Chat::find($id_chat);
    if (!$chat) return false;

    // $user bisa berupa User model; kita perlu cek id_user / id_teknisi
    // Asumsi: authenticated user model has id_user (pelanggan) or is teknisi via relation
    if (isset($user->id_user) && $chat->id_user == $user->id_user) return true;
    // kalau teknisi login punya relation ke tabel teknisi: cek user->teknisi->id_teknisi
    if (isset($user->teknisi) && $chat->id_teknisi == $user->teknisi->id_teknisi) return true;

    return false;
});

Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int)$user->id_user === (int)$userId;
});


