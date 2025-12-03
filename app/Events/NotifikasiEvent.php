<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationEvent implements ShouldBroadcast
{
    public $userId;
    public $judul;
    public $pesan;
    public $tipe;

    public function __construct($userId, $judul, $pesan, $tipe)
    {
        $this->userId = $userId;
        $this->judul = $judul;
        $this->pesan = $pesan;
        $this->tipe = $tipe;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("user.{$this->userId}");
    }

    public function broadcastAs()
    {
        return 'new-notification';
    }
}
    