<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifikasiDisetujui extends Mailable
{
    use Queueable, SerializesModels;

    public $namaUser;

    public function __construct($namaUser)
    {
        $this->namaUser = $namaUser;
    }

    public function build()
    {
        return $this->subject('Selamat! Akun Teknisi Anda Telah Diverifikasi')
                    ->view('emails.verifikasi_disetujui');
    }
}