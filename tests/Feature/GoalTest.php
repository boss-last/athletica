<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Goal;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GoalTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::create([
            'email' => 'goal@athletica.io',
            'username' => 'goaler',
            'full_name' => 'Goal User',
            'password' => bcrypt('test123'),
        ]);
    }

    public function test_user_can_create_goal()
    {
        $response = $this->actingAs($this->user)
            ->post('/goals', [
                'title' => 'Courir 100km',
                'goal_type' => 'distance',
                'target_value' => 100,
                'start_date' => now()->format('Y-m-d'),
                'end_date' => now()->addMonth()->format('Y-m-d'),
            ]);

        $response->assertRedirect('/goals');

        $this->assertDatabaseHas('goals', [
            'title' => 'Courir 100km',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_can_update_goal_progress()
    {
        $goal = Goal::create([
            'user_id' => $this->user->id,
            'title' => 'Objectif test',
            'goal_type' => 'distance',
            'target_value' => 100,
            'current_value' => 0,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
        ]);

        $response = $this->actingAs($this->user)
            ->patch('/goals/' . $goal->id . '/progress', [
                'current_value' => 50,
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('goals', [
            'id' => $goal->id,
            'current_value' => 50,
        ]);
    }

    public function test_goal_is_marked_completed_when_reached()
    {
        $goal = Goal::create([
            'user_id' => $this->user->id,
            'title' => 'Objectif atteint',
            'goal_type' => 'distance',
            'target_value' => 100,
            'current_value' => 0,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
        ]);

        $this->actingAs($this->user)
            ->patch('/goals/' . $goal->id . '/progress', [
                'current_value' => 100,
            ]);

        $this->assertDatabaseHas('goals', [
            'id' => $goal->id,
            'is_completed' => 1,
        ]);
    }
}
