<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class ArchiveTicket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archiva los tickets cuyo estado  esta completado o rechazado y llevan mas de 8 dias sin cambios';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $porArchivar = 
        \App\Models\Tickets::wherein("estado",["completado","rechazado"])
               ->where("updated_at", "<", Carbon::now()->subDays(15) )->delete();

            $this->info($porArchivar . " Eliminados de la lista");
    }
}
