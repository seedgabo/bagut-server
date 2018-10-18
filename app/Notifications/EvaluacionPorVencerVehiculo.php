<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;
use App\Models\Dispositivo;
use App\Models\Notificacion;
class EvaluacionPorVencerVehiculo extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
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
         "Una Evaluación esta por vencerse.",
         "Evaluacion: " . $this->evaluacion->evaluacion->titulo . ' a vehiculo ' . $this->evaluacion->vehiculo->nombre,
         [$notifiable->id]
       );


       Notificacion::create([
         'titulo' => "Evaluación por vencerse",
         'mensaje' => "Una Evaluación esta por vencerse. Evaluacion: " . $this->evaluacion->evaluacion->titulo . 'a proveedor '. $this->evaluacion->vehiculo->nombre ,
         'user_id' => $notifiable->id
       ]);


         return (new MailMessage)
           ->subject('Evaluación Próxima a vencerse')
           ->greeting('¡Hola!')
           ->line('Una evaluación para el vehículo ' . $this->evaluacion->vehiculo->nombre . ' esta por vencer pronto' )
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
