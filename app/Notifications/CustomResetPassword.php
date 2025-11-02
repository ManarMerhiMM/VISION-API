<?php
// Sends a customized password reset email with a SPA link and action button.

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends Notification
{
    use Queueable;

    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $frontend = rtrim(config('app.frontend_url', env('FRONTEND_URL', 'http://localhost:5173')), '/');
        $resetUrl = $frontend.'/reset-password?token='.$this->token.'&email='.urlencode($notifiable->email);

        return (new MailMessage)
            ->subject('Reset your password')
            ->greeting('Hello '.$this->displayName($notifiable))
            ->line('We received a request to reset your password.')
            ->action('Reset Password', $resetUrl)
            ->line('This link will expire soon. If you did not request a password reset, ignore this email.')
            ->salutation('VISION Team');
    }

    protected function displayName($notifiable): string
    {
        return $notifiable->name
            ?? ($notifiable->first_name ?? 'there');
    }
}