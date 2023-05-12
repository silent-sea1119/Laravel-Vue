<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class AccountCreationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $password;
    public $link;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param $new_password
     * @param bool $user_updated
     */
    public function __construct($password, $link, $user)
    {
        $this->password = $password;
        $this->link = $link;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("noreply@alphasofthub.com", "Support")->view('emails.acountcreate')->subject("Your account has been created");
    }
}