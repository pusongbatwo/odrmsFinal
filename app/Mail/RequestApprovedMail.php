<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $documentRequest;
    public $referenceNumber;

    /**
     * Create a new message instance.
     */
    public function __construct($documentRequest, $referenceNumber)
    {
        $this->documentRequest = $documentRequest;
        $this->referenceNumber = $referenceNumber;
    }

    /**
     * Get the message envelope.
     */
    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: 'Document Request Approved and Available - Reference Number Generated',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content()
    {
        return new \Illuminate\Mail\Mailables\Content(
            view: 'emails.request_approved',
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
