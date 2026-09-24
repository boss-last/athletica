<?php

namespace App\Notifications;

use App\Models\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewActivityNotification extends Notification
{
    use Queueable;

    public $activity;

    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Nouvelle activité enregistrée : {$this->activity->sport_type} - {$this->activity->distance_km} km",
            'activity_id' => $this->activity->id,
            'sport_type' => $this->activity->sport_type,
            'type' => 'new_activity',
        ];
    }
}
