<?php

namespace App\Listeners;

use App\Events\ActivityCreated;
use App\Services\NotificationService;
use App\Notifications\NewActivityNotification;

class SendActivityNotification
{
    public function handle(ActivityCreated $event)
    {
        $service = new NotificationService();
        $service->sendToFollowers(
            $event->activity->user_id,
            new NewActivityNotification($event->activity)
        );
    }
}