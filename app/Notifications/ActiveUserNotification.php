<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActiveUserNotification extends Notification
{
    use Queueable;

    private string $token;

    private string $name;

    /**
     * Create a new notification instance.
     *
     * @param string $token
     * @param string $name
     */
    public function __construct(string $token, string $name)
    {
        $this->token = $token;
        $this->name = $name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url("/api/auth/activate/" . $this->token);

        return (new MailMessage)
            ->subject("Activacion de usuario")
            ->markdown('auth.active-user', ["url" => $url, "name" => $this->name]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
