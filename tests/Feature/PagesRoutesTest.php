<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PagesRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_access_main_pages()
    {
        $user = new User();
        $user->email = 'pagetest@athletica.io';
        $user->username = 'pagetester';
        $user->full_name = 'Page Tester';
        $user->password = bcrypt('test123');
        $user->save();

        $this->actingAs($user);

        // Pages qui marchent
        $this->get('/dashboard')->assertStatus(200);
        $this->get('/activities')->assertStatus(200);
        $this->get('/goals')->assertStatus(200);
        $this->get('/challenges')->assertStatus(200);
        $this->get('/profile')->assertStatus(200);
        $this->get('/notifications')->assertStatus(200);
        $this->get('/network')->assertStatus(200);
    }
}