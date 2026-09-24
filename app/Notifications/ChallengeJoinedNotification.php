<?php

namespace App\Notifications;

use App\Models\Challenge;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ChallengeJoinedNotification extends Notification
{
    use Queueable;

    public $challenge;
    public $user;

    public function __construct(Challenge $challenge, User $user)
    {
        $this->challenge = $challenge;
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "{$this->user->full_name} a rejoint votre challenge « {$this->challenge->title} »",
            'challenge_id' => $this->challenge->id,
            'user_id' => $this->user->id,
            'user_name' => $this->user->full_name,
            'type' => 'challenge_joined',
        ];
    }
}
