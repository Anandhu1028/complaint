<?php

namespace App\Mail;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplaintSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Complaint $complaint) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'New Public Complaint - '.$this->complaint->reference_no);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.complaint-submitted');
    }

    public function attachments(): array
    {
        if (!$this->complaint->attachment_path) return [];
        $full = storage_path('app/private/'.$this->complaint->attachment_path);
        return is_file($full) ? [\Illuminate\Mail\Mailables\Attachment::fromPath($full)] : [];
    }
}
