<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\CustomRequest;

class NewConsultationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customRequest;

    public function __construct(CustomRequest $customRequest)
    {
        $this->customRequest = $customRequest;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Jewelry Consultation Request - ' . $this->customRequest->user_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'mail.new-consultation-mail',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}