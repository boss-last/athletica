<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Challenge;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChallengeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_challenge()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/challenges', [
            'title' => 'Défi 100km',
            'description' => 'Objectif mensuel',
            'challenge_type' => 'distance',
            'target_value' => 100,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addMonth()->format('Y-m-d'),
        ]);

        $response->assertRedirect('/challenges');
        $this->assertDatabaseHas('challenges', [
            'title' => 'Défi 100km',
            'creator_id' => $user->id,
        ]);
    }

    public function test_challenge_list_is_accessible()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/challenges');
        $response->assertStatus(200);
        $response->assertSee('Challenges');
    }

    public function test_user_can_join_challenge()
    {
        $creator = User::factory()->create();
        $participant = User::factory()->create();

        $challenge = Challenge::create([
            'creator_id' => $creator->id,
            'title' => 'Défi test',
            'challenge_type' => 'distance',
            'target_value' => 100,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'is_public' => 1,
        ]);

        $response = $this->actingAs($participant)->post('/challenges/' . $challenge->id . '/join');
        $response->assertRedirect();

        $this->assertDatabaseHas('challenge_participants', [
            'challenge_id' => $challenge->id,
            'user_id' => $participant->id,
        ]);
    }

    public function test_validation_fails_without_title()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/challenges', [
            'title' => '',
            'challenge_type' => 'distance',
            'target_value' => 100,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addMonth()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_challenge_show_page_displays_leaderboard()
    {
        $creator = User::factory()->create();
        $challenge = Challenge::create([
            'creator_id' => $creator->id,
            'title' => 'Classement test',
            'challenge_type' => 'distance',
            'target_value' => 100,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'is_public' => 1,
        ]);

        $response = $this->actingAs($creator)->get('/challenges/' . $challenge->id);
        $response->assertStatus(200);
        $response->assertSee('Classement');
    }
}