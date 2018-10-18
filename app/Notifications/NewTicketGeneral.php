<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class NewTicketGeneral extends Notification  implements ShouldQueue
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
                    ->subject('Nuevo '. Lang::choice('literales.ticket', 1) .": ". $this->ticket->titulo)
                    ->greeting('Hola ' . $notifiable->nombre)
                    ->line('Se ha creado un nuevo ' . Lang::choice('literales.ticket',1) . " en la categoría " .  $this->ticket->categoria->nombre)
                    ->action('ver', url('/ticket/ver/'.$this->ticket->id))
                    ->line('Titulo:'. $this->ticket->titulo )
                    ->line('Con fecha limite el: ' . \App\Funciones::transdate($this->ticket->vencimiento));
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
            'guardian_id' => $this->ticket->guardian_id,
            'user_id' => $this->ticket->user_id,
            'titulo' => "Nuevo ". Lang::choice('literales.ticket', 1) ." Creado en " . $this->ticket->categoria->nombre . ": " .$this->ticket->titulo,
            'texto'  => "Se ha creado un nuevo ". Lang::choice('literales.ticket', 1) ." en la categoría " .$this->ticket->categoria->nombre,
        ];
    }
}
