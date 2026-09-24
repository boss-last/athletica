<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Badge;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BadgeTest extends TestCase
{
    use RefreshDatabase;

    public function test_badges_page_is_accessible()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/badges');
        $response->assertStatus(200);
        $response->assertSee('Badges');
    }

    public function test_badges_page_shows_available_badges()
    {
        $user = User::factory()->create();

        Badge::create([
            'name' => 'Premier Pas',
            'description' => 'Première activité',
            'points' => 10,
        ]);

        Badge::create([
            'name' => '5K Finisher',
            'description' => '5km courus',
            'points' => 50,
        ]);

        $response = $this->actingAs($user)->get('/badges');
        $response->assertSee('Premier Pas');
        $response->assertSee('5K Finisher');
    }

    public function test_user_can_earn_badge()
    {
        $user = User::factory()->create();

        $badge = Badge::create([
            'name' => 'Test Badge',
            'description' => 'Badge de test',
            'points' => 100,
        ]);

        // Attribuer le badge
        \DB::table('user_badges')->insert([
            'user_id' => $user->id,
            'badge_id' => $badge->id,
            'unlocked_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertDatabaseHas('user_badges', [
            'user_id' => $user->id,
            'badge_id' => $badge->id,
        ]);
    }

    public function test_badge_has_correct_attributes()
    {
        $badge = Badge::create([
            'name' => 'Marathonien',
            'description' => 'Marathon complété',
            'category' => 'running',
            'points' => 200,
        ]);

        $this->assertEquals('Marathonien', $badge->name);
        $this->assertEquals(200, $badge->points);
        $this->assertEquals('running', $badge->category);
    }
}
