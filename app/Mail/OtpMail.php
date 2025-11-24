<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $minutes;
    public $appName;

    public function __construct(string $otp, int $minutes = 5, string $appName = 'QuickFix App')
    {
        $this->otp = $otp;
        $this->minutes = $minutes;
        $this->appName = $appName;
    }

    public function build()
    {
        return $this
            ->subject("Kode OTP kamu — {$this->appName}")
            ->markdown('emails.otp')
            ->with([
                'otp' => $this->otp,
                'minutes' => $this->minutes,
                'appName' => $this->appName,
            ]);
    }
}
