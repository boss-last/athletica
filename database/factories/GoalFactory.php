<?php

namespace Database\Factories;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GoalFactory extends Factory
{
    protected $model = Goal::class;

    public function definition(): array
    {
        $target = fake()->numberBetween(10, 100);
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(3),
            'goal_type' => fake()->randomElement(['distance', 'duration', 'frequency', 'calories']),
            'target_value' => $target,
            'current_value' => fake()->numberBetween(0, $target),
            'start_date' => fake()->dateTimeBetween('-30 days', 'now'),
            'end_date' => fake()->dateTimeBetween('now', '+30 days'),
            'is_completed' => 0,
        ];
    }
}
