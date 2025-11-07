<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IncidentDetected extends Mailable
{
    use Queueable, SerializesModels;

    public $incident;

    public function __construct($incident)
    {
        $this->incident = $incident;
    }

    public function build()
    {
        return $this->subject('⚠️ Incident Detected on QuickFix System')
                    ->view('emails.incident');
    }
}
