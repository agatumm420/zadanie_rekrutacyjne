<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class UserCreated extends Mailable
{
    use Queueable, SerializesModels;
   private $user_login;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user_login=$user['login'];

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('app@support.com')->subject('Account has beed created')->markdown('emails.userCreated', [
            'user_login'          => $this->user_login,

        ]);
    }
}
