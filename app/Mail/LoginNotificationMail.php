<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $ipAddress;
    public $userAgent;
    public $loginTime;

    public function __construct(User $user, $ipAddress, $userAgent, $loginTime)
    {
        $this->user = $user;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
        $this->loginTime = $loginTime;
    }

    public function build()
    {
        return $this->subject('🔐 Nouvelle connexion à votre compte Athletica')
            ->view('emails.login-notification');
    }
}

