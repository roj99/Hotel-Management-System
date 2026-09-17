<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $name,
        public string $email,
        public string $messageBody,
    ) {}

    public function build()
    {
        return $this->subject('New contact message from ' . $this->name)
            ->replyTo($this->email, $this->name)
            ->view('emails.contact-message');
    }
}
