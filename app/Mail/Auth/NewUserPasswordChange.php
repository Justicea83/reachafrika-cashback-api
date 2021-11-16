<?php

namespace App\Mail\Auth;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUserPasswordChange extends Mailable
{
    use Queueable, SerializesModels;

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
    public function build(): NewUserPasswordChange
    {
        return $this->subject('Password Reset')->view('emails.auth.new-user-password-change');
    }
}
