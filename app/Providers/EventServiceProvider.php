<?php

namespace App\Providers;

use App\Events\ActivityCreated;
use App\Events\GoalAchieved;
use App\Listeners\SendActivityNotification;
use App\Listeners\AwardBadge;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ActivityCreated::class => [
            SendActivityNotification::class,
        ],
        GoalAchieved::class => [
            AwardBadge::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}