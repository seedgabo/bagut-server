<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Funciones;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Config;
use App\Notifications\EvaluacionPorVencerConductor;
use App\Notifications\EvaluacionVencidoConductor;
class sendRecordatorioEvaluacionesConductores extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'sendRecordatorio:EvaluacionesConductores';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Revisa y envia recordatorios a las proximas evaluaciones a conductores que se deben hacer';

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
    $fechaEnvio =  Carbon::now()->addDays(Config::get('setting.days_alerts',15));
    $users = \App\User::whereIn('email',explode(",", Config::get('settings.contact_email_evaluations_conductores',"seedgabo@gmail.com")))->get();
    $vencidos = \App\Models\EvaluacionConductor::
      where("fecha_proxima","<", $fechaEnvio)
    ->where(function($q){
      $q->where("alerta_preventiva", "=","");
      $q->orWhereNull("alerta_preventiva");
    })
    ->get();

    foreach ($vencidos as $evaluacion)
    {
         Notification::send($users, new EvaluacionPorVencerConductor($evaluacion));
         $evaluacion->alerta_preventiva = Carbon::now();
         $evaluacion->save();
         $this->info($evaluacion->id . " enviado correo preventino");
    }


    // VENCIDOS
    $fechaEnvio =  Carbon::now()->tomorrow()->startOfDay();
    $users = \App\User::whereIn('email',explode(",", Config::get('settings.contact_email_evaluations_conductores',"seedgabo@gmail.com")))->get();
    $vencidos = \App\Models\EvaluacionConductor::
      where("fecha_proxima","<", $fechaEnvio)
    ->where(function($q){
      $q->where("alerta_vencido", "=","");
      $q->orWhereNull("alerta_vencido");
    })
    ->get();

    foreach ($vencidos as $evaluacion)
    {
         Notification::send($users, new EvaluacionVencidoConductor($evaluacion));
         $evaluacion->alerta_vencido = Carbon::now();
         $evaluacion->save();
         $this->info($evaluacion->id . " enviado correo vencido");
    }
  }
}
