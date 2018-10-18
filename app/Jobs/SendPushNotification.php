<?php

namespace App\Jobs;

use App\Models\Dispositivo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPushNotification implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $titulo;
    protected $mensaje;
    protected $usuarios_ids;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($titulo,$mensaje,$usuarios_ids)
    {
        $this->titulo = $titulo;
        $this->mensaje = $mensaje;
        $this->usuarios_ids = $usuarios_ids;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Dispositivo::sendPush($this->titulo,$this->mensaje, $this->usuarios_ids);
    }
}
