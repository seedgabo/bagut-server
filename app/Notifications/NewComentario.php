<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class NewComentario extends Notification implements ShouldQueue
{
    use Queueable;
    public $comentario;
    public $ticket;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($comentario)
    {
        $this->comentario = $comentario;
        $this->ticket = $comentario->ticket;
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
                    ->subject('Hola ' . $notifiable->name)
                    ->line('se ha anexado un nuevo ' . Lang::choice('literales.comentario', 1) . " al " . Lang::choice('literales.ticket', 1) .  ": ". $this->ticket->titulo )
                    ->action('ver', url('/ticket/ver/'.$this->ticket->id))
                    ->line('por el usuario '.  $this->comentario->user->nombre);
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
            'comentario_id' => $this->comentario->id,
            'ticket_id' => $this->ticket->id,
             'titulo' => 'Nuevo '. Lang::choice('literales.comentario', 1),
             'texto' => 'Se ha creado un nuevo ' . Lang::choice('literales.comentario', 1)
        ];
    }
}
