<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'username' => fake()->unique()->userName(),
            'full_name' => fake()->name(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'role' => 'user',
            'is_active' => true,
            'is_premium' => false,
        ];
    }
}
