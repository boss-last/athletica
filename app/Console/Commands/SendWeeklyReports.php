<?php

namespace App\Console\Commands;

use App\Mail\WeeklyReportMail;
use App\Models\User;
use App\Services\StatsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWeeklyReports extends Command
{
    /**
     * Le nom et la signature de la commande.
     */
    protected $signature = 'reports:weekly';

    /**
     * La description de la commande.
     */
    protected $description = 'Envoie le récapitulatif hebdomadaire à tous les utilisateurs actifs';

    /**
     * Exécute la commande.
     */
    public function handle(StatsService $stats): int
    {
        $sent = 0;
        $skipped = 0;
        $failed = 0;

        // Uniquement les utilisateurs actifs
        $users = User::active()->get();
        $this->info("Traitement de {$users->count()} utilisateur(s) actif(s)...");

        foreach ($users as $user) {
            // Ne pas envoyer si l'utilisateur n'a pas eu d'activité cette semaine
            $weekly = $stats->getWeeklyStats($user->id);
            if (!$weekly || $weekly->total == 0) {
                $skipped++;
                continue;
            }

            try {
                Mail::to($user->email)->send(new WeeklyReportMail($user));
                $sent++;
            } catch (\Exception $e) {
                $failed++;
                Log::warning("Échec envoi rapport hebdo à {$user->email}: {$e->getMessage()}");
            }
        }

        $this->info("Terminé : {$sent} envoyé(s), {$skipped} ignoré(s) sans activité), {$failed} échec(s).");

        return self::SUCCESS;
    }
}
