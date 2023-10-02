<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserTemporaryPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private string $tempPassword)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Your temporary password')
            ->greeting('Hello,')
            ->line("Your temporary password is: <strong>{$this->tempPassword}</strong>")
            ->action('Reset password', route('auth.resetPassword.show'))
            ->line('Thank you for using our application!');
    }
}
