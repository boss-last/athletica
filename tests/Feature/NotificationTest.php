<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notifications_page_is_accessible()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/notifications');
        $response->assertStatus(200);
        $response->assertSee('Notifications');
    }

    public function test_notification_can_be_created()
    {
        $user = User::factory()->create();

        Notification::create([
            'type' => 'App\Notifications\NewActivityNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $user->id,
            'data' => json_encode([
                'message' => 'Nouvelle activité : running - 5 km',
                'type' => 'new_activity',
            ]),
        ]);

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $user->id,
        ]);
    }

    public function test_notification_can_be_marked_as_read()
    {
        $user = User::factory()->create();

        $notification = Notification::create([
            'type' => 'App\Notifications\NewActivityNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $user->id,
            'data' => json_encode(['message' => 'Test notification']),
        ]);

        $response = $this->actingAs($user)->patch('/notifications/' . $notification->id . '/read');
        $response->assertRedirect();

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_all_notifications_can_be_marked_as_read()
    {
        $user = User::factory()->create();

        Notification::create([
            'type' => 'App\Notifications\NewActivityNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $user->id,
            'data' => json_encode(['message' => 'Notif 1']),
        ]);

        Notification::create([
            'type' => 'App\Notifications\NewActivityNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $user->id,
            'data' => json_encode(['message' => 'Notif 2']),
        ]);

        $response = $this->actingAs($user)->patch('/notifications/read-all');
        $response->assertRedirect();

        $unreadCount = Notification::where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->count();

        $this->assertEquals(0, $unreadCount);
    }
}