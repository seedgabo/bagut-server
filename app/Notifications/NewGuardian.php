<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class NewGuardian extends Notification implements ShouldQueue
{
    use Queueable;
    public $guardian;
    public $ticket;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ticket, $guardian)
    {
        $this->ticket = $ticket;
        $this->guardian = $guardian;

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
                    ->subject('Nuevo '. Lang::choice('literales.guardian', 1) .": ". $this->guardian->nombre)
                    ->greeting('Hola ' . $notifiable->name)
                    ->line('Se ha cambiado el '. Lang::choice('literales.guardian', 1) .' para el  ' . Lang::choice('literales.ticket',1)  . " " . $ticket->titulo)
                    ->line('Nuevo ' . Lang::choice('literales.guardian', 1) .": ". $this->guardian->nombre)
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
            'ticket_id' => $this->ticket->id,
            'guardian_id' => $this->guardian_id,
            'titulo' => 'Nuevo ' . Lang::choice('literales.guardian', 1),
            'mensaje' => 'Se ha cambiado el ' . Lang::choice('literales.guardian', 1)
        ];
    }
}
