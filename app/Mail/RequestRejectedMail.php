<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\DocumentRequest;

class RequestRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $request;

    public function __construct(DocumentRequest $request)
    {
        $this->request = $request;
    }

    public function build()
    {
        return $this->subject('Your Document Request Has Been Rejected')
            ->view('emails.request_rejected');
    }
}
