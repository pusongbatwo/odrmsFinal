<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\DocumentRequest;

class DocumentRequestSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $docRequest;

    public function __construct(DocumentRequest $docRequest)
    {
        $this->docRequest = $docRequest;
    }

    public function build()
    {
        return $this->subject('Document Request Submitted')
                    ->view('emails.document_request_submitted');
    }
}

