<?php

namespace Database\Factories;

use App\Models\ActivityGpsPoint;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ActivityGpsPointFactory extends Factory
{
    protected $model = ActivityGpsPoint::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'activity_id' => Activity::factory(),
            'timestamp' => now(),
            'latitude' => fake()->latitude(48.85, 48.87),
            'longitude' => fake()->longitude(2.34, 2.36),
            'altitude' => fake()->numberBetween(30, 50),
            'heart_rate' => fake()->numberBetween(110, 180),
            'speed' => fake()->randomFloat(2, 8, 15),
        ];
    }
}
