<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use App\Models\EmailVerify;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private EmailVerify $token,
    ) {
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
        return (new MailMessage())
            // TODO: Tłumaczenia
            ->subject('Email Verification')
            ->greeting('Hello,')
            ->action('Verify', route('auth.emailVerify.verify', ['token' => $this->token,]))
            ->line('Thank you for using our application!');
    }

}
