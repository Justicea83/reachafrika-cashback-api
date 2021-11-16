<?php

namespace App\Mail\Auth;

use App\Models\User;
use Illuminate\Mail\Mailable;

class ForgotPasswordMail extends Mailable
{
    public User $user;
    public string $token;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): ForgotPasswordMail
    {
        return $this->subject('Forgot Password')->view('emails.auth.forgot-password');
    }
}
