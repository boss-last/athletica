<?php

namespace App\Notifications;

use App\Models\Goal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GoalAchievedNotification extends Notification
{
    use Queueable;

    public $goal;

    public function __construct(Goal $goal)
    {
        $this->goal = $goal;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "🎉 Félicitations ! Vous avez atteint votre objectif « {$this->goal->title} » !",
            'goal_id' => $this->goal->id,
            'goal_title' => $this->goal->title,
            'type' => 'goal_achieved',
        ];
    }
}
