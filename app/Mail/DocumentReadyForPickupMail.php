<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\DocumentRequest;

class DocumentReadyForPickupMail extends Mailable
{
    use Queueable, SerializesModels;

    public $documentRequest;
    public $daysUntilRelease;

    /**
     * Create a new message instance.
     */
    public function __construct(DocumentRequest $documentRequest, $daysUntilRelease)
    {
        $this->documentRequest = $documentRequest;
        $this->daysUntilRelease = $daysUntilRelease;
    }

    /**
     * Get the message envelope.
     */
    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: 'Your Document Request is Ready for Pickup - iRequest System',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content()
    {
        return new \Illuminate\Mail\Mailables\Content(
            view: 'emails.document_ready_for_pickup',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments()
    {
        return [];
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('emails.document_ready_for_pickup')
                    ->subject('Your Document Request is Ready for Pickup - iRequest System')
                    ->with([
                        'documentRequest' => $this->documentRequest,
                        'daysUntilRelease' => $this->daysUntilRelease
                    ]);
    }
}
