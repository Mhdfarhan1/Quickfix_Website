<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiSkckExpired extends Mailable
{
    use Queueable, SerializesModels;

    public $namaUser;
    public $expiredDate;

    public function __construct($namaUser, $expiredDate)
    {
        $this->namaUser = $namaUser;
        $this->expiredDate = $expiredDate;
    }

    public function build()
    {
        return $this->subject('PENTING: Masa Berlaku SKCK Anda Telah Habis')
                    ->view('emails.skck_expired');
    }
}