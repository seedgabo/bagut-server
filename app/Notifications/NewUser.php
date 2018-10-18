<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

class NewUser extends Notification 
{
    use Queueable;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public function __construct($user)
    {
        $this->user = $user;
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
                ->subject('Nuevo Usuario')        
                ->greeting('Hola ' .$this->user->nombre)
                ->line('Su usuario en el sistema '. Config::get('app.name') . " ha sido creado exitosamente")
                ->line('Usuario: '. $this->user->email)
                ->line('Password: '. $this->user->email)
                ->action('Acceder al sistema', url('/login'));
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
            'user_id' => $this->user->id,
            'text' => 'Su usuario ha sido creado con exito, no olvida cambiar su clave',
            'title' => 'Bienvenido al Sistema'
        ];
    }
}
