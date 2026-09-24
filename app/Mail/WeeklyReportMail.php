<?php

namespace App\Mail;

use App\Models\User;
use App\Services\StatsService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $stats;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->stats = (new StatsService())->getWeeklyStats($user->id);
    }

    public function build()
    {
        return $this->subject('Récapitulatif hebdomadaire Athletica')
            ->view('emails.weekly-report');
    }
}