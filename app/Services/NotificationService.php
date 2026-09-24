<?php

namespace App\Services;

use App\Models\User;

class NotificationService
{
    public function sendToFollowers($userId, $notification)
    {
        $followers = User::find($userId)->followers;
        foreach ($followers as $follower) {
            $follower->notify($notification);
        }
    }

    public function sendToUser($userId, $notification)
    {
        User::find($userId)->notify($notification);
    }
}