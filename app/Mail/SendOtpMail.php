<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otpCode
    ) {}

    public function build()
    {
        return $this->subject('Your Hotel Management Verification Code')
            ->view('emails.otp');
    }
}
