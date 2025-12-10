<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\CustomRequest;
use Illuminate\Support\Facades\Storage;

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
            subject: 'New Jewellery Consultation Request - ' . $this->customRequest->user_name,
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
        $attachments = [];
        
        foreach ($this->customRequest->images as $index => $image) {
            // Extract file path from URL
            $imagePath = str_replace(asset('storage/'), '', $image->image_url);
            
            // Check if file exists
            if (Storage::disk('public')->exists($imagePath)) {
                $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromStorageDisk('public', $imagePath)
                    ->as("reference_image_" . ($index + 1) . ".jpg");
            }
        }
        
        return $attachments;
    }
}