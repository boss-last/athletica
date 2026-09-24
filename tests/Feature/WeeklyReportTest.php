<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Activity;
use App\Mail\WeeklyReportMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WeeklyReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_weekly_report_command_runs_successfully()
    {
        $user = User::factory()->create();

        // Créer une activité cette semaine pour que l'email soit envoyé
        Activity::factory()->create([
            'user_id' => $user->id,
            'start_time' => now()->subDay(),
        ]);

        Mail::fake();

        $this->artisan('reports:weekly')
            ->assertSuccessful();

        Mail::assertSent(WeeklyReportMail::class);
    }

    public function test_weekly_report_skips_users_without_activity()
    {
        User::factory()->create(); // Pas d'activités

        Mail::fake();

        $this->artisan('reports:weekly')
            ->assertSuccessful();

        Mail::assertNotSent(WeeklyReportMail::class);
    }

    public function test_weekly_report_is_scheduled()
    {
        // Vérifier que la commande est bien enregistrée dans le scheduler
        $schedule = app()->make(\Illuminate\Console\Scheduling\Schedule::class);
        $events = $schedule->events();

        $hasWeeklyReport = false;
        foreach ($events as $event) {
            if (strpos($event->command, 'reports:weekly') !== false) {
                $hasWeeklyReport = true;
                break;
            }
        }

        $this->assertTrue($hasWeeklyReport, 'La commande reports:weekly n\'est pas planifiée.');
    }
}
