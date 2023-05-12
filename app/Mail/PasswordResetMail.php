<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $new_password;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param $new_password
     * @param bool $user_updated
     */
    public function __construct($new_password, $user)
    {
        $this->new_password = $new_password;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("noreply@alphasofthub.com", "Support")->view('emails.passwordreset')->subject("Password Reset");
    }
}