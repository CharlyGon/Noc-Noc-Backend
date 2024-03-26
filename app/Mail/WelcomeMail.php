<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $newUser;

    public function __construct(User $newUser)
    {
        $this->newUser = $newUser;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        try {
            return $this->view('mails.welcome')->with([
                'name' => $this->newUser,
                'email' => $this->newUser->email,
                'password' => $this->newUser->password,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error sending email: ' . $e->getMessage()], 500);
        }
    }
}
