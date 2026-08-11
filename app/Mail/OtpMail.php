<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;

    public $expires;

    public $name;

    public function __construct($name, $code, $expires)
    {
        $this->name = $name;
        $this->code = $code;
        $this->expires = $expires;
    }

    public function build()
    {
        return $this->subject('Your HappyStem Password Reset Code')
            ->view('emails.otp');
    }
}
