<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendEmailReminderManual extends Command
{
    /**
        * The name and signature of the console command.
        *
        * @var string
        */
       protected $signature = 'sendMailReminderManual';

       /**
        * The console command description.
        *
        * @var strings
        * */
       protected $description = 'Envia un correo al usuario y al guardian de que el ticket vence en las horas establecidas';

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
            if (env('REMINDER_HOURS_1', '0') != '0')
            {
                $reminder_time = env('REMINDER_HOURS_1', '0');
                  $vencidos = \App\Models\Tickets::
                  where("vencimiento","<", Carbon::now()->addHours($reminder_time))
                  ->whereRaw("vencimiento  >  DATE_ADD(created_at, INTERVAL ". $reminder_time  ." HOUR )")
                  ->where(function($q){
                    $q->where("mail_alert_manual_1", "=","");
                    $q->orWhereNull("mail_alert_manual_1");
                  })
                  ->get();
                  
                  foreach ($vencidos as $ticket) {
                       Funciones::sendMailTicketVenceManual($ticket, $reminder_time);
                       $ticket->mail_alert_manual_1 = Carbon::now();
                       $ticket->save();
                       $this->info($ticket->titulo . " enviado correo de ticket por vencer manual 1");
                  }
            }

            if (env('REMINDER_HOURS_2', '0') != '0')
            {
                $reminder_time = env('REMINDER_HOURS_2', '0');
                  $vencidos = \App\Models\Tickets::
                  where("vencimiento","<", Carbon::now()->addHours($reminder_time))
                  ->whereRaw("vencimiento  >  DATE_ADD(created_at, INTERVAL ". $reminder_time  ." HOUR )")
                  ->where(function($q){
                    $q->where("mail_alert_manual_2", "=","");
                    $q->orWhereNull("mail_alert_manual_2");
                  })
                  ->get();
                  
                  foreach ($vencidos as $ticket) {
                       Funciones::sendMailTicketVenceManual($ticket, $reminder_time);
                       $ticket->mail_alert_manual_2 = Carbon::now();
                       $ticket->save();
                       $this->info($ticket->titulo . " enviado correo de ticket por vencer manual 2");
                  }
            }

            if (env('REMINDER_HOURS_3', '0') != '0')
            {
                $reminder_time = env('REMINDER_HOURS_3', '0');
                  $vencidos = \App\Models\Tickets::
                  where("vencimiento","<", Carbon::now()->addHours($reminder_time))
                  ->whereRaw("vencimiento  >  DATE_ADD(created_at, INTERVAL ". $reminder_time  ." HOUR )")
                  ->where(function($q){
                    $q->where("mail_alert_manual_3", "=","");
                    $q->orWhereNull("mail_alert_manual_3");
                  })
                  ->get();
                  
                  foreach ($vencidos as $ticket) {
                       Funciones::sendMailTicketVenceManual($ticket, $reminder_time);
                       $ticket->mail_alert_manual_3 = Carbon::now();
                       $ticket->save();
                       $this->info($ticket->titulo . " enviado correo de ticket por vencer manual 3");
                  }
            }            
        }
}
