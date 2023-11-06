<?php

declare(strict_types=1);

namespace App\Notifications\Exercise;

use App\Models\Exercise;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifiedExerciseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Exercise $exercise,
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
            ->subject("Exercise {$this->exercise->name} has been verified 😊")
            ->greeting('Hello,')
            ->line("Your exercise named '{$this->exercise->name}'  has been successfully verified. Enjoy your workouts! 💪🏋️‍♀️")
            ->line('If you want your exercise to be public, please adhere to the changes mentioned in the message')
            ->action('Show', route('web.exercises.show', ['exercise' => $this->exercise]))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [

        ];
    }
}
