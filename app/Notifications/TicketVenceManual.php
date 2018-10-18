<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TicketVenceManual extends Notification
{

    use Queueable;
    public $ticket;
    public $hours;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ticket, $hours)
    {
        //
        $this->ticket = $ticket;
        $this->hours = $hours;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
                    ->line('El ' . Lang::choice('literales.ticket', 1))
                    ->line('vencerá en menos de '. $this->hours . ' horas')
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
             'titulo' => "Recordatorio Ticket", 
             'texto' => "Recordatorio de vencimiento de ". Lang::choice('literales.ticket', 1)
        ];
    }
}
