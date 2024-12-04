<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\ShowCurrentWeather::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Exécuter la commande chaque lundi à 6h
        $schedule->command('forecast:send')->weeklyOn(1, '6:00');
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
    }
}
