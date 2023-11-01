<?php

declare(strict_types=1);

namespace App\Notifications\Exercise;

use App\Models\Exercise;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExerciseVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Exercise $exercise)
    {

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
            ->subject('Exercise Verification')
            ->greeting('Hello,')
            ->action('Verify', route('web.exercises.verification.request', ['exercise' => $this->exercise,]))
            ->line('Thank you for using our application!');
    }
}
