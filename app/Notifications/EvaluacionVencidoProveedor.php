<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;
use App\Models\Dispositivo;
use App\Models\Notificacion;
class EvaluacionVencidoProveedor extends Notification
{
    use Queueable;

    public function __construct($evaluacion)
    {
        $this->evaluacion = $evaluacion;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
      Dispositivo::sendPush(
        "Una Evaluación ha llegado a la fecha limite.",
        "Evaluacion: " . $this->evaluacion->evaluacion->titulo . ' a proveedor ' . $this->evaluacion->proveedor->nombre . " ha llegado a la fecha límite para reevaluación",
        [$notifiable->id]
      );


      Notificacion::create([
        'titulo' => "Una Evaluación ha llegado a la fecha limite.",
        'mensaje' => "Una Evaluación ha llegado a la fecha limite para reevaluación. Evaluacion: " . $this->evaluacion->evaluacion->titulo . 'a proveedor '. $this->evaluacion->proveedor->nombre ,
        'user_id' => $notifiable->id
      ]);


        return (new MailMessage)
          ->subject('Evaluación en fecha Límite')
          ->greeting('¡Hola!')
          ->line('Una evaluación para el proveedor ' . $this->evaluacion->proveedor->nombre . ' ha llegado a la fecha límite para reevaluación' )
          ->action('Ver Evaluación', url('/'))
          ->line('¡Gracias por usar el sistema!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
     public function toArray($notifiable)
     {
         return [
             'evaluacion_id' => $this->evaluacion->id,
             'fecha' => $this->evaluacion->fecha_proxima,
         ];
     }
}
