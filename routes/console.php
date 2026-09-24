<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Récapitulatif hebdomadaire envoyé chaque lundi à 08:00 (heure locale)
Schedule::command('reports:weekly')->weekly()->mondays()->at('08:00');
