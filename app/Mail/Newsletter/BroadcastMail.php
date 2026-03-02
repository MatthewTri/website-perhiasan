<?php

namespace App\Mail\Newsletter;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BroadcastMail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailSubject;
    public $emailBody;
    public $subscriberEmail;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $body, $subscriberEmail)
    {
        $this->emailSubject = $subject;
        $this->emailBody = $body;
        $this->subscriberEmail = $subscriberEmail;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->emailSubject)
                    ->view('mail.broadcast');
    }
}
