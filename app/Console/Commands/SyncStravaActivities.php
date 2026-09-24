<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\StravaService;
use Illuminate\Console\Command;

class SyncStravaActivities extends Command
{
    protected $signature = 'strava:sync';
    protected $description = 'Synchronise les activités Strava de tous les utilisateurs';

    public function handle()
    {
        $service = new StravaService();
        $users = User::whereHas('platformConnections', function ($q) {
            $q->where('platform_name', 'strava');
        })->get();

        foreach ($users as $user) {
            $this->info("Syncing: {$user->full_name}");
            $count = $service->syncActivities($user->id);
            $this->info("Imported: " . count($count) . " activities");
        }

        $this->info('Sync completed!');
    }
}
