<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::create([
            'email' => 'runner@athletica.io',
            'username' => 'runner',
            'full_name' => 'Test Runner',
            'password' => bcrypt('test123'),
        ]);
    }

    public function test_user_can_create_activity_via_api()
    {
        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/activities', [
                'sport_type' => 'running',
                'start_time' => now()->toISOString(),
                'duration_seconds' => 3600,
                'distance_meters' => 10000,
                'calories_burned' => 500,
            ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'sport_type' => 'running',
                'distance_meters' => 10000,
            ]);
    }

    public function test_user_can_list_activities_via_api()
    {
        Activity::create([
            'user_id' => $this->user->id,
            'sport_type' => 'running',
            'start_time' => now(),
            'distance_meters' => 5000,
        ]);

        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/activities');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_user_can_view_activity_detail()
{
    $user = User::factory()->create();
    $activity = Activity::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get('/activities/' . $activity->id);
    $response->assertStatus(200);
}
    public function test_validation_fails_without_sport_type()
    {
        $token = $this->user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/activities', [
                'start_time' => now()->toISOString(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['sport_type']);
    }
}
