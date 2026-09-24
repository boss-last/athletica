<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GpsMapTest extends TestCase
{
    use RefreshDatabase;

    public function test_activity_page_loads()
    {
        $user = User::factory()->create();
        $activity = Activity::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/activities/' . $activity->id);
        $response->assertStatus(200);
    }

    public function test_activity_page_shows_details()
    {
        $user = User::factory()->create();
        $activity = Activity::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/activities/' . $activity->id);
        $response->assertSee($activity->sport_type);
    }
}
