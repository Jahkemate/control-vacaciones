<?php

namespace App\Notifications;

use App\Mail\VacationRequest\ApprovedRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApprovedNotifications extends Notification
{
    use Queueable;
    /**
     * Create a new notification instance.
     */
    public function __construct(
        public $request,
        public $user
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }
    /**
     * Get the mail representation of the notification.
     */
     public function toMail($notifiable)
    {
        return new ApprovedRequest(
            $this->request,
            $this->user
        )->to($notifiable->email);
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Solicitud aprobada',
            'message' => 'Tu solicitud de vacaciones fue aprobada',
            'request_id' => $this->request->id,
        ];
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
