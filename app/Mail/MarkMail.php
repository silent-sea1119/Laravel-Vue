<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class MarkMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $mark;

    /**
     * Create a new message instance.
     *
     * @param $new_password
     * @param bool $user_updated
     */
    public function __construct($user, $mark)
    {
        $this->user = $user;
        $this->mark = $mark;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("noreply@alphasofthub.com", "Support")->view('emails.mark')->subject("Your account has been {$this->mark} by admin");
    }
}