<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class TicketVencido extends Notification
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
                    ->greeting("Atencion!")
                    ->line('El ' . Lang::choice('literales.ticket', 1) . " ha expirado")
                    ->action('ver', url('/ticket/ver/'.$this->ticket->id))
                    ->line('Atienda lo mas pronto posible lo faltante y cierre el '. Lang::choice('literales.ticket', 1));
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
            'titulo' => 'Atención! ' . Lang::choice('literales.ticket', 1) . " ha expirado",
            'texto' => 'El ' . Lang::choice('literales.ticket', 1). "  ". $this->ticket->titulo ." ha llegado a la fecha límite", 
        ];
    }
}
