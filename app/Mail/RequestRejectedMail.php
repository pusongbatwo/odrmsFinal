<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $documentRequest;
    public $rejectionReason;

    /**
     * Create a new message instance.
     */
    public function __construct($documentRequest, $rejectionReason)
    {
        $this->documentRequest = $documentRequest;
        $this->rejectionReason = $rejectionReason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: 'Document Request Rejected - Action Required',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content()
    {
        return new \Illuminate\Mail\Mailables\Content(
            view: 'emails.request_rejected',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments()
    {
        return [];
    }

}
