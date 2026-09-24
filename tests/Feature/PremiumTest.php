<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PremiumTest extends TestCase
{
    use RefreshDatabase;

    public function test_premium_page_is_accessible()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/premium');
        $response->assertStatus(200);
        $response->assertSee('Premium');
    }

    public function test_premium_page_shows_plans()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/premium');
        $response->assertSee('Gratuit');
        $response->assertSee('Premium');
        $response->assertSee('Pro');
        $response->assertSee('Club');
    }
}
