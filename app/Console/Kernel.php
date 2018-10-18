<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
        Commands\SendEmailsReminderVencido::class,
        Commands\SendEmailsReminderVence3::class,
        Commands\SendEmailsReminderVence24::class,
        Commands\alertas::class,
        Commands\ArchiveTicket::class,
        Commands\sendRecordatorioEvaluacionesProveedores::class,
        Commands\sendRecordatorioEvaluacionesVehiculos::class,
        Commands\sendRecordatorioEvaluacionesConductores::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
            $schedule->command('sendMailVencidos')
                ->everyTenMinutes();
                // ->appendOutputTo("ticketsvencidos.txt");

            $schedule->command('sendMailVence3')
                ->everyTenMinutes();
                // ->appendOutputTo("ticketsvence3.txt");

            $schedule->command('sendMailVence24')
                ->everyTenMinutes();
                // ->appendOutputTo("ticketsvence24.txt");
            
            $schedule->command('sendMailReminderManual')
               ->everyTenMinutes();

            $schedule->command('sendRecordatorio:EvaluacionesProveedores')
                ->daily();
                // ->appendOutputTo("EvaluacionesProveedores.txt");

            $schedule->command('sendRecordatorio:EvaluacionesVehiculos')
                ->daily();
                // ->appendOutputTo("EvaluacionesVehiculos.txt");

            $schedule->command('sendRecordatorio:EvaluacionesConductores')
                ->daily();
                // ->appendOutputTo("EvaluacionesConductores.txt");

            $schedule->command('send:Alertas')
                ->everyFiveMinutes();
                // ->appendOutputTo("alertas.txt");

            $schedule->command('archive:tickets')->daily();

            $schedule->command('backup:clean')->daily();
            $schedule->command('backup:run')->daily();
    }
}
