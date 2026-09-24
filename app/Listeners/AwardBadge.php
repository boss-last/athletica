<?php

namespace App\Listeners;

use App\Events\GoalAchieved;
use App\Models\Badge;

class AwardBadge
{
    public function handle(GoalAchieved $event)
    {
        // Badge "Objectif Atteint"
        $badge = Badge::where('name', 'Objectif Atteint')->first();
        if ($badge) {
            $event->goal->user->badges()->syncWithoutDetaching([$badge->id => ['unlocked_at' => now()]]);
        }
    }
}