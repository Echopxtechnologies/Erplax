<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ForgotPasswordOtpMail extends Mailable
{
    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Password Reset OTP')
            ->view('admin.auth.forgot-password-otp');
    }
}
