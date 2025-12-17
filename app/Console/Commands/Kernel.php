<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Los comandos Artisan de la aplicación.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\edadesupdate::class, // registra tu comando aquí
    ];

    /**
     * Define el programador de la aplicación.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Aquí puedes programar tu cron
        $schedule->command('clientes:tareas')->dailyAt('00:00');
    }

    /**
     * Registra los comandos para la aplicación.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
