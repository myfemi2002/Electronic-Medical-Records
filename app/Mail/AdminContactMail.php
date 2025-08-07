<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\ContactMessage;
use Illuminate\Queue\SerializesModels;

class AdminContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $message;

    public function __construct(Contact $message)
    {
        $this->message = $message;
    }

    public function build()
    {
        return $this->markdown('emails.admin-contact')
                    ->subject('New Contact Form Submission')
                    ->from(config('mail.from.address'), config('mail.from.name'));
    }
}