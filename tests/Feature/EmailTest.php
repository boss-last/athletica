<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Mail\WelcomeMail;
use App\Mail\WeeklyReportMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_email_is_sent_on_registration()
    {
        Mail::fake();

        $response = $this->post('/register', [
            'email' => 'newuser@athletica.io',
            'username' => 'newuser',
            'full_name' => 'New User',
        ]);

        $response->assertRedirect();

        Mail::assertSent(WelcomeMail::class);
    }

    public function test_welcome_email_contains_user_name()
    {
        $user = User::factory()->create();
        $mailable = new WelcomeMail($user);

        $mailable->assertSeeInHtml($user->full_name);
        $mailable->assertSeeInHtml('Bienvenue');
    }

    public function test_weekly_report_email_contains_stats()
    {
        $user = User::factory()->create();
        $mailable = new WeeklyReportMail($user);

        $mailable->assertSeeInHtml('Rapport Hebdomadaire');
        $mailable->assertSeeInHtml($user->full_name);
    }
}
