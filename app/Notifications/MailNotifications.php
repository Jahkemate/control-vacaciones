<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MailNotifications extends Notification
{
    use Queueable;



    /**
     * Create a new notification instance.
     */
    public function __construct(
        public $request
    ) {
        $this->request = $request;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nueva Solicitud de Vacaciones')
            ->greeting('Hola ' . $notifiable->name)
            ->line('El empleado ha enviado una nueva solicitud.')
            ->line('Estado: Pendiente')
            ->action('Ver solicitud', url('/admin/vacation-requests/' . $this->request->id))
            ->line('Gracias!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
