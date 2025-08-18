<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentRequestVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationToken;
    public $requestData;
    public $expiresAt;

    /**
     * Create a new message instance.
     */
    public function __construct($verificationToken, $requestData, $expiresAt)
    {
        $this->verificationToken = $verificationToken;
        $this->requestData = $requestData;
        $this->expiresAt = $expiresAt;
    }

    /**
     * Get the message envelope.
     */
    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: 'Verify Your Document Request - iRequest System',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content()
    {
        return new \Illuminate\Mail\Mailables\Content(
            view: 'emails.document_request_verification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
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
        return $this->view('emails.document_request_verification')
                    ->subject('Verify Your Document Request - iRequest System')
                    ->with([
                        'verificationToken' => $this->verificationToken,
                        'requestData' => $this->requestData,
                        'expiresAt' => $this->expiresAt
                    ]);
    }
}
