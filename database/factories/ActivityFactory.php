<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'sport_type' => fake()->randomElement(['running', 'cycling', 'swimming', 'walking']),
            'name' => fake()->sentence(2),
            'start_time' => fake()->dateTimeBetween('-30 days', 'now'),
            'duration_seconds' => fake()->numberBetween(600, 7200),
            'distance_meters' => fake()->numberBetween(1000, 20000),
            'calories_burned' => fake()->numberBetween(50, 800),
            'avg_heart_rate' => fake()->numberBetween(110, 180),
            'is_manual' => true,
        ];
    }
}
