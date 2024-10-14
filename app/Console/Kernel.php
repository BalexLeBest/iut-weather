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
        $schedule->command('weather:send-forecast-emails')->dailyAt('06:00');
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
    }
}