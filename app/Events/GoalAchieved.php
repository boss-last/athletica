<?php

namespace App\Events;

use App\Models\Goal;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GoalAchieved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $goal;

    public function __construct(Goal $goal)
    {
        $this->goal = $goal;
    }
}
