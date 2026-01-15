<?php
// filepath: c:\laragon\www\website-perhiasan\app\Mail\Consultation\ConsultationConfirmationMail.php

namespace App\Mail\Consultation;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\CustomRequest;
use Illuminate\Support\Facades\Storage;


class ConsultationConfirmationMail extends Mailable
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
            subject: 'Consultation Confirmed - ' . $this->customRequest->appointment_date->format('M j, Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'mail.consultation-confirmation',
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
                $ext = pathinfo($imagePath, PATHINFO_EXTENSION) ?: 'jpg';
                $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromStorageDisk('public', $imagePath)
                    ->as("reference_image_" . ($index + 1) . "." . $ext);
            }
        }
        
        return $attachments;
    }
}