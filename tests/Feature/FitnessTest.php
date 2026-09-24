<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\FitnessMetric;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FitnessTest extends TestCase
{
    use RefreshDatabase;

    public function test_fitness_page_is_accessible()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/fitness');
        $response->assertStatus(200);
        $response->assertSee('Fitness');
    }

    public function test_user_can_add_fitness_metric()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/fitness', [
            'metric_date' => now()->format('Y-m-d'),
            'vo2max' => 45.5,
            'resting_heart_rate' => 60,
            'heart_rate_variability' => 55.5,
            'fitness_score' => 80,
            'fatigue_score' => 30,
            'form_score' => 50,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('fitness_metrics', [
            'user_id' => $user->id,
            'vo2max' => 45.5,
            'resting_heart_rate' => 60,
        ]);
    }

    public function test_fitness_metrics_list_displays_data()
    {
        $user = User::factory()->create();

        FitnessMetric::create([
            'user_id' => $user->id,
            'metric_date' => now(),
            'vo2max' => 48.0,
            'resting_heart_rate' => 55,
            'heart_rate_variability' => 60.0,
            'fitness_score' => 85,
            'form_score' => 55,
        ]);

        $response = $this->actingAs($user)->get('/fitness');
        $response->assertSee('48');
        $response->assertSee('55');
    }

    public function test_validation_rejects_invalid_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/fitness', [
            'metric_date' => '',
            'vo2max' => 'invalid',
        ]);

        $response->assertSessionHasErrors('metric_date');
    }

    public function test_fitness_metrics_are_user_specific()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        FitnessMetric::create([
            'user_id' => $user1->id,
            'metric_date' => now(),
            'vo2max' => 50.0,
        ]);

        FitnessMetric::create([
            'user_id' => $user2->id,
            'metric_date' => now(),
            'vo2max' => 40.0,
        ]);

        $count = FitnessMetric::where('user_id', $user1->id)->count();
        $this->assertEquals(1, $count);
    }
}