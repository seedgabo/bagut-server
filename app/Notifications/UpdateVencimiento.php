<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class UpdateVencimiento extends Notification implements ShouldQueue
{
    use Queueable;
    public $ticket;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ticket)
    {
        $this->ticket = $ticket;    
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
        return (new MailMessage)
                    ->greeting("Hola ". $notifiable->name)
                    ->line('Se ha cambiado la fecha de '  .Lang::get('literales.vencimiento'). " de un " . Lang::choice('literales.ticket', 1))
                    ->line(Lang::choice('literales.ticket', 1) . ": " . $this->ticket->titulo)
                    ->action('ver', url('/ticket/ver/'.$this->ticket->id));
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
             'ticket_id' =>  $this->ticket->id,
             'vencimiento' => $this->ticket->vencimiento,
             'titulo' => "Actualización de caso", 
             'texto' => "Ha cambiado la fecha de plazo para el ". Lang::choice('literales.ticket', 1) . ": " .$this->ticket->titulo
        ];
    }
}
