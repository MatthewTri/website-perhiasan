<?php

namespace App\Mail\Consultation;

use App\Models\CustomRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConsultationRescheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customRequest;

    public function __construct(CustomRequest $customRequest)
    {
        $this->customRequest = $customRequest;
    }

    public function build()
    {
        return $this->subject('Your Consultation Has Been Rescheduled - Cecile Private Collection')
                    ->view('mail.consultation-rescheduled');
    }
}