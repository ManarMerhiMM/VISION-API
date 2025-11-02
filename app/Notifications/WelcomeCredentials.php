<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class WelcomeCredentials extends Notification
{
    use Queueable;

    public function __construct(
        public string $username,
        public string $plainPassword
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $appName = config('app.name', 'VISION');
        $frontend = rtrim(config('app.frontend_url', env('FRONTEND_URL', 'http://localhost:5173')), '/');
        $loginUrl = $frontend . '/login';


        return (new MailMessage)
            ->subject('Your ' . $appName . ' Account Credentials')
            ->greeting('Welcome to ' . $appName . '!')
            ->line('Your account has been created successfully. Glad to have you on board!')
            ->line('Here are your login credentials:')
            ->line('**Username:** ' . $this->username)
            ->line('**Temporary password:** ' . $this->plainPassword)
            ->line('Please log in and change your password as soon as possible for security reasons.')
            ->action('Open App', $loginUrl)
            ->salutation($appName . ' Team');
    }
}
